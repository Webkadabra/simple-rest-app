<?php
namespace app\structures;

/**
 * Class Config representing data structure for application config
 * @package app\structures
 */
class Config
{
    /**
     * @var string connection dsn, e.g. `mysql:host=localhost;dbname=testapp;charset=utf8`
     */
    public $dbDsn;

    /**
     * @var string database username
     */
    public $dbUsername;

    /**
     * @var string database password (if any)
     */
    public $dbPassword;

    /**
     * @var array valid access tokens
     */
    public $accessTokens = [];
}