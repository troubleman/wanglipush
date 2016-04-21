<?php
namespace WangliPush\Exception;

use \Exception as BaseException;

class Exception extends BaseException
{
    /**
     * all errors of push
     *
     * @var array
     */
    protected $errors = array(
        '4001' => 'illegal user',
        '4002' => 'fail authority',
        '4101' => 'params rule_id required',
        '4102' => 'params nonstr required',
        '4103' => 'params sign required',
        '4104' => 'params data required',
        '4105' => 'params user required',
        '4201' => 'request expired',
        '4301' => 'template params number not match',
        '4302' => 'template params not match',
        '4401' => 'rule not exists',
        '4402' => 'gateway not exists',
        '4403' => 'user type not exists',
    );

    public function __construct($message, $code = -1)
    {
        $message = empty($this->errors[$code]) ? $message : $this->errors[$code];

        parent::__construct($message, $code);
    }
}