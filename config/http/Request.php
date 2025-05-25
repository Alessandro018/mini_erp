<?php
namespace MiniERP\Config\Http;

class Request
{
    private $url;
    private $queryParams;
    private $bodyParams;

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
        $this->setBodyParams();
    }

    public function route()
    {
        return $this->url;
    }

    public function queryParams()
    {
        return $this->queryParams;
    }

    private function setBodyParams()
    {
        $this->bodyParams = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json' ? 
            json_decode(file_get_contents('php://input')) : $_POST;
        file_put_contents('php://input', '');
        $_POST = [];
    }

    public function bodyParams()
    {
        return (object)$this->bodyParams;
    }

    public function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
?>