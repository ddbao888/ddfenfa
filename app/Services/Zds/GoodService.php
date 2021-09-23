<?php


namespace App\Services\Zds;


use App\Model\Zds\Good;
use App\Model\Zds\QuestionReward;

class GoodService extends BaseService
{

    public function store($data)
    {
        $good = Good::where('uid', $data['uid'])->where('unicid', $data['unicid'])->where('good_name', trim($data['good_name']))->first();
        if($good)
        {
            return $this->error('物品名称已经存在!');
        }
        $good = new Good();
        $good->good_name = $data['good_name'];
        $good->type = $data['type'];
        $good->good_price = isset($data['good_price']) ? $data['good_price'] : 0;
        $good->good_price2 = isset($data['good_price2']) ? $data['good_price2'] : 0;
        $good->gold = isset($data['gold']) ? $data['gold'] : 0;
        $good->desc = isset($data['desc']) ? $data['desc'] : '';
        $good->pic = json_encode($data['pic']);
        $good->uid = $data['uid'];
        $good->unicid = $data['unicid'];
        $good->content = $data['content'];
        $ret = $good->save();
        if($ret)
        {
            return $this->success('新增成功');
        }
        else{
            return $this->error('新增失败!');
        }

    }

    public function edit($id, $data)
    {
        $good = Good::where('id', $id)->where(function($query)use($data){
            if($data['is_plat_manager'] != self::ISMANAGER) $query->where('uid', $data['uid']);
        })->first();
        if(!$good)
        {
            return $this->error('物品不存在!');
        }
        $good->good_name = $data['good_name'];
        $good->type = $data['type'];
        $good->good_price = $data['good_price'];
        $good->good_price2 = $data['good_price2'];
        $good->content = $data['content'];
       // $good->pic = $data['pic'];
        $good->uid = $data['uid'];
        $good->unicid = $data['unicid'];
        $ret = $good->save();
        if($ret)
        {
            return $this->success('编辑成功');
        }
        else{
            return $this->error('编辑失败!');
        }
    }

    public function setStatus($id, $status, $data)
    {
        $num = Good::where(function($query)use($data, $id){
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
            if($data['is_plat_manager'] != self::ISMANAGER) $query->where('uid', $data['uid']);

        })->update(['status' => $status]);
        if($num)
        {
            return $this->success('设置成功');
        }
        else{
            return $this->error('设置失败!');
        }
    }

    public function destroy($id, $data)
    {
        $questionReward = QuestionReward::where(function($query)use($id){
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
        })->first();
        if($questionReward) {
            return $this->error('删除失败,奖励中含有该物品');
        }
        $num = Good::where(function($query)use($data, $id){
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
            if($data['is_plat_manager'] != self::ISMANAGER) $query->where('uid', $data['uid']);

        })->delete();
        if($num)
        {
            return $this->success('删除成功');
        }
        else{
            return $this->error('删除失败!');
        }
    }


}
