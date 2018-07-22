<?php 

namespace app\api\validate;

class GetOpenGId extends BaseValidate
{
    protected $rule = [
        'encryptedData' => 'require|isNotEmpty',
        'iv' => 'require|isNotEmpty',
    ];

    protected $message = [
        'code' => '获取openGID的参数不对'
    ];
}