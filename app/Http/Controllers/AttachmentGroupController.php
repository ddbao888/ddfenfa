<?php


namespace App\Http\Controllers;


use App\Model\Attachment;
use App\Model\AttachmentGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachmentGroupController extends Controller
{
    public function list(Request $request)
    {
        $type = $request->get('type');
        $groupId = $request->get('attachment_group_id');
        $user = Auth::guard('web')->user();
        $data =  AttachmentGroup::where('uid', $user->id)
            ->where('unicid', 1)
            ->where('type', $type)->orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'msg' => '成功', 'data' => $data]);
    }


    public function store(Request $request)
    {
        $title = $request->get('title');
        $type = $request->get('type');
        $auth = Auth::guard('web')->user();
        $uid = $auth->id;
        $attachmentGroup = AttachmentGroup::where('title', trim($title))->where('uid', $uid)->where('type', $type)->first();
        if($attachmentGroup)
        {
            return response()->json(['status' => 'error', 'msg' => '名称已经存在']);
        }
        $attachmentGroup = new AttachmentGroup();
        $attachmentGroup->title = $title;
        $attachmentGroup->type = $type;
        $attachmentGroup->uid = $uid;
        $attachmentGroup->unicid = $auth->unicid;
        $ret = $attachmentGroup->save();

        if($ret)
        {
            $data =  AttachmentGroup::where('uid', 1)->where('unicid', 1)->where('type', $type)->orderBy('id', 'desc')->get();

            return response()->json(['status' => 'success', 'msg' => '成功', 'data' => $data]);
        } else {
            return response()->json(['status' => 'error', 'msg' => '添加失败!']);
        }
    }

    public function destroy(Request $request)
    {
        $groupId = $request->get('id');
        $attachment = Attachment::where('attachment_group_id', $groupId)->first();
        if($attachment)
        {
            return response()->json(['status' => 'error', 'msg' => '删除失败，附件中存在此目录!']);
        }
        $auth = Auth::guard('web')->user();
        $num = AttachmentGroup::where('id', $groupId)->where('uid', $auth->id)->delete();
        if($num > 0) {
            return response()->json(['status' => 'success', 'msg' => '删除成功']);
        } else {
            return response()->json(['status' => 'error', 'msg' => '删除失败']);
        }
    }
}