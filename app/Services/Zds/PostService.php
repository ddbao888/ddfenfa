<?php


namespace App\Services\Zds;


use App\Model\Zds\Post;

class PostService extends BaseService
{


    public static function add($data)
    {
        $post = Post::where('title', $data['title'])->first();
        if($post) {
            return self::error('标题已经存在!');
        }
        $ret = Post::create($data);
        if($ret) {
            return response()->json(['status' => 'success','msg' => '新增成功', 'code' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 'error','msg' => '新增失败', 'code' => 200, 'data' => $data]);
        }
    }

    public static function edit($id, $data)
    {
        $ret = Post::where('id', $id)->update($data);
        if($ret) {
            return response()->json(['status' => 'success','msg' => '修改成功', 'code' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 'error','msg' => '修改失败', 'code' => 200, 'data' => $data]);
        }
    }

}