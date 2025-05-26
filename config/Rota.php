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
        $this->request->validate(method: "GET", url: $route, callback: $function);
    }

    public function post(string $route, callable $function)
    {
        $this->request->validate(method: "POST", url: $route, callback: $function);
    }

    public function put(string $route, callable $function)
    {
        $this->request->validate(method: "PUT", url: $route, callback: $function);
    }

    public function getRequest() : Request
    {
        return $this->request;
    }
}
?>