<?php
namespace app\exceptions;

use app\components\HttpException;

class BadRequest extends HttpException
{
    public $code = 400;
}