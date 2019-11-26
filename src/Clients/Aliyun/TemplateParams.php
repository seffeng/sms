<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Sms\Clients\Aliyun;

use Seffeng\Sms\Helpers\ArrayHelper;

class TemplateParams
{
    /**
     *
     * @var string
     */
    private $templateCode;

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     */
    public function __construct()
    {
        //
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     */
    public function setTemplateCode(string $templateCode)
    {
        $this->templateCode = $templateCode;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getValue()
    {
        return $this->templateCode;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return array|string
     */
    public function getName()
    {
        return ArrayHelper::getValue(static::fetchNameItems(), $this->getValue(), []);
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return array
     */
    public static function fetchNameItems()
    {
        return [
            'SMS_153055065' => ['code', 'address'],
        ];
    }
}