## SmsClient

### 安装

```
# 暂支持 阿里云 和 腾讯云 发送短信
# 使用 阿里云 时请安装
$ composer require alibabacloud/client seffeng/sms

# 使用 腾讯云 时请安装
$ composer require qcloudsms/qcloudsms_php seffeng/sms
```

### 目录说明

```
│ SmsClient.php
├─Clients
│      AliyunClient.php
│      QcloudClient.php
└─Exceptions
       SmsException.php
```

### 示例

```php
use Seffeng\Sms\SmsClient;
use Seffeng\Sms\Exceptions\SmsException;

class SiteController extends Controller
{
    public function index()
    {
        try {
            $appId = '';            // 腾讯云SDK AppID 或 阿里云 accessKeyId
            $appSecret = '';        // 腾讯云SDK AppKey 或 阿里云 AccessKeySecret
            $name = 'qcloud';       // qcloud 或 aliyun
            $signName = '签名';     // 签名内容
            $tempCode = '1234|SMS_153055065';   // 腾讯云 templateId 或 阿里云 TemplateCode
            $content = ['111111'];              // 腾讯云 ['111111'] 或 阿里云 ['code' => '111111']

            $client = new SmsClient($appId, $appSecret);
            $result = $client->setClient($name)
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

### 备注

1、依赖阿里云（alibabacloud/client）和腾讯云SDK（qcloudsms/qcloudsms_php）；

2、阿里云  AccessKeyId 和  AccessKeySecret 为[子账号 AccessKey](https://help.aliyun.com/document_detail/53045.html) 。