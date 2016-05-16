<?php

namespace WangliPush\Message;

class JpushMessage extends Message
{

    /**
     * 每次最多发送数量
     */
    protected $maxSendSize = '1000';

    /**
     * 接受人列表
     */
    protected $listType = 'aliasList';

    /**
     * 落脚点
     * @var string
     */
    protected $viewPos;

    /**
     * 落脚点
     *
     * @var array
     */
    static $EXTRAS_OPEN_PAGE = array(
        '1' => 'home',
        '2' => 'p2plist',
        '3' => 'discovery',
        '4' => 'expgold'
    );

    /**
     * 获取极光推送内容
     *
     * @param bool $transcode
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 设置极光推送落脚点
     * @param $viewPos
     */
    public function setOpenPage($viewPos)
    {
        $this->viewPos = $viewPos;
    }
    /**
     * 获取极光推送落脚点
     */
    public function getOpenPage()
    {
        return static::$EXTRAS_OPEN_PAGE[$this->viewPos];
    }
}