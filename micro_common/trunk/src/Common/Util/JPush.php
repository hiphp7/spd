<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/24
 * Time: 17:53
 */

namespace Micro\Common\Common\Util;

use Illuminate\Support\Facades\Log;
use JPush\Client;

class JPush {
	private $jpush;
	public function __construct() {
		$app_key = config('jpush.APP_KEY');
		$master_secret = config('jpush.MASTER_SECRET');

		$this->jpush = new Client($app_key, $master_secret);
	}

    /**
     * @desc 单个向用户推送消息
     * @param $alias string 用户id
     * @param $title string 消息标题
     * @param $msg string 消息内容
     * @param $data array 数据
//     * @param $type string 客户端类型 IOS,Android,WinPhone All(全部类型)
     * @return mixed
     */
	public function singlePush($alias, $title, $msg, $data) {
		$response = $this->jpush->push()->setPlatform('all')->addAlias($alias);
		return $this->sendMessage($response, $title, $msg, $data);

		// return $this->getOptions($send);
	}

	/**
	 * 批量推送消息
	 * @param $tag string 标签 (P1102,P1202...)
	 * @param $title string 消息标题
	 * @param $msg string 消息内容
	 * @param $data string 消息类型 01 02 03 04 (消息,公告,任务,分润账单)
     * @return mixed
	 */
	public function batchPush($tag, $title, $msg, $data) {
		$data['title'] = $title;
		$response = $this->jpush->push()->setPlatform('all')->addTag($tag);
		return $this->sendMessage($response, $title, $msg, $data);
	}

	/**
	 * 消息广播
	 */
	public function allJPushMsg($title, $msg, $apptype, $data) {
		$data['title'] = $title;

		$response = $this->jpush->push()->setPlatform($apptype)->addTag('all');

		if ($apptype == 'all') {
			return $this->sendMessage($response, $title, $msg, $data);
		}

		$actionName = $apptype . 'Notification';

		$send = $this->$actionName($response, $title, $msg, $data);

		return $this->getOptions($send);
	}
	// return $this->jpush->report()->getReceived(['27021598289641033,27021598289180750,27021598289180751']);

	/**
	 * ios 通知
	 */
	public function iosNotification($clien, $title, $msg, $data) {
		try {
			return $clien->iosNotification(
			    [
                    "title" => $title,
                    "body" => $msg
                ],[
                    'alert' => $title,
                    'sound' => 'sound.caf',
                    'badge' => '+1',
                    'content-available' => true, //表示推送唤醒
                    'extras' => $data
                ]);
		} catch (\Throwable $e) {
			Log::info('消息推送失败');
			Err('消息推送失败');
		}

	}

	/**
	 * android 通知
	 */
	public function androidNotification($clien, $title, $msg, $data) {
		try {
			return $clien->androidNotification(
			    [
                    "title" => $title,
                    "body" => $msg
                ], [
                    'alert' => $title,
                    'extras' => $data,
                ]);
		} catch (\Throwable $e) {
			Log::info('消息推送失败');
			Err('消息推送失败');
		}
	}

    /**
     * @param $clien
     * @return mixed
     */
	public function getOptions($clien) {
		return $clien->options(array(
			'sendno' => time(), //推送序号
			'apns_production' => False, //True 生产环境，False 开发环境
			'time_to_live' => 86400, //离线消息保留时长(秒)
			// 'big_push_duration' => 1    //定速推送时长(分钟)，又名缓慢推送
		))->send();
	}

	//消息发送
	public function sendMessage($clien, $title, $msg, $data) {
		try {
			$ios = $this->iosNotification($clien, $title, $msg, $data);
			$send = $this->androidNotification($ios, $title, $msg, $data);
			return $this->getOptions($send);
		} catch (\Throwable $e) {
			Log::info('消息推送失败');
			Err('消息推送失败');
		}

	}
}