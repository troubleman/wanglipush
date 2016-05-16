# wanglipush
推送服务多渠道、易拓展SDK (For `laravel/lumen`)

### 当前支持渠道

* 企信通(短信默认渠道)
* 云信
* 极光推送

install
-------

	composer require "newiep/wanglipush"

config
------
配置目录`config/`下增加配置文件`gateways.php`,结构如下:

	return [

	    /**
	     * 针对短信有多个渠道接口，默认企信通
	     */
	    'sms_channel' => env('CHANNEL_TYPE', 'qxt'),

	    'stores' => [
	        /**
	         * 企信通接口账号配置
	         */
	        'qxt' => [
	            'key' => env('QXT_KEY', 'default key for qixintong'),
	            'secret' => env('QXT_SECRET', 'default secret for qixintong'),
	        ],

	        /**
	         * 云信接口账号配置
	         */
	        'yes' => [
	            'key' => env('YES_KEY', 'default key for yunxin'),
	            'secret' => env('YES_SECRET', 'default secret for yunxin'),
	            'channel' => env('YES_CHANNEL', '276'),
	        ],

	        /**
	         * 极光推送接口账号配置
	         */
	        'jpush' => [
	            'key' => env('JPUSH_KEY', 'default key for jpush'),
	            'secret' => env('JPUSH_SECRRT', 'default secret for jpush'),
	        ],
	    ],

	];
	
Usage Example
-------------

    //收件人列表，英文逗号分隔（发送短信）
    $receiver = array(
    	//短信接收人格式（'phone' => 'number list'）
    	'phone' => ['18801301379, 18801301379', '....'],

    	//极光接收人格式（'alias' => 'number list'）
    	'alias' => ['693023', '1115256, '....'],

    );

    //内容
    $content = '测试内容';
	
	//发送短信（默认使用企信通）
    $handle = new \WangliPush\PushFacade('sms', $receiver, $content);

    //发送极光推送
    $handle = new \WangliPush\PushFacade('jpush', $receiver, $content);

    $result = $handle->send(); // success or fail


### 使用其他渠道发送短信
	
	//使用云信
    app('config')["gateways.sms_channel"] = 'yes';

    $handle = new \WangliPush\PushFacade('sms', $receiver, $content);

    $handle->send();


其他可用接口
------------
是否收件人全部发送完成

	$handle->isSendOver();

获取当前批次收件人
	
	$handle->getBatchReceivers();

获取发送的消息内容

	$handle->getMessage();

获取接口响应结果

	$handle->getResultNo();