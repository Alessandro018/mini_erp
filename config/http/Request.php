<?php
namespace MiniERP\Config\Http;

class Request
{
    private $url;
    private $queryParams;
    private $body;

    public function __construct()
    {
        global $_GET;
        $getUrl = isset($_GET['url']) ? explode("/", addslashes($_GET['url'])) : null;
        if($getUrl) {
            for($i=0; $i < sizeof($getUrl); $i++ ){
                $isNumber = strlen(preg_replace("/[^0-9]/", "", $getUrl[$i])) > 0 ? true : false;
                if($isNumber) {
                    $this->queryParams = $getUrl[$i];
                }
                else {
                    $this->url .= "/$getUrl[$i]";
                }
            }
        }
        $this->setBody();
    }

    public function route()
    {
        return $this->url;
    }

    public function queryParams()
    {
        return $this->queryParams;
    }

    private function setBody()
    {
        $this->body = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json' ? 
            json_decode(file_get_contents('php://input')) : $_POST;
        file_put_contents('php://input', '');
        $_POST = [];
    }

    public function getBody()
    {
        return (object)$this->body;
    }

    public function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
?>