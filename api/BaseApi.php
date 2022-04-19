<?php

namespace api;

require_once 'config/Database.php';

use config\Database;
use RuntimeException;

abstract class BaseApi
{
    /**
     * @var string
     */
    public $apiName = '';

    /**
     * @var mixed|string
     */
    protected $method = ''; //GET|POST|PUT|DELETE

    /**
     * @var array|string[]
     */
    public $requestUri = [];
    /**
     * @var array|mixed
     */
    public $requestParams = [];

    /**
     * @var string
     */
    public $action = '';
    /**
     * @var \PDO|null
     */
    protected $db;


    /**
     *
     */
    public function __construct()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->requestUri = trim($this->requestUri, '/');
        $this->requestUri = explode('/', $this->requestUri);
        $this->requestParams = $_REQUEST;

        $this->method = $_SERVER['REQUEST_METHOD'];

        if ($this->method == 'PUT' || $this->method == 'POST') {
            $this->requestParams = json_decode(file_get_contents('php://input'), true);
        }

        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * @return mixed
     */
    public function run()
    {
        if (array_shift($this->requestUri) !== 'api' || array_shift($this->requestUri) !== $this->apiName) {
            throw new RuntimeException('API Not Found', 404);
        }
        $this->action = $this->getAction();

        if (method_exists($this, $this->action)) {
            return $this->{$this->action}();
        } else {
            throw new RuntimeException('Invalid Method', 405);
        }
    }

    /**
     * @param $data
     * @param $status
     * @return false|string
     */
    protected function response($data, $status = 500)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        return json_encode($data);
    }

    /**
     * @param $code
     * @return string
     */
    private function requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            401 => 'Unauthorized',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    /**
     * @return string|null
     */
    abstract public function getAction(): ?string;
}