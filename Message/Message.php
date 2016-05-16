<?php
namespace WangliPush\Message;

use WangliPush\Receiver;

abstract class Message
{
    /**
     * 接受人对象
     *
     * @var Receiver
     */
    protected $receiver;

    /**
     * 待发送内容
     *
     * @var string
     */
    protected $message;

    /**
     * 某批收件人
     *
     * @var array
     */
    protected $batchReceivers;

    /**
     * 获取短信内容
     */
    abstract public function getMessage();


    public function __construct(Receiver $receiver, $message)
    {
        $this->receiver = $receiver;

        $this->message = $message;
    }

    /**
     * 获取要接受短信的手机号（并保存到batchReceivers）
     *
     * @return string
     */
    public function getReceiver()
    {
        $this->batchReceivers = $this->receiver->getNumber($this->listType, $this->maxSendSize);

        if(empty($this->batchReceivers)){
            throw new \LogicException('receiver list if empty, misson complete');
        }

        return $this->batchReceivers;
    }

    /**
     * 获取当前批次收件人
     *
     * @return array
     */
    public function getBatchReceivers()
    {
        return $this->batchReceivers;
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