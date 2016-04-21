<?php
namespace WangliPush\Gateway;

use Requests;
use WangliPush\Message\Message;
use WangliPush\Message\SMSMessage;

class YesGateway extends Gateway
{
    /**
     * 云信短信接口地址
     */
    const SMS_URL = 'http://yes.itissm.com/api/MsgSend.asmx/sendMes';

    /**
     * 设备接受方式
     */
    protected $device = 'sms-yes';

    /**
     * 用户名
     *
     * @var
     */
    private $userCode;

    /**
     * 密码
     *
     * @var
     */
    private $userPass;

    /**
     * 通道号
     *
     * @var
     */
    private $channel;

    public function __construct($userCode, $password, $channel = 0)
    {

        $this->userCode = $userCode;

        $this->userPass = $password;

        $this->channel = $channel;
    }

    /**
     * 设置默认请求参数
     *
     * @return array
     */
    public function setDefaultOpt()
    {
        return array(
            'userCode' => $this->userCode,
            'userPass' => $this->userPass,
            'Channel' => $this->channel
        );
    }

    /**
     * 发送短信
     *
     * @param  Message $message
     *
     * @return string
     */
    public function send(Message $message)
    {

        $options = $this->setDefaultOpt();

        //获取接受人
        $receiverArr = $message->getReceiver();

        $options['DesNo'] = implode(',', $receiverArr);

        $options['Msg'] = $message->getMessage(false);

        $response = Requests::post(self::SMS_URL, array(), $options, array('timeout'=>10));

        $resNo = intval(strip_tags($response->body));
        $result = ($resNo < 0) ? 'fail' : 'success';

        return $this->buildSendRecords($receiverArr, $result, $message->getMessage(false));
    }

}