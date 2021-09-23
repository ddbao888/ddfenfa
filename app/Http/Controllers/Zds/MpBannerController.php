<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\Zds\MpBannerCollection;
use App\Repository\Zds\MpBannerRep;
use App\Services\Zds\MpBannerService;
use Illuminate\Http\Request;

class MpBannerController extends BaseController
{
    protected $service,$rep;
    public function __construct(MpBannerService $service, MpBannerRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index()
    {
        return $this->view('setting.banner.index');
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $model = $this->rep->list($this->data);
        return new MpBannerCollection($model);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['uid'] = 1;
        $data['unicid'] = 1;
        return $this->service->store($data);
    }

    public function edit(Request $request)
    {
        $id = $request->get('id');
        $data = $request->all();
        $data['uid'] = 1;
        $data['unicid'] = 1;
        return $this->service->edit($id, $data);
    }

    public function destroy(Request $request)
    {
        $ids = $request->get('id');
        return $this->service->destroy($ids, 1);
    }

    public function setStatus(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        return $this->service->setStatus($id, 1, $status);
    }
}