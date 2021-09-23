<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\Zds\MemberCollection;
use App\Repository\Zds\MemberRep;
use App\Services\Zds\MemberService;
use Illuminate\Http\Request;

class MemberController extends BaseController
{
    public function __construct(MemberRep $rep,MemberService $service)
    {
        $this->rep = $rep;
        $this->service = $service;
    }

    public function index()
    {
        return $this->view('member.index');
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $collection = $this->rep->list($this->data);
        return new MemberCollection($collection);
    }

    public function store(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        return $this->service->store($this->data);
    }

    public function edit()
    {

    }
}
