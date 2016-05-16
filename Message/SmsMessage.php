<?php

namespace WangliPush\Message;

class SmsMessage extends Message
{
    /**
     * 每次最多发送数量
     */
    protected $maxSendSize = '100';

    /**
     * 接受人列表
     */
    protected $listType = 'numberList';

    /**
     * 获取短信内容
     *
     * @param bool $utf8
     *
     * @return string
     */
    public function getMessage($utf8 = true)
    {
        return $utf8 ? $this->message : iconv("UTF-8", "GB2312", $this->message);
    }

}