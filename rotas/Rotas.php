<?php
namespace MiniERP\Rotas;
use MiniERP\Config\Rota;
use MiniERP\Rotas\RotasProduto;
use MiniERP\Rotas\RotasPedidos;
require_once "config/Rota.php";
require_once "config/bancoDados/Conexao.php";
require_once "rotas/RotasProduto.php";
require_once "rotas/RotasPedido.php";
require_once "utils/Preco.php";

class Rotas
{
    public function __construct()
    {
        $rota = new Rota();
        new RotasProduto($rota);
        new RotasPedidos($rota);
    }
}