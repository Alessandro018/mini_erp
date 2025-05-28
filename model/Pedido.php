<?php
namespace MiniERP\Model;
use MiniERP\Model\Produto;

class Pedido {
    public static function calcularFrete(float $subtotal)
    {
        $frete = $subtotal >= 52 && $subtotal <= 166.59 ? 15 : ($subtotal > 200 ? 0 : 20);
        return $frete;
    }

    public static function detalhes(array $produtosCarrinho)
    {
        $pedido = [
            "produtos"=> [],
            "frete"=> 0
        ];
        $subtotal = 0;
        
        foreach($produtosCarrinho as $produtoCarrinho) {
            $id = (int)addslashes($produtoCarrinho["idProduto"]);
            $produto = Produto::buscar($id);
            $quantidade = $produto->estoque >= $produtoCarrinho["quantidade"] ? $produtoCarrinho["quantidade"] : $produto->estoque;

            if($quantidade > 0) {
                $pedido["produtos"][] = [
                    "nome"=> $produto->nome,
                    "preco"=> $produto->preco,
                    "quantidade"=> $quantidade
                ];
                $subtotal += $quantidade * $produto->preco;
            }
        }
        $pedido["frete"] = self::calcularFrete($subtotal);

        return $pedido;
    }
}
?>