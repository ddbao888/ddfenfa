<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\Zds\UserCollection;
use App\Repository\UserRep;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected  $service, $rep;

    public function __construct(UserService $userService, UserRep $userRep)
    {
        $this->service = $userService;
        $this->rep = $userRep;
    }

    public function index()
    {
        return $this->view('user.index');
    }

    public function list(Request $request)
    {
        $this->data = $request->all();
        $this->initData();
        $collection = $this->rep->list($this->data);
        return new UserCollection($collection);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        return $this->service->add($data);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $id = $request->get('id');
        return $this->service->update($id, $data);
    }

    public function setStatus(Request $request)
    {
        $id = $request->get('id');

        return $this->service->setStatus($id);
    }
}