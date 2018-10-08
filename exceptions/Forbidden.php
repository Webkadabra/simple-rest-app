<?php
namespace app\exceptions;

use app\components\HttpException;

class NotAuthorized extends HttpException
{
    public $httpCode = 401;
}