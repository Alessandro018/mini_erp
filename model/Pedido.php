<?php
namespace MiniERP\Model;
use MiniERP\Model\Produto;
use MiniERP\Config\BancoDados\Conexao;

class Pedido {
    public array $itens;
    public string $cep;

    public function __construct(array $itens, string $cep)
    {
        $this->itens = $itens;
        $this->cep = $cep;
    }

    public function salvar()
    {
        $conexao = Conexao::conectar();
        $subtotal = 0;
        $frete = 0;
        $sqlPedido = "INSERT INTO pedidos SET total = ?, cep = ?, status = ?";
        $sqlCadastroPedido = $conexao->prepare($sqlPedido);
        $cadastrarPedido = $sqlCadastroPedido->execute([$subtotal, $this->cep, 'Finalizado']);
        $idPedido = $cadastrarPedido ? $conexao->lastInsertId() : null;
        
        if($idPedido !== null) {
            $placeholders = [];
            $valoresSql = [];
    
            foreach($this->itens as $item) {
                $placeholders[] = "(?, ?, ?, ?)";
                $valoresSql[] = $idPedido;
                $valoresSql[] = $item["idProduto"];
                $valoresSql[] = $item["quantidade"];
                $valoresSql[] = $item["preco"];
                $subtotal += $item["quantidade"] * $item["preco"];
            }
            $frete = self::calcularFrete($subtotal);
            $total = $subtotal + $frete;
            $sqlCadastroItem = "INSERT INTO pedidos_produtos(id_pedido, id_produto, quantidade, preco) VALUES ".implode(',', $placeholders);
            $cadastrarItens = $conexao->prepare($sqlCadastroItem);
            $cadastrarItens->execute($valoresSql);
            $atualizarValorPedido = $conexao->prepare("UPDATE pedidos SET total = ? WHERE id = ?");
            $atualizarValorPedido->execute([$total, $idPedido]);
        }

        return $cadastrarPedido;
    }

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
        $pedido["frete"] = $subtotal > 0 ? self::calcularFrete($subtotal) : 0;

        return $pedido;
    }

    public static function atualizar(int $id, string $status)
    {
        $atualizarPedido = Conexao::conectar()->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $pedidoAtualizado = $atualizarPedido->execute([$status, $id]);

        return $pedidoAtualizado;
    }

    public static function remover(int $id)
    {
        $removerPedido = Conexao::conectar()->prepare("DELETE FROM pedidos WHERE id = ?");
        $pedidoRemovido = $removerPedido->execute([$id]);
        
        if($pedidoRemovido) {
            $removerItens = Conexao::conectar()->prepare("DELETE FROM pedidos_produtos WHERE id_pedido = ?");
            $removerItens->execute([$id]);
        }

        return $pedidoRemovido;
    }
}
?>