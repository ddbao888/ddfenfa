<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FileUploadController extends \App\Http\Controllers\Zds\BaseController
{
    public function store(Request $request)
    {
        $file = Input::file('file');
        $size = round($_FILES["file"]["size"] / 1048576 ,2);
        /* $workspace = $user->workspace;
         if(!$this->workspaceSize($workspace, $size)) {
             return $this->errorMessage('空间已满！');
         }*/
        $ext = $file->getClientOriginalExtension();
        $extArra = ['csv', 'xlsx', 'xls'];
        if(!in_array($ext, $extArra)) {
            return $this->error('您上传的文件格式暂不支持,请上传csv、xlsx、xls格式文件!');
        }

        if($size > 2) {
            return $this->error('文件大小不能超出'.'2M');
        }

        $destinationPath='/'.$ext; //定义目录
        $extension = $file->getClientOriginalExtension(); //获取文件扩展名
        $fileName =str_random(40);                //定义文件名称
        $fileNameFull=$fileName.'@.'.$extension;
        $fullPath = $destinationPath.'/'.$fileNameFull; //文件路劲
        $bool = Storage::put($fullPath, file_get_contents($file->getRealPath()));  //存储文件
        if(!$bool) {
            return $this->error('文件上传失败!');
        }
        return $this->success('上传成功!', ['url'=>'/public'.$fullPath]);
    }
}