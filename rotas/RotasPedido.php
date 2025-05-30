<?php
namespace MiniERP\Rotas;
use MiniERP\Config\Rota;
use MiniERP\Config\Http\Request;
use MiniERP\Controller\PedidoController;
require_once "controller/PedidoController.php";

class RotasPedidos
{
    public function __construct(Rota $rota)
    {
        $rota->post("/pedidos/produtos/:{idProduto}", function(Request $request) {
            PedidoController::adicionarProduto($request);
        });
        $rota->post("/pedidos", function(Request $request) {
            PedidoController::criarPedido($request);
        });
        
    }
}