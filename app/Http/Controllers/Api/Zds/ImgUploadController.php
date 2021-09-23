<?php


namespace App\Http\Controllers\Api\Zds;


use App\Model\MemberImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ImgUploadController extends BaseController
{

    public function store(Request $request)
    {
        $user = auth('api')->user();
        $file = Input::file('file');
        $size = round($_FILES["file"]["size"] / 1048576 ,2);
        $ext = $file->getClientOriginalExtension();
        $extensions = ['jpg', 'jpeg', 'png'];

        if(!in_array($ext, $extensions)) {
            return $this->error('您上传的文件格式暂不支持,请联系系统管理员13151523039帮助!');
        }
        if($size > 2) {
            return $this->error('文件大小不能超出2M');
        }
        $destinationPath='/images/'.$ext; //定义目录
        $extension = $file->getClientOriginalExtension(); //获取文件扩展名
        $fileName =str_random(40);                //定义文件名称
        $fileNameFull=$fileName.'@.'.$extension;
        $fullPath = $destinationPath.'/'.$fileNameFull; //文件路劲
        $bool = Storage::put($fullPath, file_get_contents($file->getRealPath()));  //存储文件
        if(!$bool) {
            return $this->error('文件上传失败!');
        }
            //$file->move($destinationPath, $fileNameFull);
            $resourceNode = new MemberImage();
            $resourceNode->uid = $user->id;
            $resourceNode->pic = $fullPath;
            $resourceNode->type = 'zds';
            $resourceNode->size = $size;
            $resourceNode->save();

            return response()->json(
                [
                    'status' => 'success',
                    'path' =>env('APP_URL').'/storage'.$fullPath,
                    'size' => $size,
                ]
            );
    }
}