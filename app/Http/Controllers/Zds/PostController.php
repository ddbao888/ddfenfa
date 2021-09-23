<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\Zds\Post;
use App\Http\Resources\Zds\PostCollection;
use App\Repository\Zds\PostRep;
use App\Services\Zds\PostService;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function index()
    {
        return $this->view('post.index');
    }

    public function list(Request $request)
    {

        $this->data = $request->all();
        $this->initData();
        $rep = new PostRep();
        $data = $rep->list($this->data);
        return new PostCollection($data);
    }

    public function store(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        return PostService::add($this->data);
    }

    public function update(Request $request)
    {
        $id = $request->get('id');
        $this->data = $request->all();
        unset($this->data['id']);
        return PostService::edit($id, $this->data);
    }
}