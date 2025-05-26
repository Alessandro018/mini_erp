<?php
namespace MiniERP\Rotas;
use MiniERP\Config\Rota;
use MiniERP\Config\Http\Request;
use MiniERP\Controller\ProdutoController;
require_once "controller/ProdutoController.php";

class RotasProduto
{
    public function __construct(Rota $rota)
    {
        $rota->get("", function(Request $request) {
            ProdutoController::inicio();
        });
        $rota->get("/produtos/:{id}", function(Request $request) {
            ProdutoController::detalhesProduto($request);
        });
        $rota->post("/produtos", function(Request $request) {
            ProdutoController::cadastrar($request);
        });
        $rota->put("/produtos/:{id}", function(Request $request) {
            ProdutoController::atualizarProduto($request);
        });
    }
}