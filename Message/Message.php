<?php
namespace WangliPush\Message;

use WangliPush\Receiver;

abstract class Message
{
    /**
     * 接收端
     *
     * @var Receiver
     */
    protected $receiver;

    /**
     * 待发送短信
     *
     * @var string
     */
    protected $message;

    /**
     * 节点信息
     *
     * @var array
     */
    protected $rule_info;


    /**
     * 获取短信内容
     *
     * @param bool $transcode
     *
     * @return string
     */
    abstract public function getMessage();


    public function __construct(Receiver $receiver, $message, $rule_info = array())
    {

        $this->receiver = $receiver;

        $this->message = $message;

        $this->rule_info = $rule_info;

    }

    /**
     * 获取要接受短信的手机号
     *
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver->getNumber($this->listType, $this->maxSendSize);
    }

    /**
     * 是否已经发送所有用户
     *
     * @return bool
     */
    public function isSendOver()
    {
        return $this->receiver->isReachBottom($this->listType);
    }

}