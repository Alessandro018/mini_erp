<?php
namespace MiniERP\Controller;
use MiniERP\Config\Http\Request;
use MiniERP\Model\Produto;
use MiniERP\Model\Estoque;
require_once "model/Produto.php";
require_once "model/Estoque.php";

class ProdutoController
{
    public static function inicio()
    {
        $produtos = Produto::buscarTodos();
        include "view/produto/inicio.php";
    }
    public static function cadastrar(Request $request)
    {
        $body = $request->getBody();
        $nome = addslashes($body->nome);
        $preco = addslashes($body->preco);
        $estoque = addslashes($body->estoque);

        $produto = new Produto(nome: $nome, preco: $preco);
        $produtoCadastrado = $produto->salvar();
        
        header("Content-Type: application/json");
        if($produtoCadastrado) {
            Estoque::movimentar([
                (object)[
                    "idProduto"=> $produto->id,
                    "idVariacao"=> null,
                    "quantidade"=> $estoque
                ]
            ]);
            header("HTTP/1.1 201 Created");
            echo json_encode($produto);
        } else {
            echo json_encode([
                "status"=> "erro",
                "mensagem"=> "Não foi possível cadastrar o produto"
            ]);
        }
    }
}
?>