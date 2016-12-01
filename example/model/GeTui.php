<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use getuisdk\IOSGeTui;
use getuisdk\IGtSingleMessage;
use getuisdk\IGtAppMessage;
use getuisdk\IGtTarget;
use getuisdk\IGtTransmissionTemplate;
use getuisdk\RequestException;
use getuisdk\IGtNotificationTemplate;
use getuisdk\DictionaryAlertMsg;
use getuisdk\IGeTui;
use getuisdk\IGtAPNPayload;


class GeTui extends Model
{
    //正式的
   protected $getuiAppid='{你的 APP ID}';
   protected $getuiAppkey='{你的 APP KEY}';
   protected $getuiMasterSecret='{你的 MASTER SECRET}';

   protected $getuiHost='http://sdk.open.api.igexin.com/apiex.htm';
   protected $getuiCid='';
   protected $getuiDeviceToken='';
   protected $getuiAlias='';

    //通知透传功能
    public function IGtNotificationTemplateDemo ($content,$title,$text,$logoUrl,$clientId)
    {
        $igt = new IGeTui($this->getuiHost, $this->getuiAppkey, $this->getuiMasterSecret);
        //消息模版：
        $template = new IGtNotificationTemplate();
        $template->setAppId($this->getuiAppid);//应用appid
        $template->setAppKey($this->getuiAppkey);//应用appkey
        $template->setTransmissionType(2);//透传消息类型
        $template->setTransmissionContent($content);//透传内容
        $template->setTitle($title);//通知栏标题
        $template->setText($text);//通知栏内容
        $template->setLogo($logoUrl);//通知栏logo
        $template->setIsRing(true);//是否响铃
        $template->setIsVibrate(true);//是否震动
        $template->setIsClearable(true);//通知栏是否可清除


                //个推信息体
                $message = new IGtSingleMessage();
                $message->setIsOffline(true);//是否离线
                $message->setOfflineExpireTime(3600 * 12 * 1000);//离线时间
                $message-etData($template);//设置推送消息类型


                        //接收方
                        $target = new IGtTarget();
                        $target->setAppId($this->getuiAppid);
                        //$target->setClientId('50ea0fa4c556d1fd4a79243c776ad722');
                        $target->setClientId($clientId);
                        try {
                            $rep = $igt->pushMessageToSingle($message, $target);
                            return true;
                        } catch (RequestException $e) {
                            $requstId = $e->getRequestId();
                            $rep = $igt->pushMessageToSingle($message, $target, $requstId);
                            return false;
                        }

    }


    //透传功能
    public function IGtTransmissionTemplateDemo($app,$content,$clientId,$titleLocKey,$locKey,$actionLockey)
    {
        $igt = new IGeTui($getuiHost, $getuiAppkey, $getuiMasterSecret);
        //消息模版：
        $template = new IGtTransmissionTemplate();
        $template->setAppId($getuiAppid);//应用appid
        $template->setAppKey($getuiAppkey);//应用appkey
        $template->setTransmissionType(2);//透传消息类型
        $template->setTransmissionContent($content);//透传内容
        //APN高级推送
        $apn = new IGtAPNPayload();
        $alertmsg = new DictionaryAlertMsg();
        $alertmsg->body = $locKey;
        $alertmsg->actionLocKey = $actionLockey;
        $alertmsg->locKey = $locKey;
        $alertmsg->locArgs = array('locargs');
        $alertmsg->launchImage = 'launchimage';
//        IOS8.2 支持
        $alertmsg->title = $titleLocKey;
        $alertmsg->titleLocKey = $titleLocKey;
        $alertmsg->titleLocArgs = array('TitleLocArg');
        $apn->alertMsg = $alertmsg;
        $apn->badge = 1;
        $apn->sound = '';
        $apn->addCustomMsg('payload', 'payload');
        $apn->contentAvailable = 1;
        $apn->category = 'ACTIONABLE';
        $template->setApnInfo($apn);


        //基于应用消息体
//        $message = new IGtAppMessage();
//        $message->set_isOffline(true);
//        $message->set_offlineExpireTime(3600*12*1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
//        $message->set_data($template);
//        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
//        $message->set_speed(100);// 设置群推接口的推送速度，单位为条/秒，例如填写100，则为100条/秒。仅对指定应用群推接口有效。
//        $message->set_appIdList(array(APPID));
//        $message->set_phoneTypeList(array('ANDROID','IOS'));
//        $message->set_provinceList();
//        $message->set_tagList();
//        $message = new IGtSingleMessage();
//        $message->setIsOffline(true);//是否离线
//        $message->setOfflineExpireTime(3600 * 12 * 1000);//离线时间
//        $message->setData($template);//设置推送消息类型
        //群推信息
//        try {
//            $rep = $igt->pushMessageToApp($message);
//            return true;
//        } catch (RequestException $e) {
//            $requstId = $e->getRequestId();
//            $rep = $igt->pushMessageToApp($message);
//            return false;
//        }
//
//        //个推信息体
        $message = new IGtSingleMessage();
        $message->setIsOffline(true);//是否离线
        $message->setOfflineExpireTime(3600 * 12 * 1000);//离线时间
        $message->setData($template);//设置推送消息类型
//
//        //接收方
        $target = new IGtTarget();
        $target->setAppId($getuiAppid);
        $target->setClientId($clientId);
        try {
            $rep = $igt->pushMessageToSingle($message, $target);
            return true;
        } catch (RequestException $e) {
            $requstId = $e->getRequestId();
            $rep = $igt->pushMessageToSingle($message, $target, $requstId);
            return false;
        }

    }



}
