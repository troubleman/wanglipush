<?php
namespace WangliPush\Gateway;

use WangliPush\Message\Message;

abstract class Gateway
{   

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
     * 构造发送记录数据
     * @param $receiverList
     * @param $result
     * @param $content
     *
     * @return array
     */
    protected function buildSendRecords($receiverList, $result, $content)
    {
        return array_map(function($receiver) use ($result, $content) {
            return array(
                'devices' => $this->getName(),
                'receiver'=> $receiver,
                'content' => $content,
                'result'  => $result
            );

        }, $receiverList);
    }
}
