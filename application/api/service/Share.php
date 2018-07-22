<?php 

namespace app\api\service;
use \app\api\service\Token as TokenService;
use \app\lib\share\WXBizDataCrypt;

class Share
{
    public static function get($encryptedData, $iv)
    {
        $sessionKey = TokenService::getCurrentTokenVar('session_key');
        $pc = new WXBizDataCrypt(config('wx.app_id'), $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode == 0) 
        {
            return $data;
        } 
        else 
        {
            return $errCode;
        }
    }
}