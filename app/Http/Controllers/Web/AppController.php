<?php



namespace App\Http\Controllers\Web;

require app_path('../vendor/autoload.php');

use App\Helpers\PclZip;
use App\Helpers\PngFile;
use App\Model\App;
use Aws\Laravel\AwsFacade;
use CFPropertyList\CFPropertyList;
use Chumper\Zipper\Zipper;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class AppController extends BaseController
{

    public function  index()
    {

        return view('web.app.index');
    }

    public function list()
    {
        $list = App::orderBy('id', 'desc')->paginate(15);



        return $list;
    }

    public function delete($id)
    {
        $app = App::findOrFail($id);
        $ret = $app->delete();
        if($ret) {
            return response()->json(
                [
                    'status' => 'success',
                    'msg' => '删除成功',
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => 'warning',
                    'msg' => '删除失败',
                ]
            );
        }
    }

    /*解压苹果包*/
   /* public function ipaUp()
    {
        $file = Input::file('file');
        $ext = $file->getClientOriginalExtension();
        $time = date("YmdHis");
        $file = storage_path()."\\app\\public\\ios\\tmp\\".$time.'.ipa';
        $dir = storage_path()."\\app\\public\\ios\\tmp\\".$time.'\\payload';
        @move_uploaded_file($_FILES['file']['tmp_name'], $file);
        if($ext == 'ipa'){
            $zip = new PclZip($file);
            $zip->extract(PCLZIP_OPT_PATH, $dir, PCLZIP_OPT_BY_PREG, '/^Payload\/.*.app\/Info.plist$/');
            $zip->extract(PCLZIP_OPT_PATH, $dir, PCLZIP_OPT_BY_PREG, '/^Payload\/.*.app\/embedded.mobileprovision$/');
            $zip->extract(PCLZIP_OPT_PATH, $dir, PCLZIP_OPT_BY_PREG, '/^Payload\/.*.app\/(?!.*\/).*.png$/');
        }
        return response()->json(["extension" => $ext, "time" => $ext, "size" => $_FILES["file"]["size"]]);
    }*/

    function uploadImg(Request $request)
    {
        $file = Input::file('file');
        $ext = $file->getClientOriginalExtension();
        $extArra = ['png'];
        $size = round($_FILES["file"]["size"]/ 1048576 ,2);
        if(!in_array($ext, $extArra)) {
            return response()->json(
                [
                    'status' => 'warning',
                    'msg' => '文件格式有误!'
                ]
            );
        }

        if($size > 2) {
            return response()->json(
                [
                    'status' => 'warning',
                    'msg' => '文件大小不能超过2M!'
                ]
            );
        }

        $fileName =str_random(50);                //定义文件名称
        $fileNameFull=$fileName.".".$ext;

        $fullPath = '/image/'.$fileNameFull; //文件路劲
        $bool = Storage::put($fullPath, file_get_contents($file->getRealPath()));  //存储文件
        $url = storage_path()."\\app\\public\\image\\".$fileNameFull;
      /*  $s3Client = AwsFacade::createClient('s3');
        $s3_return = $s3Client->putObject([
            'Bucket' => "baobo", //存储桶（我的理解就是文件系统中的目录）
            'Key' => $fileNameFull, //文件名（包括后缀名）
            'SourceFile' => $url //要上传的文件
        ]);*/

        if($bool){
            return response()->json( [
                'status' => 'success',
                'url' => "/storage/image/".$fileNameFull
            ]);
        } else {
            return response()->json( [
                'status' => 'warning',
                'msg' => '上传错误!'
            ]);
        }

    }


    public function upload(Request $request)
    {
        $time = $request->get('time');
        $file = Input::file('file');

        $xml_uname = "kinka";
        if($file) { //安卓处理
            $ext = $file->getClientOriginalExtension();
            $extArra = ['ipa', 'apk'];
            $size = round($_FILES["file"]["size"]/ 1048576 ,2);
            if(!in_array($ext, $extArra)) {
                return response()->json(
                    [
                        'status' => 'warning',
                        'msg' => '文件格式有误!'
                    ]
                );
            }

            if($size > 2000) {
                return response()->json(
                    [
                        'status' => 'warning',
                        'msg' => '文件大小不能超过2G!'
                    ]
                );
            }

            if($ext == "apk") {
                $extension = $file->getClientOriginalExtension(); //获取文件扩展名
                $fileName =str_random(50);                //定义文件名称
                $fileNameFull=$fileName.".".$extension;

                $fullPath = '/apk/'.$fileNameFull; //文件路劲
                Storage::put($fullPath, file_get_contents($file->getRealPath()));  //存储文件

                $url = storage_path()."/app/public/apk/".$fileNameFull;

                //提交到S3
                $s3Client = AwsFacade::createClient('s3');
                $s3_return = $s3Client->putObject([
                    'Bucket' => env("Bucket"), //存储桶（我的理解就是文件系统中的目录）
                    'Key' => $fileNameFull, //文件名（包括后缀名）
                    'SourceFile' => $url //要上传的文件
                ]);

                if($s3_return['@metadata']['statusCode'] == 200){

                    $xml_plist =  $s3_return['@metadata']['effectiveUri'];
                } else {
                    return response()->json( [
                        'status' => 'warning',
                        'msg' => '上传错误!'
                    ]);
                }

                //提取安卓包中的信息
                $apk = new \ApkParser\Parser($url);

                $xml_mnvs = $apk->getManifest()->getMinSdkLevel();


                $xml_bid = $apk->getManifest()->getPackageName();

                $xml_bsvs = $apk->getManifest()->getVersionName();

                $xml_bvs = $apk->getManifest()->getVersionCode();

                $labelResourceId = $apk->getManifest()->getApplication()->getLabel();

                $appLabel = $apk->getResources($labelResourceId);

                $xml_name = detect_encoding($appLabel[0]);

                $resourceId = $apk->getManifest()->getApplication()->getIcon();
                $resources = $apk->getResources($resourceId);

                foreach($resources as $resource){
                    Storage::put('/img/'.$fileName.'.png', stream_get_contents($apk->getStream($resource)));  //存储文件
                    // fwrite(fopen('/img/'.$fileName.'.png', 'w'), stream_get_contents($apk->getStream($resource)));
                }
                $xml_icon = '/storage/img/'.$fileName.'.png';
                $xml_form = "Android";
                $xml_team = "*";
                $xml_type = 0;
                $size = formatsize($_FILES["file"]["size"]);

                $app = new App();
                $app->in_uname = $xml_uname;
                $app->in_name = $xml_name;
                $app->in_mnvs = $xml_mnvs;
                $app->in_bid = $xml_bid;
                $app->in_bvs = $xml_bvs;
                $app->in_bsvs = $xml_bsvs;
                $app->in_icon = $xml_icon;
                $app->in_form = $xml_form;
                $app->in_nick = $xml_team;
                $app->in_type = $xml_type;
                $app->in_plist = $xml_plist;
                $app->in_size = $size;
                $bool = $app->save();

                if(!$bool) {
                    return response()->json(
                        [
                            'status' => 'error',
                            'msg' =>'上传失败',
                            'size' => $size,
                        ]
                    );
                }
                return response()->json(
                    [
                        'status' => 'success',
                        'path' =>$xml_icon,
                        'size' => $size,
                    ]
                );
            } else {

                //解压苹果包
                //$file = Input::file('file');
                $ext = $file->getClientOriginalExtension();
                $time = date("YmdHis");
                //$file = storage_path()."\\app\\public\\ios\\tmp\\".$time.'.ipa';
                $dir = storage_path()."/app/public/ios/tmp/".$time."/";
                Storage::put("/ios/tmp/".$time.'.'.$ext, file_get_contents($file->getRealPath()));  //存储文件
                if($ext == 'ipa'){
                    $zip = new PclZip($file);
                    $zip->extract(PCLZIP_OPT_PATH, $dir, PCLZIP_OPT_BY_PREG, '/^Payload\/.*.app\/Info.plist$/');
                    $zip->extract(PCLZIP_OPT_PATH, $dir, PCLZIP_OPT_BY_PREG, '/^Payload\/.*.app\/embedded.mobileprovision$/');
                    $zip->extract(PCLZIP_OPT_PATH, $dir, PCLZIP_OPT_BY_PREG, '/^Payload\/.*.app\/(?!.*\/).*.png$/');
                }
                return response()->json(["status" => "continue","extension" => $ext, "time" => $time, "size" => $_FILES["file"]["size"]]);
            }

        }
        //处理苹果包
        if(empty($file) && $time) {
            $logo = $request->get('logo');

            $tmpDir = storage_path() . "/app/public/ios/tmp/" . $time;
            $tmp = "/ios/tmp/" . $time . '.ipa';
            $path = "/ios/attachment/".$time.'.ipa';// storage_path() . "\\app\\public\\ios\\attachment\\" . $time;
            $dir = storage_path()."/app/public/ios/tmp/".$time."/Payload";//storage_path() . "\\app\\public\\ios\\tmp\\" . $time . '\\Payload';
            if (is_dir($tmpDir)) {
                //Storage::move($tmp, $path);
                //rename($tmp, $path . '.ipa');
                $d = NULL;
                $h = opendir($dir);
                while ($f = readdir($h)) {
                    if ($f != '.' && $f != '..' && is_dir($dir . '/' . $f)) {
                        $d = $dir . '/' . $f;
                    }
                }
                closedir($h);
                $info = file_get_contents($d . '/Info.plist');
               // rename("/".$d."/embedded.mobileprovision",storage_path()."/ios/tmp/".$time."/embedded.mobileprovision");
                //Storage::move("/".$d."/embedded.mobileprovision","/ios/tmp/".$time."/embedded.mobileprovision");
                //dd($d);
                $mobileprovision = file_get_contents($d .'/embedded.mobileprovision');
                fwrite(fopen(storage_path()."/app/public/ios/tmp/".$time.'/embedded.mobileprovision', 'w'), convert_charset($mobileprovision));
                $mobileprovisionPath = "/storage/ios/tmp/".$time."/embedded.mobileprovision";
                $plist = new CFPropertyList();
                $plist->parse($info);
                $plist = $plist->toArray();
                $xml_name = detect_encoding(isset($plist['CFBundleDisplayName']) ? $plist['CFBundleDisplayName'] : $plist['CFBundleName']);
                $xml_mnvs = $plist['MinimumOSVersion'];
                $xml_bid = $plist['CFBundleIdentifier'];
                $xml_bsvs = $plist['CFBundleShortVersionString'];
                $xml_bvs = $plist['CFBundleVersion'];
                $newfile = storage_path()."/app\public/ios/attachment/".$time.'.png';
                $icon = $plist['CFBundleIcons']['CFBundlePrimaryIcon']['CFBundleIconFiles'];
                if (!$icon) {
                    $icon = $plist['CFBundleIconFiles'];
                    if (!$icon) {
                        $icon = $plist['CFBundleIconFiles~ipad'];
                    }
                }


                //提取苹果包中的logo
              /*  if (preg_match('/\./', $icon[0])) {
                    $cvt = is_file($d . '\\' . $icon[0]) ? 'trim' : 'strtolower';
                    for ($i = 0; $i < count($icon); $i++) {
                        if (is_file($d . '\\' . $cvt($icon[$i]))) {
                            $big[] = filesize($d . '\\' . $cvt($icon[$i]));
                            $small[] = filesize($d . '\\' . $cvt($icon[$i]));
                        }
                    }
                    rsort($big);
                    sort($small);
                    for ($p = 0; $p < count($icon); $p++) {
                        if ($big[0] == filesize($d . '\\' . $cvt($icon[$p]))) {
                            $bigfile = $d . '\\' . $cvt($icon[$p]);
                        }
                        if ($small[0] == filesize($d . '\\' . $cvt($icon[$p]))) {
                            $smallfile = $d . '\\' . $cvt($icon[$p]);
                        }
                    }
                } else {
                    $ext = is_file($d . '\\' . $icon[0] . '.png') ? '.png' : '@2x.png';
                    for ($i = 0; $i < count($icon); $i++) {
                        if (is_file($d . '\\' . $icon[$i] . $ext)) {
                            $big[] = filesize($d . '\\' . $icon[$i] . $ext);
                            $small[] = filesize($d . '\\' . $icon[$i] . $ext);
                        }
                    }
                    if(is_array($big) && is_array($small)) {
                        rsort($big);
                        sort($small);
                    }

                    for ($p = 0; $p < count($icon); $p++) {
                        if ($big[0] == filesize($d . '\\' . $icon[$p] . $ext)) {
                            $bigfile = is_file($d . '\\' . $icon[$p] . '@3x.png') ? $d . '\\' . $icon[$p] . '@3x.png' : $d . '\\' . $icon[$p] . $ext;
                        }
                        if ($small[0] == filesize($d . '\\' . $icon[$p] . $ext)) {
                            $smallfile = preg_match('/AppIcon20x20/', $icon[$p]) ? $d . '\\' . $icon[$p] . '@3x.png' : $d . '\\' . $icon[$p] . $ext;
                        }
                    }
                }

                //dd($smallfile);
                if(file_exists($smallfile)) {
                    $png = new PngFile($smallfile);
                    if (!$png->revertIphone($newfile)) {
                        if (!rename($bigfile, $newfile)) {
                            if ($plist['CFBundleIconFile']) {
                                if (preg_match('/\./', $plist['CFBundleIconFile'])) {
                                    rename($d . '\\' . $plist['CFBundleIconFile'], $newfile);
                                } else {
                                    rename($d . '\\' . $plist['CFBundleIconFile'] . '.png', $newfile);
                                }
                            } else {
                                copy(storage_path().'\\img\\app\\iOS.png', $newfile);
                            }
                        }
                    }
                } else {
                    Storage::copy('/', "/storage/img/" . $time . '.png');
                }*/

                //上传苹果包到S3
                $ipaFile = storage_path()."/app/public/ios/tmp/".$time.'.ipa';
                $s3Client = AwsFacade::createClient('s3');
                $s3_return = $s3Client->putObject([
                    'Bucket' => env("Bucket"), //存储桶（我的理解就是文件系统中的目录）
                    'Key' => $time.'.ipa', //文件名（包括后缀名）
                    'SourceFile' => $ipaFile //要上传的文件
                ]);

                if($s3_return['@metadata']['statusCode'] == 200){
                    $ipaUrl =  $s3_return['@metadata']['effectiveUri'];
                } else {
                    return response()->json( [
                        'status' => 'warning',
                        'msg' => '上传错误!'
                    ]);
                }


                //修改包中信息
                $xml_icon = $logo ? env("APP_URL").$logo : env("APP_URL")."/fenfa/static/app/iOS.png";
                $em = file_get_contents($d . '/embedded.mobileprovision');
                $xml_form = preg_match('/<key>Platform<\/key>([\s\S]+?)<string>([\s\S]+?)<\/string>/', $em, $m) ? $m[2] : 'iOS';
                $xml_nick = preg_match('/<key>Name<\/key>([\s\S]+?)<string>([\s\S]+?)<\/string>/', $em, $m) ? mb_convert_encoding($m[2], set_chars(), 'HTML-ENTITIES') : '*';
                $xml_type = preg_match('/^iOS Team Provisioning Profile:/', $xml_nick) ? 0 : 1;
                $xml_team = preg_match('/<key>TeamName<\/key>([\s\S]+?)<string>([\s\S]+?)<\/string>/', $em, $m) ? mb_convert_encoding($m[2], set_chars(), 'HTML-ENTITIES') : '*';
                //$url = env("APP_URL") . '/storage/ios/attachment/' . $time;
                $str = file_get_contents(public_path() . '/fenfa/static/app/down.plist');
                $str = str_replace(array('{ipa}', '{icon}', '{bid}', '{name}'), array($ipaUrl, $xml_icon, $xml_bid, $xml_name), $str);
                fwrite(fopen(storage_path()."/app/public/ios/attachment/".$time.'.ipa.plist', 'w'), convert_charset($str));

                $ipaPlistFile = storage_path()."/app/public/ios/attachment/".$time.'.ipa.plist';
                $s3_return = $s3Client->putObject([
                    'Bucket' => "baobo", //存储桶（我的理解就是文件系统中的目录）
                    'Key' => $time.'.ipa.plist', //文件名（包括后缀名）
                    'SourceFile' => $ipaPlistFile //要上传的文件
                ]);

                if($s3_return['@metadata']['statusCode'] == 200){
                    $xml_plist =  $s3_return['@metadata']['effectiveUri'];
                } else {
                    return response()->json( [
                        'status' => 'warning',
                        'msg' => '上传错误!'
                    ]);
                }

                $size = $request->get('size');
                $app = App::where('in_name', $xml_name)->where('in_bid', $xml_bid)->first();
                if(!$app){
                    $app = new App();
                }

                $app->in_uname = $xml_uname;
                $app->in_name = $xml_name;
                $app->in_mnvs = $xml_mnvs;
                $app->in_bid = $xml_bid;
                $app->in_bvs = $xml_bvs;
                $app->in_bsvs = $xml_bsvs;
                $app->in_icon = $xml_icon;
                $app->in_form = $xml_form;
                $app->in_nick = $xml_team;
                $app->in_type = $xml_type;
                $app->in_plist = $xml_plist;
                $app->in_size = $size;
                $app->in_mobilevision = $mobileprovisionPath;
                $bool = $app->save();

                if(!$bool) {
                    return response()->json(
                        [
                            'status' => 'error',
                            'msg' =>'上传失败',
                            'size' => $size,
                        ]
                    );
                }
                return response()->json(
                    [
                        'status' => 'success',
                        'path' =>$xml_icon,
                        'size' => $size,
                    ]
                );
            }
        }
    }


    public function info($id)
    {
        $app = App::findOrFail($id);

        $qr = QrCode::size(100)->generate(env('APP_URL')."/".$app->id);
        return view('app', ['app' => $app, 'qr' => $qr]);
    }

    public function install($id)
    {
        checkmobile() or strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') or exit('Access denied');
        $app = App::findOrFail($id);
        $kid = $app->in_kid;//getfield('app', 'in_kid', 'in_id', $id);
        $form = $app->in_form;//getfield('app', 'in_form', 'in_id', $id);
        $plist = $app->in_plist;//getfield('app', 'in_plist', 'in_id', $id);
        $uid = $app->in_uid;//getfield('app', 'in_uid', 'in_id', $id);
        $points = $app->in_points;//getfield('user', 'in_points', 'in_userid', $uid);
        //$points > 0 or exit(header('location:'.env('APP_URL')."/app/".$app->id));

        if($form == 'iOS'){
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
                $app->in_hits = $app->in_hits+1;
                $app->save();

                header('location:'.$plist);
            }else{
                $app->in_hits = $app->in_hits+1;
                $app->save();

                header('location:itms-services://?action=download-manifest&url='.$plist);
            }
        }else{
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
                $app->in_hits = $app->in_hits+1;
                $app->save();
                header('location:'.$plist);
            }else{
                $app->in_hits = $app->in_hits+1;
                $app->save();
                header('location:itms-services://?action=download-manifest&url='.$plist);
            }
        }
    }

    function  update(Request $request)
    {
        $id = $request->get("id");
        $in_name = $request->get("in_name");
        $in_icon = $request->get("in_icon");
        $app = App::where("id", $id)->first();
        if($app) {
            $app->in_name = $in_name;
            $app->in_icon = $in_icon;
            $ret = $app->save();
            if($ret){
                return response()->json(
                    [
                        'status' => 'success',
                        'msg' =>'保存成功',
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'msg' =>'保存失败',
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'msg' =>'app不存在',
                ]
            );
        }
    }







}