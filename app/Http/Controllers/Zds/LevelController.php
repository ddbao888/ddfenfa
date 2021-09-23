<?php


namespace App\Http\Controllers\Zds;


use App\Http\Controllers\Controller;
use App\Http\Resources\Zds\LevelCollection;
use App\Repository\Zds\LevelRep;
use App\Services\Zds\LevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends BaseController
{
    protected $service,$rep;
    public function __construct(LevelService $service, LevelRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index()
    {

        return $this->view('level.index');
    }

    public function list(Request $request)
    {
        $data = $request->all();
        $auth = Auth::user();
        $data['uid'] = $auth->id;
        $data['unicid'] = $auth->unicid;
        $model = $this->rep->list($data);
        return new LevelCollection($model);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $auth = Auth::user();
        $data['uid'] = $auth->id;
        $data['unicid'] = $auth->unicid;
        return isset($data['id']) && $data['id'] ? $this->service->edit($data['id'], $data) : $this->service->store($data);

    }

    public function edit(Request $request)
    {
        $data = $request->all();
        $id = $request->get('id');
        $auth = Auth::user();
        $data['uid'] = $auth->id;
        $data['unicid'] = $auth->unicid;
        return isset($data['id']) && $data['id'] ? $this->service->edit($id, $data) : $this->service->edit($id, $data);
    }

    public function destroy(Request $request)
    {
        $id = $request->get('id');
        return $this->service->destroy($id);
    }

    public function setStatus(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        return $this->service->setStatus($id, $status);
    }

    public function toJson(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $this->data['status'] = 2;
        return $this->rep->toJson($this->data);
    }

}