<?php


namespace App\Http\Controllers\Zds;

use App\Http\Resources\Zds\QuestionCateCollection;
use App\Repository\Zds\QuestionCateRep;
use App\Services\Zds\QuestionCateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionCateController extends BaseController
{
    public function __construct(QuestionCateService $service, QuestionCateRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index()
    {
        return view('zds.question.cate');
    }

    public function list(Request $request)
    {
        $data = $request->all();
        $auth = Auth::user();
        $data['uid'] = $auth->id;
        $data['unicid'] = $auth->unicid;
        $model = $this->rep->list($data);
        return new QuestionCateCollection($model);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $auth = Auth::user();
        $data['uid'] = $auth->id;
        $data['unicid'] = $auth->unicid;
        return $this->service->store($data);
    }

    public function edit(Request $request)
    {
        $data = $request->all();
        $id = $request->get('id');
        return $this->service->edit($data);
    }

    public function destroy(Request $request)
    {
        $id = $request->get('id');
        return $this->service->destroy($id);
    }

    public function toJason(){
        $auth = Auth::user();
        $data['uid'] = $auth->id;
        $data['unicid'] = $auth->unicid;
        $model =  $this->rep->list($data, 'id', 'desc', false);
        return $this->success('成功', $model);
    }

}