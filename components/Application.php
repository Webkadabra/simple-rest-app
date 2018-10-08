<?php
namespace app\components;

use app\exceptions\NotAuthorized;
use app\exceptions\NotFound;
use app\structures\Config;

/**
 * Simple REST application engine with dynamic controllers support, JSON response and access token validation.
 *
 * To run an application, create an instance and pass configuration as a first parameter:
 * ```
 *  \app\components\Application::instance($config)->run();
 * ```
 *
 * @package app\components
 * @author Sergii Gamaiunov
 */
class Application
{
    /**
     * @var Application|null instance of an application
     */
    private static $instance = null;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var \PDO
     */
    public $db;

    public $controller;

    /**
     * @var string access token, passed as `authToken` parameter via `_GET`
     */
    public $token;

    /**
     * Application constructor.
     * @param $config array app configuration
     * @see \app\structures\Config for configuration data structure
     */
    public function __construct($config)
    {
        $this->config = new Config();
        static::configure($this->config, $config);
    }

    /**
     * @param array $config
     * @return Application|null
     */
    public static function instance($config = [])
    {
        if (self::$instance == null) {
            self::$instance = new Application($config);
        }
        return self::$instance;
    }

    /**
     * Helper method to configure an object
     * @param $object
     * @param $properties
     * @return mixed
     */
    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }
        return $object;
    }

    /**
     * Initiates an application and creates all necessary components etc.
     */
    protected function init()
    {
        $this->db = new \PDO($this->config->dbDsn, $this->config->dbUsername, $this->config->dbPassword);
    }

    /**
     * @param array $config
     */
    public function run()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $this->init();
        try {
            $this->requireAuthToken();
            $this->runController();
        } catch (HttpException $e) {
            http_response_code($e->code);
            echo json_encode([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        }
    }

    /**
     * Simple auth validation: check if `authToken` value is is present in `_GET` and is valid
     * @throws NotAuthorized
     */
    public function requireAuthToken()
    {
        $this->token = $_GET['authToken'] ?? null;
        if (!$this->token) {
            throw new NotAuthorized();
        }
        if (!in_array($this->token, $this->config->accessTokens)) {
            throw new NotAuthorized();
        }
    }

    /**
     * Run controller, based on requested URI. Controllers must be located in `controllers` folder under app root,
     * must be in `\app\controllers` namespace; controller's name must have "Controller" suffix.
     * Example: /book/ => `BookController`
     * @throws NotFound
     */
    protected function runController()
    {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
        $path = trim($parsedUrl['path'], '/');
        $pathRoute = explode('/', $path);
        if (!$pathRoute) {
            throw new NotFound();
        }
        $controller = $pathRoute[0] ?? null;  // PHP 7
        if (!preg_match("/[A-Za-z0-9]/", $controller)) { // make sure there ain't no funny business going on
            throw new NotFound();
        }
        $action = $pathRoute[1] ?? 'list'; // `list` is the default action
        $action_name = 'action'.ucfirst($action);
        $controller_name = ucfirst($controller).'Controller';
        $controller_path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controller_name . '.php';
        $controller_class_name = '\app\controllers\\'.$controller_name;
        if (!file_exists( $controller_path )) {
            throw new NotFound();
        }
        if (!class_exists( $controller_class_name )) {
            throw new NotFound();
        }
        $this->controller = new $controller_class_name();
        if (!method_exists($this->controller, $action_name)) {
            throw new NotFound();
        }
        $response = $this->controller->$action_name();
        if (is_array($response) || is_object($response)) {
            echo json_encode($response);
        }
    }
}