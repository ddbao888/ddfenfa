<?php


namespace App\Services\Zds;


use App\Model\Zds\Kxian;

class KxianService extends BaseService
{

    public function store($data)
    {
        $kxian = new Kxian();
        $kxian->cycle = $data['cycle'];
        $kxian->currency_id =  $data['currency_id'];
        $kxian->pic = $data['pic'];
        $kxian->uid = $data['uid'];
        $kxian->unicid = $data['unicid'];
        $ret = $kxian->save();
        if($ret) {
            return $this->success('新增成功!');
        } else {
            return $this->error('新增失败!');
        }
    }

    public function update($id, $data)
    {
        $kxian = Kxian::find($id);
        $kxian->cycle = $data['cycle'];
        $kxian->currency_id =  $data['currency_id'];
        $kxian->pic = $data['pic'];
        $ret = $kxian->save();
        if($ret) {
            return $this->success('保存成功!');
        } else {
            return $this->error('保存失败!');
        }
    }

    public function delete($id)
    {
        if(is_array($id)){
            $num = Kxian::whereIn('id', $id)->delete();
        } else {
            $num = Kxian::where('id', $id)->delete();
        }
        if($num > 0) {
            return $this->success('删除成功!');
        } else {
            return $this->success('删除失败!');
        }

    }

}