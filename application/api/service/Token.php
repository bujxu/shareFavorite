<?php 

namespace app\api\service;

class Token
{
    public static function generateToken()
    {
        $randChars = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token_salt');
        
        return md5($randChars.$timestamp.$salt);
    }


    public static function getCurrentTokenVar($key)
    {
        $token = \think\Request::instance()->header('token');
        $vars = \think\Cache::get($token);

        if (!$vars)
        {
            throw new \app\lib\exception\TokenException;
        }
        else
        {
            if (!is_array($vars))
            {
                $vars = json_decode($vars, true);
            }

            if (array_key_exists($key, $vars))
            {
                return $vars[$key];
            }
            else
            {
                throw new \think\Exception('token 变量不存在');
            }
        }
    }

    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }
}