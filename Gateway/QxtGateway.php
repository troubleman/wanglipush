<?php
namespace WangliPush\Gateway;

use Requests;
use WangliPush\Message\Message;
use WangliPush\Message\SMSMessage;

class QxtGateway extends Gateway
{
    /**
     * 短信接口地址
     */
    const SMS_URL = 'http://c.kf10000.com/sdk/SMS';

    /**
     * 接口调用方式
     */
    const CMD = 'send';

    /**
     * 设备接受方式
     */
    protected $device = 'sms-qxt';

    /**
     * 用户名
     * @var
     */
    private $userId;

    /**
     * 密码
     * @var
     */
    private $password;

    public function __construct($userId, $password){

        $this->userId = $userId;

        $this->password = md5($password);

    }

    /**
     * 设置默认请求参数
     *
     * @return array
     */
    public function setDefaultOpt(){
        return array(
                'uid'  => $this->userId,
                'psw'  => $this->password,
            );
    }

    /**
     * 发送短信
     *
     * @param  Message $message
     *
     * @return string
     */
    public function send(Message $message){

        $options = $this->setDefaultOpt();

        $options['cmd'] = self::CMD;

        $options['msgid'] = $this->getMsgId();

        $receiverArr = $message->getReceiver();
        $options['mobiles'] = implode(',', $receiverArr);

        $options['msg'] = $message->getMessage();

        $response = Requests::post(self::SMS_URL, array(), $options);

        $result = ($response->body == 100) ? 'success' : 'fail';


        return $this->buildSendRecords($receiverArr, $result, $message->getMessage(false));
    }

    /**
     * 生成随机字符串
     *
     * @return string
     */
    private function getMsgId()
    {

        return substr(uniqid(),-6);
    }
}