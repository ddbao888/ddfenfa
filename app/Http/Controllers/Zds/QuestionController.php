<?php


namespace App\Http\Controllers\Zds;

use App\Http\Resources\Zds\QuestionCollection;
use App\Repository\Zds\QuestionRep;
use App\Services\Zds\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends BaseController
{
    protected $service, $rep;
    public function __construct(QuestionService $service, QuestionRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        return view('zds.question.index',['status' => $status]);
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $collection = $this->rep->list($this->data, 'sort');
        return new QuestionCollection($collection);
    }

    public function toJson(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        return $this->rep->toJson($this->data, ['id','question_title']);
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
        return $this->service->delete($id, $this->data);
    }

    public function setHot(Request $request)
    {
        $id = $request->get('id');
        $hot = $request->get('hot');
        return $this->service->setHot($id, $hot);
    }

    public function setStatus(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        return $this->service->setStatus($id, $status);
    }


}