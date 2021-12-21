<?php


namespace App\Services;


use App\Models\ChatGroup;
use App\Models\ChatMessage;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserHasFollow;
use GatewayWorker\Lib\Gateway;

/**
 * Created By FreeAbrams
 * Date: 2021/6/17
 */
class ChatRoomService
{
    static public function group_bind($client_id, $message)
    {
        if(!isset($message['user_id']) || empty($message['user_id'])){
            throw new \Exception('Data in wrong format');
        }
        $user_id=Gateway::getUidByClientId($client_id);
        if(!$user_id){
            throw new \Exception('Invalid login status');
        }
        $author=[$user_id,$message['user_id']];
        sort($author);
        $group=ChatGroup::query()->firstOrCreate(['author'=>$author[0],'invited'=>$author[1]]);
        if(!$group){
            throw new \Exception('Server error');
        }

        return self::showMsg(["type"=>'group_bind',"group_id"=>$group->id,"user_data"=>self::get_user_nickname($author)]);
    }

    static public function group_member_join($client_id, $message)
    {
        if(!isset($message['group_id']) || empty($message['group_id'])){
            throw new \Exception('Data in wrong format');
        }
        $user_id=Gateway::getUidByClientId($client_id);
        if(!$user_id){
            throw new \Exception('Invalid login status');
        }
        $_SESSION['group_id'] = $message['group_id'];
        $sql=ChatMessage::query()
            ->where('group_id','=',$message['group_id'])
            ->where('user_id','<>',$user_id)
            ->where('read_num','=',0);

        $lists=$sql->orderBy('created_at','asc')
            ->get()
            ->toArray();
        $sql->update(['read_num'=>1,'is_push'=>1]);
        return self::showMsg(["type"=>'group_member_join',"group_id"=>$message['group_id'],"lists"=>$lists]);
    }

    static public function group_member_leave($client_id, $message)
    {
        if(!isset($message['group_id']) || empty($message['group_id'])){
            throw new \Exception('Data in wrong format');
        }
        $user_id=Gateway::getUidByClientId($client_id);
        if(!$user_id){
            throw new \Exception('Invalid login status');
        }
        unset($_SESSION['group_id']);
        return self::showMsg([]);
    }

    static public function group_set_order_type($client_id, $message)
    {
        return $message;
    }

    static public function group_set_order($client_id, $message)
    {
        return $message;
    }

    static public function group_set_admin($client_id, $message)
    {
        return $message;
    }

    static public function group_member_ban($client_id, $message)
    {
        return $message;
    }

    static public function group_member_voice($client_id, $message)
    {
        return $message;
    }

    static public function group_update($client_id, $message)
    {
        return $message;
    }

    static public function group_send_msg($client_id, $message)
    {
        if(!isset($message['group_id']) || empty($message['group_id'])){
            throw new \Exception('Data in wrong format');
        }
        $user_id=Gateway::getUidByClientId($client_id);
        if(!$user_id){
            throw new \Exception('Invalid login status');
        }
        $group=ChatGroup::query()->select('author','invited')->find($message['group_id'])->toArray();
        if(!$group){
            throw new \Exception('Server error');
        }
        $where=[];
        $where[]=['user_id','=',$group['author']];
        $where[]=['follow_id','=',$group['invited']];
        $orwhere=[];
        $orwhere[]=['user_id','=',$group['invited']];
        $orwhere[]=['follow_id','=',$group['author']];
        $author_nickname=UserHasFollow::query()
            ->where($where)
            ->orWhere($orwhere)
            ->get();
        if(is_null($author_nickname)){
            return self::showMsg(["type"=>'group_send_msg',"group_id"=>$message['group_id']],500,'对方已不是你的粉丝，或者你未关注对方，发送信息失败');
        }
        if($group['author'] == $user_id){
            $follow_id = $group['invited'];
        }else{
            $follow_id = $group['author'];
        }


        $datas=[];
        $datas['group_id']=$message['group_id'];
        $datas['user_id']=$user_id;
        $datas['type']=$message['message']['type'];
        $datas['content']=$message['message']['content'];
        if(isset($message['message']['remarks'])){
            $datas['remarks']=$message['message']['remarks'];
        }
        $online=Gateway::isUidOnline($follow_id);
        if(!$online){
            $datas['is_push']=2;
            $datas['read_num']=0;
        }else{
            echo "用户：".$follow_id."在线\n";
            //对方是否再聊天室内
            $datas['is_push']=1;
            $datas['read_num']=0;
            $sessions=Gateway::getAllClientSessions();
            $client_list=Gateway::getClientIdByUid($follow_id);
            foreach ($client_list as $key =>$value){
                if(isset($sessions[$value]) && isset($sessions[$value]['group_id']) && $sessions[$value]['group_id'] == $message['group_id']){
                    $datas['read_num']=1;
                    echo "用户：".$follow_id."一个设备在房间内\n";
                }
            }
        }
        $sql=ChatMessage::query()->create($datas);
        if(!$sql){
            throw new \Exception('Server error');
        }
        //发送消息
        self::showMsg(["type"=>'group_send_msg',"group_id"=>$message['group_id'],"messages_id"=>$sql->id]);
        return self::sendMsg([$user_id,$follow_id],['type'=>'group_messages','messages'=>$sql->toArray()]);

    }
    static public function group_message_record($client_id, $message)
    {
        if (!isset($message['group_id']) || empty($message['group_id'])) {
            throw new \Exception('Data in wrong format');
        }
        $user_id = Gateway::getUidByClientId($client_id);
        if (!$user_id) {
            throw new \Exception('Invalid login status');
        }
        $group = ChatGroup::query()->select('author', 'invited')->find($message['group_id'])->toArray();
        if (!$group) {
            throw new \Exception('Server error');
        }
        $condition=[];
        $condition[]=['group_id','=',$message['group_id']];
        if((int)$message['message_id'] > 0){
            $condition[]=['id','<',(int)$message['message_id']];
        }
        $limit=isset($message['limit'])?$message['limit']:10;
        $lists=ChatMessage::query()->where($condition)->limit($limit)->orderBy('created_at','desc')->get()->sortBy('created_at')->values()->toArray();
        return self::showMsg(["type"=>'group_message_record',"group_id"=>$message['group_id'],"lists"=>$lists]);
    }

    static public function group_messages($client_id, $message)
    {
        return $message;
    }


    static public function bind($client_id, $message)
    {
        if(!isset($message['token']) || empty($message['token'])){
            throw new \Exception('token does not exist');
        }
        $condition = [];
        $condition[] = ['token', '=', $message['token']];
        $reset = PasswordReset::query()->where($condition)->first();
        if (!$reset || (isset($reset->time_out) && $reset->time_out >= 0 && $reset->time_out <= time())) {
            throw new \Exception('Invalid login status');
        }
        $lists=Gateway::getClientIdByUid($reset->user_id);
        $_SESSION['member_id']=$reset->user_id;
        if(!in_array($client_id,$lists)){
            Gateway::bindUid($client_id,$reset->user_id);
        }
        //1.获取分组信息
        $where=[];
        $where[]=['author','=',$reset->user_id];
        $where[]=['invited','=',$reset->user_id,'OR'];
        $group = ChatGroup::query()->select('id','author', 'invited')->where($where)->get();
        if (!$group) {
            throw new \Exception('Server error');
        }
        $group_id = $group->pluck('id');
        $group_data=$group->groupBy('id')->toArray();
        $message= ChatMessage::query()->whereIn('group_id',$group_id)->where('user_id','<>',$reset->user_id)->where('read_num','=',0)->orderBy('created_at','desc')->orderBy('id','desc')->get();
        $lists=[];
        $lists['counts']=0;
        $lists['datas']=[];
        if($message){
            $lists['counts']= $message->count();
            $datas_group=$message->groupBy('group_id')->toArray();
            foreach ($datas_group as $key =>$value){
                $li=[];
                $li['group_id']=$key;
                $li['counts']=count($value);
                $author=collect($group_data[$key][0])->only(['author','invited'])->values()->toArray();
                $li['users']=self::get_user_nickname($author);
                $li['messages']=$value[0];
                $lists['datas'][]=$li;
            }
        }
        return self::showMsg(["type"=>'login',"user_id"=>$reset->user_id,"lists"=>$lists]);
    }
    static public function unbind($client_id, $message)
    {
        $user_id=Gateway::getUidByClientId($client_id);
        if(!$user_id){
            throw new \Exception('Invalid login status');
        }
        Gateway::unbindUid($client_id,$user_id);
        return self::showMsg(["type"=>'unbind']);
    }
    public static function showMsg($data = [], $errno = '200', $errmsg = 'SUCCESS')
    {
        $return['errno'] = $errno;
        $return['errmsg'] = $errmsg;
        $return['data'] = $data;
        return Gateway::sendToCurrentClient(json_encode($return));
    }
    public static function sendMsg($uid= [],$data = [], $errno = '200', $errmsg = 'SUCCESS')
    {
        $return['errno'] = $errno;
        $return['errmsg'] = $errmsg;
        $return['data'] = $data;
        return Gateway::sendToUid($uid,json_encode($return));
    }
    public  static function get_user_nickname($author){
        $where=[];
        $where[]=['user_id','=',$author[1]];
        $where[]=['follow_id','=',$author[0]];
        $author_nickname=UserHasFollow::query()->where($where)->value('nickname');
        $where=[];
        $where[]=['user_id','=',$author[0]];
        $where[]=['follow_id','=',$author[1]];
        $invited_nickname=UserHasFollow::query()->where($where)->value('nickname');
        $author_user=User::query()->select('id','name','cover')->find($author[0])->toArray();
        $invited_user=User::query()->select('id','name','cover')->find($author[1])->toArray();
        if($author_nickname){
            $author_user['name']=$author_nickname;
        }
        if($invited_nickname){
            $invited_user['name']=$invited_nickname;
        }
        return [$author_user,$invited_user];
    }
}
