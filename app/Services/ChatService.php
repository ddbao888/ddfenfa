<?php


namespace App\Services;


class ChatService
{
    //打开连接
    public static function open( $data ){
        //返回4代表初始化房间以及获取在线用户
        $pushMsg['code'] = 4;
        $pushMsg['msg'] = 'success';
        $pushMsg['data']['mine'] = 0;
        $pushMsg['data']['users'] = self::getOnlineUsers();
        unset( $data );
        return $pushMsg;
    }


    /**
     * @return array|mixed
     * swoole_table版本
     */
    public static function getOnlineUsers(){
        $user = new ChatUsersService();
        $lists = $user->getOnlineUsers();
        return $lists;
    }



    //登陆
    public static function doLogin($data)
    {
        $domain = config('chat.domain');
        $pushMsg['code'] = 1;
        $pushMsg['msg'] = $data['params']['name']."加入了群聊";

        $pushMsg['data']['roomid'] = $data['roomid'];
        $pushMsg['data']['fd'] = $data['fd'];
        $pushMsg['data']['name'] = $data['params']['name'];
        $pushMsg['data']['avatar'] = $domain.'images/avatar/f1/f_'.rand(1,12).'.jpg';
        $pushMsg['data']['time'] = date("H:i",time());
        //增加房间的名字
        $pushMsg['data']['roomname'] = config('chat.rooms')[$data['roomid']];

        self::login($data['roomid'],$data['fd'],$data['params']['name'],$data['params']['email'],$pushMsg['data']['avatar']);
        unset( $data );
        return $pushMsg;
    }

    //登陆写入swoole_table中
    public static function login($roomid,$fd,$name,$uuid,$avatar){
        $user = new ChatUsersService(array(
            'roomid'    => $roomid,
            'fd'        => $fd,
            'name'		=> htmlspecialchars($name),
            'uuid'		=> $uuid,
            'avatar'	=> $avatar
        ));
        if(!$user->save()){
            throw new Exception('This nick is in use.');
        }
    }

    //登出
    public static function doLogout($data)
    {
        echo "退出################";
        var_dump($data);
        $roomid = $data['params']['roomid'];

        //从房间里删除用户
        $userArr = app('swoole')->ws_roomsTable->get($roomid);
        if ($userArr){
            $userArr = json_decode($userArr['users'],true);
            $key=array_search($data['fd'],$userArr);
            array_splice($userArr,$key,1);
            app('swoole')->ws_roomsTable->set($roomid, ['users' => json_encode($userArr)]);
        }


        //从房间用户信息删除
        $infos = app('swoole')->ws_roomUsersTable->get('roomUsersInfo'.$roomid);
        var_dump($infos);
        if ($infos){
            $infos = json_decode($infos['infos'],true);
            if (!empty($infos)){
                foreach ($infos as $info_key => $row){
                    if ($row['fd']==$data['fd']){
                        array_splice($infos,$info_key,1);
                        break;
                    }
                }
                var_dump($infos);
                app('swoole')->ws_roomUsersTable->set('roomUsersInfo'.$roomid,['infos'=>json_encode($infos)]);
            }
        }
        echo "退出结束################";


        //删除用户
        app('swoole')->ws_usersTable->del('user'.$data['fd']);

        $pushMsg['code'] = 3;
        $pushMsg['msg'] = $data['params']['name']."退出了群聊";
        $pushMsg['data']['fd'] = $data['fd'];
        $pushMsg['data']['name'] = $data['params']['name'];
        $pushMsg['data']['roomid'] = $roomid;
        unset( $data );
        return $pushMsg;
    }

    //改变房间
    public static function change( $data ){
        $pushMsg['code'] = 6;
        $pushMsg['msg']  = '换房成功';
        $user = new ChatUsersService(array(
            'roomid'    => $data['roomid'],//新的房间号
            'fd'        => $data['fd'],
            'name'		=> htmlspecialchars($data['params']['name']),
            'email'		=> $data['params']['email'],
            'avatar'	=> $data['params']['avatar']
        ));

        $is_copyed = $user->changeUser($data['oldroomid'],$data['fd'],$data['roomid']);

        if($is_copyed){
            $pushMsg['data']['oldroomid'] = $data['oldroomid'];
            $pushMsg['data']['roomid'] = $data['roomid'];
            $pushMsg['data']['mine'] = 0;
            $pushMsg['data']['fd'] = $data['fd'];
            $pushMsg['data']['name'] = $data['params']['name'];
            $pushMsg['data']['avatar'] = $data['params']['avatar'];
            $pushMsg['data']['time'] = date("H:i",time());
            unset( $data );
            return $pushMsg;
        }
        return false;
    }

    public static function getUsersByRoom($roomid)
    {
        $infos = app('swoole')->ws_roomUsersTable->get('roomUsersInfo'.$roomid);
        if ($infos){
            $users = json_decode($infos['infos'],true);
        }else{
            $users =[];
        }
        return $users;
    }

    public static function sendMessage($fd, $type, $msg)
    {
        $swoole = app('swoole');
        $success = $swoole->push($fd, json_encode(['code' => 0, 'type' => $type, 'msg' => $msg]));
    }

}