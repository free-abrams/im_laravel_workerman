<?php


namespace App\Workerman;

use App\Services\ChatRoomService;
use GatewayWorker\Lib\Gateway;

define('ping', 1);//ping 心跳  返回1
define('bind', 2);//bind 绑定  返回2
define('unbind', -1);
define('group_bind', 10000);//创建聊天室
define('group_member_join', 10001);//聊天室-用户进入聊天室
define('group_member_leave', 10002);//聊天室-用户离开聊天室
define('group_set_order_type', 11000);//聊天室-设置排序方式 自由排序 规则排序 固定排序
define('group_set_order', 11001);//聊天室-设置自由排序-排序
define('group_set_admin', 99999);//聊天室-设置管理员
define('group_member_ban', 10003);//聊天室-踢出用户
define('group_member_voice', 10004);//聊天室-用户禁言
define('group_update', 30000);//聊天室-更新频道属性
define('group_send_msg', 200);//聊天室-发送消息
define('group_messages', 100);//聊天室-推送消息
define('group_message_record', 300);//聊天室-获取消息记录

class Events
{
	
	public static function onWorkerStart($businessWorker)
	{
	}
	
	public static function onConnect($client_id)
	{
		Gateway::sendToClient($client_id, json_encode(array(
			'type' => 'init',
			'client_id' => $client_id
		)));
	}
	
	public static function onWebSocketConnect($client_id, $data)
	{
	}
	
	public static function onMessage($client_id, $message)
	{
		if ($debug = true) {
			$journal = [];
			$journal['client_id'] = $client_id;
			$journal['message'] = json_decode($message, true);
			echo json_encode($journal, JSON_UNESCAPED_UNICODE) . "\n";
		}
		try {
			$datas = json_decode($message, true);
			
			if (!$datas) {
				self::showMsg([], 400, '数据格式错误');
			}
			if ($datas['type'] > 100 && !isset($_SESSION['member_id'])) {
				self::showMsg($client_id, 401, '登录状态已超时，请重新登录');
			}
			// 根据类型执行不同的业务
			switch ($datas['type']) {
				case group_bind:
					ChatRoomService::group_bind($client_id, $datas);
					break;
				case group_member_join:
					ChatRoomService::group_member_join($client_id, $datas);
					break;
				case group_member_leave:
					ChatRoomService::group_member_leave($client_id, $datas);
					break;
				case group_set_order_type:
					ChatRoomService::group_set_order_type($client_id, $datas);
					break;
				case group_set_order:
					ChatRoomService::group_set_order($client_id, $datas);
					break;
				case group_set_admin:
					ChatRoomService::group_set_admin($client_id, $datas);
					break;
				case group_member_ban:
					ChatRoomService::group_member_ban($client_id, $datas);
					break;
				case group_member_voice:
					ChatRoomService::group_member_voice($client_id, $datas);
					break;
				case group_update:
					ChatRoomService::group_update($client_id, $datas);
					break;
				case group_send_msg:
					ChatRoomService::group_send_msg($client_id, $datas);
					break;
				case group_messages:
					ChatRoomService::group_messages($client_id, $datas);
					break;
				case group_message_record:
					ChatRoomService::group_message_record($client_id, $datas);
					break;
				case bind:
					ChatRoomService::bind($client_id, $datas);
					break;
				case unbind:
					ChatRoomService::unbind($client_id, $datas);
					break;
				default :
					return Gateway::sendToCurrentClient(self::showMsg([]));
			}
		} catch (\Exception $e) {
			self::showMsg([], 500, $e->getMessage());
		}
	}
	
	public static function showMsg($data = [], $errno = '200', $errmsg = 'SUCCESS')
	{
		$return['errno'] = $errno;
		$return['errmsg'] = $errmsg;
		$return['data'] = $data;
		if ($errno == 401) {
			return Gateway::closeClient($data, json_encode($return));
		}
		return Gateway::sendToCurrentClient(json_encode($return));
	}
	
	public static function onClose($client_id)
	{
		echo "设备断开连接" . $client_id . "\n";
		
	}
}
