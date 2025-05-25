<?php
namespace MiniERP\Rotas;
use MiniERP\Config\Rota;
require_once "config/Rota.php";
require_once "config/bancoDados/Conexao.php";
require_once "rotas/RotasProduto.php";
require_once "utils/Preco.php";

class Rotas
{
    public function __construct()
    {
        $rota = new Rota();
        new RotasProduto($rota);
    }
}