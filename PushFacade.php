<?php
/**
 * Author     : newiep
 * CreateTime : 11:51
 * Description: 推送门面
 */
namespace WangliPush;

use InvalidArgumentException;

class PushFacade
{
    /**
     * 发送渠道对象
     *
     * @var
     */
    protected $gateway;

    /**
     * 消息对象
     *
     * @var
     */
    protected $message;

    public function __construct($type, $receiver, $content)
    {
        //__call 初始化渠道对象和消息对象
        if(empty($content))
        {
            throw new InvalidArgumentException('message content is empty');
        }

        $this->$type(new Receiver($receiver), $content);
    }

    /**
     * 消息发送
     * @return mixed
     */
    public function send()
    {
        return $this->gateway->send($this->message);
    }

    /**
     * 收件人列表是否全部发送
     */
    public function isSendOver()
    {
        return $this->message->isSendOver();
    }

    /**
     * 获取当前批次收件人列表
     * @return array
     */
    public function getBatchReceivers()
    {
        return $this->message->getBatchReceivers();
    }

    /**
     * 获取发送内容
     * @return string
     */
    public function getMessage()
    {
        return $this->message->getMessage();
    }

    /**
     * 获取发送结果
     * @return mixed
     */
    public function getResultNo()
    {
        return $this->gateway->getResultNo();
    }

    /**
     * 获取渠道名称
     * @return mixed
     */
    public function getDevice()
    {
        return $this->gateway->getName();
    }

    /**
     * 初始化渠道对象和消息对象
     * @param $name sms|jpush
     * @param $arguments [Receiver , $content]
     */
    public function __call($name, $arguments)
    {
        $method = "make".ucfirst(strtolower($name));

        if (method_exists($this, $method)) {
            $this->{$method}($arguments);
        }else{
            throw new InvalidArgumentException("Channel [{$channel}] is not supported.");
        }
    }

    /**
     * 读取推送渠道配置
     * @param $name
     *
     * @return $this
     */
    protected function getConfig($name)
    {
        return app('config')->get("gateways.stores.{$name}");
    }

    /**
     * 初始化短信通道（gateway）和短信消息（message）对象
     * @param array $arguments
     *
     * @return void
     */
    protected function makeSms(Array $arguments)
    {
        $channel = app('config')->get("gateways.sms_channel");
        $config = $this->getConfig($channel);

        $channelMethod = 'create' . ucfirst($channel) . 'Channel';

        if (method_exists($this, $channelMethod)) {
            $this->gateway = $this->{$channelMethod}($config);
        } else {
            throw new InvalidArgumentException("Channel [{$channel}] is not supported.");
        }

        $reflect = new \ReflectionClass('WangliPush\Message\SmsMessage');
        $this->message = $reflect->newInstanceArgs($arguments);
    }

    /**
     * 初始化极光通道（gateway）和极光消息（message）对象
     * @param array $arguments
     *
     * @return void
     */
    protected function makeJpush(Array $arguments)
    {
        $config = $this->getConfig('jpush');
        $reflect = new \ReflectionClass('WangliPush\Gateway\JPushGateway');
        $this->gateway = $reflect->newInstanceArgs($config);


        $reflect = new \ReflectionClass('WangliPush\Message\JpushMessage');
        $this->message = $reflect->newInstanceArgs($arguments);
    }

    //QxtGateway 对象
    protected function createQxtChannel($config)
    {
        $reflect = new \ReflectionClass('WangliPush\Gateway\QxtGateway');
        return $reflect->newInstanceArgs($config);
    }

    //YesGateway 对象
    protected function createYesChannel($config)
    {
        $reflect = new \ReflectionClass('WangliPush\Gateway\YesGateway');
        return $reflect->newInstanceArgs($config);
    }
}