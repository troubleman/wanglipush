<?php
namespace WangliPush\Gateway;

use WangliPush\Message\Message;

abstract class Gateway
{
    /**
     * 接口响应结果
     * @var
     */
    protected $resultNo;

    //发送短信
    abstract public function send(Message $message);

    /**
     * 获取发送方式名称
     * @return string
     */
    public function getName()
    {
        return $this->device;
    }

    /**
     * 获取接口响应信息
     * @return mixed
     */
    public function getResultNo()
    {
        return $this->resultNo;
    }
}
