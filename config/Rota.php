<?php
namespace MiniERP\Config;
use MiniERP\Config\Http\Request;
require_once 'config/http/Request.php';

class Rota
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function get(string $route, callable $function)
    {
        if($this->request->route() == $route) {
            $this->validateMethod(__FUNCTION__);
            if(is_callable($function)) {
                $function($this->request);
            }
        }
    }

    public function post(string $route, callable $function)
    {
        if($this->request->route() == $route) {
            $this->validateMethod(__FUNCTION__);
            if(is_callable($function)) {
                $function($this->request);
            }
        }
    }

    private function validateMethod(string $method)
    {
        if(strtolower($_SERVER['REQUEST_METHOD']) != $method) {
            header('HTTP/1.1 405 Method Not Allowed');
            exit();
        }
    }

    public function getRequest() : Request
    {
        return $this->request;
    }
}
?>