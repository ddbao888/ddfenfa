<?php


namespace App\Services\Zds;


use App\Model\Zds\MpBanner;

class MpBannerService extends BaseService
{
    public function store($data)
    {
        $mpBanner = new MpBanner();
        $mpBanner->unicid = $data['unicid'];
        $mpBanner->uid = $data['uid'];
        $mpBanner->title = $data['title'];
        $mpBanner->pic = $data['pic'];
        $mpBanner->page = $data['page'];
        $mpBanner->status = $data['status'];
        $ret = $mpBanner->save();
        if($ret) {
            return $this->success('新增成功!');
        } else {
            return $this->error('新增失败!');
        }
    }

    public function edit($id, $data)
    {
        $mpBanner = MpBanner::where('uuid', $id)->where('uid', $data['uid'])->first();
        $mpBanner->title = $data['title'];
        $mpBanner->pic = $data['pic'];
        $mpBanner->page = $data['page'];
        $mpBanner->status = $data['status'];
        $ret = $mpBanner->save();
        if($ret) {
            return $this->success('编辑成功!');
        } else {
            return $this->error('编辑失败!');
        }
    }

    public function destroy($id, $uid)
    {
        $num = MpBanner::where(function($query)use($id) {
            if(is_array($id)) {
                $query->whereIn('uuid', $id);
            } else {
                $query->where('uuid', $id);
            }
        })->where('uid', $uid)->delete();
        if($num > 0) {
            return $this->success('删除成功!');
        } else {
            return $this->error('删除失败!');
        }
    }

    public function setStatus($id,$uid, $status)
    {
        $ret = MpBanner::where(function($query)use($id) {
            if(is_array($id)) {
                $query->whereIn('uuid', $id);
            } else {
                $query->where('uuid', $id);
            }
        })->where('uid', $uid)->update(['status' => $status]);
        if($ret) {
            return $this->success('修改成功!');
        } else {
            return $this->error('修改失败!');
        }
    }
}