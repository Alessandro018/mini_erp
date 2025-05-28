<?php
namespace MiniERP\Controller;
use MiniERP\Config\Http\Request;
use MiniERP\Model\Produto;
use MiniERP\Model\Pedido;
require_once "model/Produto.php";
require_once "model/Pedido.php";

class PedidoController
{
    public static function adicionarProduto(Request $request)
    {
        $response = [
            "produtos"=> [],
            "frete"=> 0
        ];
        $host = $request->headers()->HTTP_HOST;
        $idProduto = (int)addslashes($request->getPathParams()->idProduto);
        $produto = Produto::buscar($idProduto);

        if(isset($produto->id)) {
            $produtosCarrinho = $_SESSION[$host]["carrinho"]["produtos"];
            $idsProdutos = array_column($produtosCarrinho, "idProduto");
            $indiceProdutoCarrinho = array_search($produto->id, $idsProdutos);

            if($indiceProdutoCarrinho !== false) {
                $_SESSION[$host]["carrinho"]["produtos"][$indiceProdutoCarrinho]["quantidade"] += 1;
            } else {
                $_SESSION[$host]["carrinho"]["produtos"][] = [
                    "idProduto"=> $produto->id,
                    "quantidade"=> 1
                ];
            }

            $produtosCarrinho = $_SESSION[$host]["carrinho"]["produtos"];
            $carrinhoAtualizado = [];
            $subtotal = 0;
            
            foreach($produtosCarrinho as $produtoCarrinho) {
                $id = (int)addslashes($produtoCarrinho["idProduto"]);
                $produto = Produto::buscar($id);
                $quantidade = $produto->estoque >= $produtoCarrinho["quantidade"] ? $produtoCarrinho["quantidade"] : $produto->estoque;

                if($quantidade > 0) {
                    $carrinhoAtualizado[] = [
                        "idProduto"=> $produto->id,
                        "quantidade"=> $quantidade
                    ];
                    $response["produtos"][] = [
                        "nome"=> $produto->nome,
                        "preco"=> $produto->preco,
                        "quantidade"=> $quantidade
                    ];
                    $subtotal += $quantidade * $produto->preco;
                }
            }
            $_SESSION[$host]["carrinho"]["produtos"] = $carrinhoAtualizado;
            $frete = Pedido::calcularFrete($subtotal);
            $response["frete"] = $frete;
        }
        header("Content-Type: application/json");
        echo json_encode($response);
    }

    public static function detalhesCarrinho(Request $request)
    {
        $host = $request->headers()->HTTP_HOST;
        $pedido = [
            "produtos"=> [],
            "frete"=> 0
        ];
        $produtosCarrinho = $_SESSION[$host]["carrinho"]["produtos"];
        $carrinhoAtualizado = [];
        $subtotal = 0;
        
        foreach($produtosCarrinho as $produtoCarrinho) {
            $id = (int)addslashes($produtoCarrinho["idProduto"]);
            $produto = Produto::buscar($id);
            $quantidade = $produto->estoque >= $produtoCarrinho["quantidade"] ? $produtoCarrinho["quantidade"] : $produto->estoque;

            if($quantidade > 0) {
                $carrinhoAtualizado[] = [
                    "idProduto"=> $produto->id,
                    "quantidade"=> $quantidade
                ];
                $pedido["produtos"][] = [
                    "nome"=> $produto->nome,
                    "preco"=> $produto->preco,
                    "quantidade"=> $quantidade
                ];
                $subtotal += $quantidade * $produto->preco;
            }
        }
        $_SESSION[$host]["carrinho"]["produtos"] = $carrinhoAtualizado;
        $frete = Pedido::calcularFrete($subtotal);

        return $pedido;
    }
}