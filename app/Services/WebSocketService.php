<?php


namespace App\Services;
use App\ChatTask\ChatTask;
use App\Model\Zds\Member;
use App\Model\Zds\RoomUser;
use App\User;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Tymon\JWTAuth\Facades\JWTAuth;


class WebSocketService implements WebSocketHandlerInterface
{
    // 声明没有参数的构造函数
    public function __construct()
    {
    }
    /* public function onHandShake(Request $request, Response $response)
     {
        // 自定义握手：https://wiki.swoole.com/#/websocket_server?id=onhandshake
        // 成功握手之后会自动触发onOpen事件
     }*/
    public function onOpen(Server $server, Request $request)
    {
        // 在触发onOpen事件之前，建立WebSocket的HTTP请求已经经过了Laravel的路由，
        // 所以Laravel的Request、Auth等信息是可读的，Session是可读写的，但仅限在onOpen事件中。
        // \Log::info('New WebSocket connection', [$request->fd, request()->all(), session()->getId(), session('xxx'), session(['yyy' => time()])]);
        // 此处抛出的异常会被上层捕获并记录到Swoole日志，开发者需要手动try/catch
        $data = request()->all();
        Log::info('打开链接接受到的参数');
        Log::info($data);
        try{
            $server->push($request->fd, json_encode(array('code' => 0, 'msg'=>'链接成功')));
        }
        catch(\Exception $e)
        {
            $server->disconnect($request->fd);
        }

    }
    public function onMessage(Server $server, Frame $frame)
    {
        $data = json_decode($frame->data , true );
        Log::info('接受到的信息');
        Log::info($data);
        switch($data['type']) {
            case 1://登用户登录
                //$user = auth('api')->user();
                $user = Member::where('token', $data['token'])->first();
                //$user = JWTAuth::toUser($data['token']);
                //echo '欢迎'.$user->nick_name.'加入！';
            Log::info($user);
                ChatService::login($data['roomid'], $frame->fd, $user->nick_name, $user->uuid, $user->avatar);
                $server->push( $frame->fd, json_encode(array('code' => 0, 'type' => 1, 'msg'=>'欢迎'.$user->nick_name.'加入！')));
                break;
            case 2: //获取在线用户
                //$user = Member::where('token', $data['token'])->first();
                //echo '欢迎'.$user->nick_name.'加入！';
                //Log::info($user);
                $users = ChatService::getUsersByRoom($data['roomid']);

                $userNum = !empty($users) ? count($users) : 0;
                $items = [];
                if($userNum >0){
                    $i = 0;
                    foreach($users as $user)
                    {
                        if($i < 5) {
                            $items[] = ['avatar' => $user['avatar'], 'nickName' => $user['name']];
                        } else {
                            return;
                        }

                    }
                }
                //$users = $users->get(5);
                $server->push($frame->fd, json_encode(array('code' => 200, 'type' => 2, 'online_num' => $userNum, 'users' => $items)));
                break;
            case 3: // 改变房间
                $data = array(
                    'task' => 'change',
                    'params' => array(
                        'name' => $data['name'],
                        'avatar' => $data['avatar'],
                        'email' => $data['email'],
                    ),
                    'fd' => $frame->fd,
                    'oldroomid' => $data['oldroomid'],
                    'roomid' => $data['roomid']
                );
                $task = new ChatTask(json_encode($data));
                $ret = Task::deliver($task);
                echo $frame->fd . "改变房间\n";
                break;
            case 4: //私聊信息消息

                echo $server->push($frame->fd, json_encode(['time' => time()]));
                break;
            default :
                $server->push($frame->fd, json_encode(array('code' => 0, 'msg' => 'type error')));
        }
    }
    public function onClose(Server $server, $fd, $reactorId)
    {
        // 此处抛出的异常会被上层捕获并记录到Swoole日志，开发者需要手动try/catch

        //$user = auth('api')->user();
        //echo '欢迎'.$user->nick_name.'加入！';
       // ChatService::login($data['roomid'], $frame->fd, $user->nickName, $user->uuid, $user->avatar);

    }
}
