<?php
namespace MiniERP\Rotas;
use MiniERP\Config\Http\Request;
use MiniERP\Config\Rota;
require_once 'config/Rota.php';
require_once 'config/bancoDados/Conexao.php';


class Rotas
{
    public function __construct()
    {
        $rota = new Rota();
        $rota->get('', function(Request $request) {
            include "view/produto/inicio.php";
        });
    }
}