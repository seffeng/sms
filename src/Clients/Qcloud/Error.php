<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Sms\Clients\Qcloud;

use Seffeng\Sms\Helpers\ArrayHelper;

class Error
{
    /**
     *
     * @var string
     */
    private $errorCode;

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $errorCode
     */
    public function __construct(string $errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getValue()
    {
        return $this->errorCode;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return array|string
     */
    public function getName()
    {
        return ArrayHelper::getValue(self::fetchNameItems(), $this->getValue(), '');
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return array
     */
    public static function fetchItems()
    {
        return array_keys(self::fetchNameItems());
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
            'AuthFailure.SecretIdNotFound' => 'SecretId错误！',
            'AuthFailure.SignatureFailure' => '提供的凭据无法验证。请检查您的签名是否正确！',
            'FailedOperation' => '操作失败！',
            'FailedOperation.AlreadyRegistered' => '已经注册！',
            'FailedOperation.FailResolvePacket' => '请求包解析失败，通常情况下是由于没有遵守 API 接口说明规范导致的！',
            'FailedOperation.InsufficientBalanceInSmsPackage' => '套餐包余量不足！',
            'FailedOperation.JsonParseFail' => '解析请求包体时候失败！',
            'FailedOperation.MarketingSmsTimeConstraint' => '营销短信发送时间限制！',
            'FailedOperation.PhoneNumberOnBlacklist' => '手机号在黑名单库中，通常是用户退订或者命中运营商黑名单导致的！',
            'FailedOperation.SignatureIncorrectOrUnapproved' => '签名格式错误或者签名未审批！',
            'FailedOperation.SmsContainSensitiveWord' => '短信内容中含有敏感词！',
            'FailedOperation.TemplateIncorrectOrUnapproved' => '模版未审批或请求的内容与审核通过的模版内容不匹配！',
            'InternalError.NotHaveThisRestApiInterface' => '不存在该 RESTAPI 接口！',
            'InternalError.OtherError' => '其他错误！',
            'InternalError.RequestTimeException' => '请求发起时间不正常，通常是由于您的服务器时间与腾讯云服务器时间差异超过10分钟导致的！',
            'InternalError.SigFieldMissing' => '后端包体中请求包体没有 Sig 字段或 Sig 为空！',
            'InternalError.SigVerificationFail' => '后端校验 Sig 失败！',
            'InternalError.Timeout' => '请求下发短信超时！',
            'InternalError.UnknownError' => '未知错误类型！',
            'InvalidParameter' => '参数错误！',
            'InvalidParameter.SignIdNotFound' => 'SignId 不存在！',
            'InvalidParameter.VerificationCodeTemplateParameterFormatError' => '验证码模板参数格式错误！',
            'InvalidParameterValue.IncorrectPhoneNumber' => '手机号格式错误！',
            'InvalidParameterValue.LengthOfSmsContentExceedLimit' => '请求的短信内容太长！',
            'InvalidParameterValue.LengthOfTemplateParameterExceed' => '单个模板变量字符数超过12个！',
            'InvalidParameterValue.SdkAppidNotExist' => 'SdkAppid 不存在！',
            'InvalidParameterValue.VerificationCodeTemplateParameterFormatError' => '验证码模板参数格式错误！',
            'LimitExceeded.AmountOfDailyExceedLimit' => '业务短信日下发条数超过设定的上限！',
            'LimitExceeded.AmountOfOneHourExceedLimit' => '单个手机号1小时内下发短信条数超过设定的上限！',
            'LimitExceeded.AmountOfPhoneNumberSameContenetDailExceedLimit' => '单个手机号下发相同内容超过设定的上限！',
            'LimitExceeded.AmountOfThirtySecondExceedLimit' => '单个手机号30秒内下发短信条数超过设定的上限！',
            'LimitExceeded.CountOfPhoneNumberExceed' => '调用群发 API 接口单次提交的手机号个数超过200个！',
            'LimitExceeded.DeliveryFrequencyLimit' => '下发短信命中了频率限制策略！',
            'MissingParameter' => '缺少参数错误！',
            'RequestLimitExceeded.AmountOfOneHourExceedLimit' => '手机号码每天发送的短信数量超过上限！',
            'RequestLimitExceeded.AmountOfThirtySecondExceedLimit' => '手机号码发送的短信时间间隔太短！',
            'UnauthorizedOperation.IndividualUserSendMarketingSmsPermissionDeny' => '个人用户没有发营销短信的权限！',
            'UnauthorizedOperation.RequestIpNotOnWhitelist' => '请求 IP 不在白名单中！',
            'UnauthorizedOperation.RequestPermissionDeny' => '请求没有权限！',
            'UnauthorizedOperation.SdkAppidIsDisabled' => 'SdkAppid 已禁用！',
            'UnauthorizedOperation.SerivceSuspendByDueArrear' => '欠费被停止服务！',
            'UnauthorizedOperation.SmsSdkAppidVerifyFail' => 'SmsSdkAppid 校验失败！',
            'UnsupportedOperation.' => '不支持该请求！',
            'UnsupportedOperation.ContainDomesticAndInternationalPhoneNumber' => '群发请求里既有国内手机号也有国际手机号！',
            'UnsupportedOperation.UnsuportedRegion' => '不支持该地区短信下发！',
        ];
    }
}