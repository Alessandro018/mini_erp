<?php
namespace MiniERP\Model;
use MiniERP\Config\BancoDados\Conexao;
use PDO;

class Estoque {
    private static function cadastrar(array $movimentacoes)
    {
        $parametrosSql = str_repeat("(?, ?, ?),", sizeof($movimentacoes));
        $parametrosSql = substr($parametrosSql, 0, strlen($parametrosSql) -1);
        $valoresSql = [];
        $placeholders = [];

        foreach($movimentacoes as $movimentacao) {
            $placeholders[] = "(?, ?, ?)";
            $valoresSql[] = $movimentacao->idProduto;
            $valoresSql[] = $movimentacao->idVariacao ?? null;
            $valoresSql[] = $movimentacao->quantidade;
        }
        $sql = "INSERT INTO estoque (id_produto, id_variacao, quantidade) VALUES ".implode(',', $placeholders);
        $movimentarEstoque = Conexao::conectar()->prepare($sql);
        $estoqueMovimentado = $movimentarEstoque->execute($valoresSql);
        
        return $estoqueMovimentado;
    }

    private static function atualizar(array $movimentacoes)
    {
        $qtdMovRealizadas = 0;
        $sql = "UPDATE estoque SET quantidade = ? WHERE id_produto = ?";
        $conexao = Conexao::conectar();

        foreach($movimentacoes as $movimentacao) {
            $movimentarEstoque = $conexao->prepare($sql);
            $atualizarEstoque = $movimentarEstoque->execute([$movimentacao->quantidade, $movimentacao->idProduto]);
            if($atualizarEstoque) {
                $qtdMovRealizadas++;
            }
        }
        return $qtdMovRealizadas > 0;
    }

    public static function movimentar(string $acao, array $movimentacoes) {
        return $acao == "cadastrar" ? self::cadastrar($movimentacoes) : self::atualizar($movimentacoes);
    }
}
?>