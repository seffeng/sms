## SmsClient

### 安装

```
# 暂时支持 阿里云 和 腾讯云 发送短信
$ composer require seffeng/sms
```

### 目录说明

```
│ SmsClient.php                 基类
├─Clients
│  ├─Aliyun                     阿里云
│  │      Client.php                阿里云发送处理类
│  │      Error.php                 阿里云错误
│  │      TemplateParams.php        短信内容参数对应
│  └─Qcloud                     腾讯云
│          Client.php               腾讯云发送处理类
│          Error.php                腾讯云错误
├─Exceptions
│      SmsException.php             异常基类
└─Helpers
       ArrayHelper.php              数组帮助类
```

### 示例

```php
/**
 * SiteController
 */
use Seffeng\Sms\SmsClient;
use Seffeng\Sms\Exceptions\SmsException;

class SiteController extends Controller
{
    public function index()
    {
        try {
            $sdkAppId = '';             // 腾讯云SDKAppID  阿里云不需要
            $appSecretId = '';          // 腾讯云SecretId 或 阿里云 AccessKeyId
            $appSecretKey = '';         // 腾讯云SecretKey 或 阿里云 AccessKeySecret
            $name = 'aliyun';           // qcloud 或 aliyun
            $signName = '签名';         // 签名内容
            $tempCode = 'SMS_153055065'; // 腾讯云 templateId[1234] 或 阿里云 TemplateCode[SMS_153055065]
            $content = ['111111'];      // 腾讯云 ['111111'] 或 阿里云 ['code' => '111111']

            // 因阿里云与腾讯云的内容参数结构不一致，参考 $content；可通过 TemplateParams 实现以腾讯云结构发送
            $stdTemplateParams = new TemplateParams();
            $client = new SmsClient($appSecretId, $appSecretKey, $sdkAppId);
            $result = $client->setClient($name, $stdTemplateParams)
                        ->setSignName($signName)
                        ->setTemplateCode($tempCode)
                        ->send('13800138000', $content);

            if ($result) {
                echo '发送成功！';
            } else {
                echo '发送失败！';
            }
        } catch (SmsException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
```

```php
/**
 * TemplateParams
 * @see Seffeng\Sms\Clients\Aliyun\TemplateParams
 */
class TemplateParams extends \Seffeng\Sms\Clients\Aliyun\TemplateParams
{
    /**
     * 重写模板对应参数
     * @return array
     */
    public static function fetchNameItems()
    {
        return [
            'SMS_153055065' => ['code'],
            'SMS_153055066' => ['code', 'address'],
        ];
    }
}
```

### 备注

1、使用阿里云发送短信时 $sdkAppId 无效（不需要此参数）；

2、使用阿里云发送短信时若 $content 结构是阿里云格式时 $stdTemplateParams 无效（不需要此参数）；

3、阿里云  AccessKeyId 和  AccessKeySecret 为[子账号 AccessKey](https://help.aliyun.com/document_detail/53045.html) ；

4、本地 http 请求错误：(cURL error 60: SSL certificate problem: unable to get local issuer certificate.)。

4.1 下载 cacert.pem (https://curl.haxx.se/docs/caextract.html)；
4.2 修改 php.ini 修改 curl.cainfo 文件路径（绝对路径）：

```
[curl]
curl.cainfo = "/cacert.pem"
```

