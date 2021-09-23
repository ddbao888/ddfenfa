<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Zds\BaseController;
use App\Http\Resources\Zds\AttachmentCollection;
use App\Repository\AttachmentRep;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends  BaseController
{
    protected $service, $rep;
    public function  __construct(AttachmentService $service, AttachmentRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index()
    {

    }

    public function store(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $this->service->add($this->data);
    }

    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $auth = Auth::guard('web')->user();
        return $this->service->delete($id, $auth->id);
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $model = $this->rep->list($this->data);
        return new AttachmentCollection($model);
    }

}