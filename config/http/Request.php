<?php
namespace MiniERP\Config\Http;

class Request
{
    private $url;
    private $queryParams;
    private $pathParams;
    private $headers;
    private $body;
    private $qtyPathParams = 0;

    public function __construct()
    {
        $this->headers = (object)$_SERVER;
        $this->setRoute();

        $_SERVER = [];
        $_GET = [];
    }

    private static function mountRoute()
    {
        $route = (object)[
            "url"=> "",
            "pathParams"=> []
        ];

        $getUrl = isset($_GET['url']) ? explode("/", addslashes($_GET['url'])) : null;
        if ($getUrl) {
            for ($i = 0; $i < sizeof($getUrl); $i++) {
                $isNumber = strlen(preg_replace("/[^0-9]/", "", $getUrl[$i])) > 0 ? true : false;
                if ($isNumber) {
                    array_push($route->pathParams, $getUrl[$i]);
                } else {
                    $route->url .= "/$getUrl[$i]";
                }
            }
        }
        return $route;
    }

     private function setQueryParams()
    {
        unset($_GET["url"]);
        if(sizeof($_GET) > 0) {
            foreach($_GET as $key => $value) {
                $this->queryParams[$key] = $value;
            }
        } else {
            $this->queryParams = [];
        }
    }

    private function setRoute()
    {
        $mountRoute = self::mountRoute();
        $this->url = $mountRoute->url;
        $this->pathParams = $mountRoute->pathParams;
        $this->setQueryParams();
        $this->qtyPathParams = sizeof($mountRoute->pathParams);
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
        $this->body = isset($this->headers->CONTENT_TYPE) && $this->headers->CONTENT_TYPE == 'application/json' ? 
            json_decode(file_get_contents('php://input')) : (object)$_POST;
        file_put_contents('php://input', '');
        $_POST = [];
    }

    public function getBody()
    {
        return (object)$this->body;
    }

    public function requestMethod()
    {
        return $this->headers->REQUEST_METHOD;
    }

    private function mountParameters(string $url)
    {
        $explodeUrl = explode('/', $url);
        $qtyParameters = 0;

        foreach($explodeUrl as $urlPath) {
            if(preg_match("/[:]+[{]+[a-zA-Z]+[}]/", $urlPath) && isset($this->pathParams[$qtyParameters])) {
                $indice = str_replace([":", "{", "}"],  "", $urlPath);
                $this->pathParams[$indice] = $this->pathParams[$qtyParameters];
                unset($this->pathParams[$qtyParameters]);
                $qtyParameters++;
            }
        }
        $this->pathParams = (object)$this->pathParams;
    }

    public function validate(string $method, string $url, callable $callback)
    {
        $originalUrl = $url;
        $url = preg_replace("/[\/]+[:]+[{]+[a-zA-Z]+[}]/", "", $url);
        $qtyPathParameters = preg_match_all("/[:]+[{]+[a-zA-Z]+[}]/", $originalUrl);

        if($this->headers->REQUEST_METHOD == $method && $this->url == $url && $qtyPathParameters == $this->qtyPathParams) {
            if(preg_match("/[:]+[{]+[a-zA-Z]+[}]/", $originalUrl)) {
                $this->mountParameters($originalUrl);
            }
            $callback($this);
        }
    }

    public function getPathParams()
    {
        return $this->pathParams;
    }
}
?>