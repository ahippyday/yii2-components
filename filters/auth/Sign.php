<?php

namespace ixia\components\filters\auth;

use Yii;
use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;


class Sign extends AuthMethod
{
    public $signParam = 'sign';

    public function authenticate($user, $request, $response)
    {
        $params = $request->get();
        $sign = $this->getSign($params);

        return $sign == $request->get($this->signParam, '') ? true : null;
    }

    protected function getSign($params)
    {
        unset($params[$this->signParam]);

        ksort($params);

        $str = '';
        foreach($params as $key => $value) {
            $str .= "{$key}=$value";
        }

        $appKey = Yii::$app->params['app.key'];
        $str = $str.$appKey;
        return sha1($str);
    }

    public function handleFailure($response)
    {
        throw new UnauthorizedHttpException('无效的 Sign [20001]');
    }

}