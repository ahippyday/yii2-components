<?php

namespace ixia\components\filters\auth;

use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;


class Token extends AuthMethod
{

    public $tokenParam = 'access_token';

    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->getBodyParam($this->tokenParam);
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }

    public function handleFailure($response)
    {
        throw new UnauthorizedHttpException('无效的 AccessToken');
    }
}