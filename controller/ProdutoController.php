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
        require_once "view/produto/inicio.php";
    }
    public static function cadastrar(Request $request)
    {
        $body = $request->getBody();

        if(!isset($body->nome)) {
            echo json_encode([
                "status"=> "erro",
                "mensagem"=> "Nome do produto é obrigatório"
            ]);
            exit;
        }

        $nome = addslashes($body->nome);
        $preco = addslashes($body->preco);
        $estoque = addslashes($body->estoque);

        $produto = new Produto(nome: $nome, preco: $preco);
        $produtoCadastrado = $produto->salvar();
        
        header("Content-Type: application/json");
        if($produtoCadastrado) {
            Estoque::movimentar(
                acao: "cadastrar",
                movimentacoes: [
                    (object)[
                        "idProduto"=> $produto->id,
                        "idVariacao"=> null,
                        "quantidade"=> $estoque
                    ]
                ]
            );
            header("HTTP/1.1 201 Created");
            echo json_encode($produto);
        } else {
            echo json_encode([
                "status"=> "erro",
                "mensagem"=> "Não foi possível cadastrar o produto"
            ]);
        }
    }

    public static function detalhesProduto(Request $request)
    {
        $id = (int)addslashes($request->getPathParams()->id);
        $produto = Produto::buscar($id);
        
        header("Content-Type: application/json");
        if(isset($produto->id)) {
            echo json_encode($produto);
        }
    }

    public static function atualizarProduto(Request $request)
    {
        $id = (int)addslashes($request->getPathParams()->id);
        $produto = Produto::buscar($id);
        $body = $request->getBody();

        if(isset($produto->id)) {
            $nome = addslashes($body->nome);
            $preco = (float)addslashes($body->preco);
            $estoque = addslashes($body->estoque);
            $atualizacaoProduto = new Produto(
                id: $id,
                nome: $nome,
                preco: $preco
            );

            $produtoAtualizado = Produto::atualizar($atualizacaoProduto);
            if($produtoAtualizado) {
                Estoque::movimentar(
                    acao: "atualizar",
                    movimentacoes: [
                        (object)[
                            "idProduto"=> $id,
                            "idVariacao"=> null,
                            "quantidade"=> $estoque
                        ]
                    ]
                );
                header("HTTP/1.1 204 No Content");
            }
        }
    }
}
?>