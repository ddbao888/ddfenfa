<?php


namespace App\Http\Controllers\Zds;


use App\Repository\Zds\KxianRep;
use App\Services\Zds\KxianService;
use Illuminate\Http\Request;

class KxianController extends BaseController
{
    protected $service, $rep;

    public function __construct(KxianService $service, KxianRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index()
    {
        return $this->view('kxian.index');
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        return $this->rep->list($this->data);
    }

    public function store(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        return $this->service->store($this->data);
    }

    public function update(Request $request)
    {
        $id = $request->get('id');
        $data = $request->all();
        return $this->service->update($id, $data);

    }

    public function destroy(Request $request)
    {
        $id = $request->get('id');
        return $this->service->delete($id);
    }



}