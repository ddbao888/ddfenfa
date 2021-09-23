<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\MemberWithdrawCollection;
use App\Http\Resources\Zds\Member;
use App\Model\MemberWithdraw;
use App\Repository\MemberWithdrawRep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends BaseController
{
    public function index()
    {
        return $this->view('withdraw.index');
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $rep = new MemberWithdrawRep();
        $collection = $rep->list($this->data);
        return new MemberWithdrawCollection($collection);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $member = Auth::guard('web')->user();
        $memberWithdraw = MemberWithdraw::where('id', $data['id'])->first();
        if($memberWithdraw->status != 1) {
            return $this->error('处理失败，该订单已处理！');
        }
        $memberWithdraw->status = $data['status'];
        $memberWithdraw->handle_comment = $data['handle_comment'];
        $memberWithdraw->handle_uid = $member->id;
        $ret = $memberWithdraw->save();
        if($ret) {
            return $this->success('处理成功!');
        } else {
            return $this->error('处理失败!');
        }
    }
}
