<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\Zds\QuestionItemCollection;
use App\Imports\QuestionItemImport;
use App\Repository\Zds\QuestionItemRep;
use App\Services\Zds\QuestionItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class QuestionItemController extends BaseController
{
    protected $service ,$rep;
    public function  __construct(QuestionItemService $service, QuestionItemRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        return $this->view('question.item.list', ['status' => $status]);
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $collection = $this->rep->list($this->data);
        return new QuestionItemCollection($collection);
    }

    public function store(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        return $this->service->store($this->data);
    }

    public function edit(Request $request)
    {
        $this->data = $request->all();
        $id = $request->get('id');
        $this->initData();
        return $this->service->edit($id, $this->data);
    }

    public function setStatus(Request $request)
    {
        $id = $request->get('id');
        $this->data = $request->all();
        $this->initData();
        return $this->service->setStatus($id, $this->data);

    }

    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $this->initData();
        return $this->service->destroy($id, $this->data);
    }

    public function import(Request $request)
    {
        return $this->view('question.item.import');
    }

    public function importStore(Request $request)
    {
        //$importClass = 'App\\Imports\\QuestionItemImport';
        $filePath = $request->get('filePath');
        $data = Excel::toArray(new QuestionItemImport(),  storage_path('app').$filePath);
        $items = $data[0];
        unset($items[0]);
        $auth = Auth::guard('web')->user();
        return $this->service->import($items, $auth->id, $auth->unicid);
    }

    public function attachment(Request $request)
    {
        return $this->view('question.item.attachment');
    }
}