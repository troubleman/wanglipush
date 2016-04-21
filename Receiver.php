<?php
namespace WangliPush;

use WangliPush\Exception\Exception;

class Receiver
{
    /**
     * 主键id
     */
    const USER_ID = 'id';

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
     * @param $id
     * @param $type id的类型
     * @param $userModel 用户信息模型
     *
     * @throws Exception
     */
    public function __construct($id, $type, $userModel)
    {
        //处理用户id
        if (preg_match('/[^\d,]/', $id)) {
            throw new Exception('param user_id is illegal');
        }else{
            $id = explode(',', $id);
        }

        switch ($type) {
            case self::USER_ID:

                $receiverList = $userModel->getReceiverNumber($id);

                $this->numberList = $receiverList['phone'];
                $this->aliasList = $receiverList['user_id'];
                break;

            case self::USER_PHONE:
                $this->numberList = $id;
                break;

            case self::ALIAS:
                $this->aliasList = $id;
                break;
            default:
                throw new Exception('user type not exists', 4403);
                break;
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