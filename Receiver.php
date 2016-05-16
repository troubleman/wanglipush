<?php
namespace WangliPush;

use WangliPush\Exception\Exception;

class Receiver
{
    /**
     * 手机号
     */
    const USER_PHONE = 'phone';

    /**
     * user_id(用于极光推送alias数据)
     */
    const ALIAS = 'alias';

    /**
     * 待发送所有手机号
     *
     * @var array
     */
    protected $numberList = array();

    /**
     * 待发送的所有用户id
     *
     * @var array
     */
    protected $aliasList = array();

    /**
     * Receiver constructor.
     *
     * @param array $userList
     */
    public function __construct(Array $userList)
    {
        if (!is_array($userList)) {
            throw new \InvalidArgumentException('userList must be of the type array');
        }
        $this->setNumber($userList);
    }

    /**
     * 设置接收人
     *
     * @param $userList
     *
     * @return void
     */
    public function setNumber($userList)
    {
        foreach ($userList as $type => $list) {
            if ($type === self::USER_PHONE) {
                $this->numberList = array_merge($this->numberList, $list);
            }

            if ($type === self::ALIAS) {
                $this->aliasList = array_merge($this->aliasList, $list);
            }
        }
    }

    /**
     * 获取要接受短信的手机号
     *
     * @param string $list 读取那个receiverList
     * @param int $size 每次获取个数
     *
     * @return mixed
     */
    public function getNumber($list, $size = 1)
    {
        return array_splice($this->$list, 0, $size);
    }


    /**
     * 是否所有用户都已发送
     *
     * @return bool
     */
    public function isReachBottom($list)
    {
        return empty($this->$list);
    }

}