<?php
namespace app\components;

/**
 * Class HttpException representing HTTP exceptions
 * @package app\components
 */
abstract class HttpException extends \Exception
{
    public $code = 500;
}