<?php


namespace App\Services\Zds;


use App\Model\Zds\Level;
use App\Model\Zds\Member;

class LevelService extends BaseService
{
    public function store($data)
    {
        $level = Level::where('level_name', $data['level_name'])->first();
        if($level) {
            return $this->error('等级名称已经存在!');
        }
        $level = new Level();
        $level->level_name = $data['level_name'];
        $level->pic = $data['pic'];
        $level->uid = $data['uid'];
        $level->min = $data['min'];
        $level->max = $data['max'];
        $level->unicid = $data['unicid'];
        $level->status = $data['status'];
        $ret = $level->save();
        if($ret)
        {
            return $this->success('保存成功!');
        } else {
            return $this->success('保存失败!');
        }
    }

    public function edit($id, $data)
    {
        $level = Level::where('id', $id)->first();
        $level->level_name = $data['level_name'];
        $level->pic = $data['pic'];
        $level->uid = $data['uid'];
        $level->min = $data['min'];
        $level->max = $data['max'];
        $level->unicid = $data['unicid'];
        $level->status = $data['status'];
        $ret = $level->save();
        if($ret)
        {
            return $this->success('保存成功!');
        } else {
            return $this->success('保存失败!');
        }
    }

    public function destroy($id)
    {
        $member = Member::where('level_id', $id)->first();
        if($member) {
            return $this->error('用户中存在此等级，禁止删除!');
        }
        $num = Level::where(function($query)use($id){
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
        })->delete();
        if($num > 0) {
            return $this->success('删除成功!');
        } else {
            return $this->error('删除失败!');
        }
    }

    public function setStatus($id, $status)
    {
        $num = Level::where(function($query)use($id){
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
        })->update(['status' => $status]);
        if($num > 0) {
            return $this->success('设置成功!');
        } else {
            return $this->error('设置失败!');
        }
    }
}