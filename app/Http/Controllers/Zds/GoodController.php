<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\Zds\GoodCollection;
use App\Repository\Zds\GoodRep;
use App\Services\Zds\GoodService;
use Illuminate\Http\Request;

class GoodController extends BaseController
{

    public function __construct(GoodService $service, GoodRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index()
    {
        return $this->view('good.index');
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $collection = $this->rep->list($this->data);
        return new GoodCollection($collection);
    }

    public function store(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        return $this->service->store($this->data);
    }

    public function edit(Request $request)
    {
        $id = $request->get('id');
        $this->data = $request->all();
        $this->initData();
        return $this->service->edit($id, $this->data);
    }

    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $this->initData();
        return $this->service->destroy($id, $this->data);
    }

    public function setStatus(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        $this->initData();
        return $this->service->setStatus($id, $status, $this->data);
    }

}