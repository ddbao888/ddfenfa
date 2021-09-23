<?php


namespace App\Services;


use App\Model\Attachment;

class AttachmentService
{

    public function add($data)
    {
        $attachment = new Attachment();
        $attachment->unicid=$data['unicid'];
        $attachment->uid = $data['uid'];
        $attachment->title = $data['title'];
        $attachment->path = $data['path'];
        $attachment->url = $data['url'];
        $attachment->attachment_group_id = $data['attachment_group_id'];
        $attachment->size = $data['size'];
        $attachment->type = $data['type'];
        $ret = $attachment->save();
        if($ret) {
            return response()->json(['data' => ['status' => 'success', 'msg' => '保存成功']]);
        } else {
            return response()->json(['data' => ['status' => 'error', 'msg' => '保存失败']]);
        }
    }

    public function delete($uuid, $uid)
    {
        $ret = Attachment::where('uuid', $uuid)->where('uid', $uid)->delete();
        if($ret) {
            return response()->json(['status' => 'success', 'msg' => '删除成功']);
        } else {
            return response()->json(['status' => 'error', 'msg' => '删除失败']);
        }
    }

}