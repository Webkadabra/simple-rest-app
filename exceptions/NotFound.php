<?php
namespace app\exceptions;


use app\components\HttpException;

class NotFound extends HttpException
{
    public $code = 404;
    public $message = 'Page does not exist';
}