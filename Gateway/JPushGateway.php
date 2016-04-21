<?php
namespace WangliPush\Gateway;

use Requests;
use WangliPush\Exception\Exception;
use WangliPush\Message\Message;

class JPushGateway extends Gateway
{
    /**
     * 接口地址
     */
    const API_PUSH = 'https://api.jpush.cn/v3/push';

    //const API_PUSH_VALIDATE = 'https://api.jpush.cn/v3/push/validate';

    /**
     * 设备接受方式
     */
    protected $device = 'jpush';

    /**
     * 应用key
     *
     * @var
     */
    private $app_key;

    /**
     * 应用密码
     *
     * @var
     */
    private $app_secret;

    /**
     * 接口请求头
     *
     * @var
     */
    protected $header;


    public function __construct($app_key, $app_secret)
    {

        $this->app_key = $app_key;

        $this->app_secret = $app_secret;

        $this->setDefaultHeader();

    }

    public function setHeader($header = array())
    {
        array_push($this->header, $header);
    }

    //发送推送
    public function send(Message $message)
    {

        $options = array(
            'auth' => array($this->app_key, $this->app_secret)
        );

        $postData = $this->setDefaultData();

        $receiverArr = $message->getReceiver();
        $postData['audience']['alias'] = $receiverArr;

        $postData['notification']['alert'] = $message->getMessage();
        $postData['notification']['ios']['extras']['openPage'] = $message->getOpenPage();
        $postData['notification']['android']['extras']['openPage'] = $message->getOpenPage();

        $response = Requests::post(self::API_PUSH, $this->header, json_encode($postData), $options);
        $response = json_decode($response->body);
        $result = isset($response->error) ? 'fail' : 'success';

        return $this->buildSendRecords($receiverArr, $result, $message->getMessage());
    }

    /**
     * 设置默认header
     */
    private function setDefaultHeader()
    {
        $this->header = array(
            'Charset' => 'UTF-8',
            'Content-Type' => 'application/json',
            'Connection' => 'Keep-Alive',
        );
    }

    /**
     * 设置默认请求内容
     *
     * @return array
     */
    private function setDefaultData()
    {
        return array(
            'platform' => "all",
            'notification' => array('ios' => array('badge' => '+1')),
            'options' => array('apns_production' => true)
        );
    }
}