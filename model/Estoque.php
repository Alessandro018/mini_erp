<?php
namespace MiniERP\Model;
use MiniERP\Config\BancoDados\Conexao;
use PDO;

class Estoque {
    // public static function getAll() {
    //     $sql = "SELECT e.*, p.nome as produto_nome, v.nome as variacao_nome FROM estoque e LEFT JOIN produtos p ON e.produto_id = p.id LEFT JOIN produto_variacoes v ON e.variacao_id = v.id";
    //     return Database::connect()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    // }

    public static function movimentar(array $movimentacoes) {
        $parametrosSql = str_repeat("(?, ?, ?),", sizeof($movimentacoes));
        $parametrosSql = substr($parametrosSql, 0, strlen($parametrosSql) -1);
        $sql = "INSERT INTO estoque (id_produto, id_variacao, quantidade) VALUES $parametrosSql";
        $parametrosInsert = [];

        foreach($movimentacoes as $movimentacao) {
            $parametrosInsert[] = [$movimentacao->idProduto, $movimentacao->idVariacao ?? null, $movimentacao->quantidade];
        }
        $movimentarEstoque = Conexao::conectar()->prepare($sql);
        $estoqueMovimentado = $movimentarEstoque->execute(...$parametrosInsert);
        
        return $estoqueMovimentado;
    }

    // public static function update($id, $quantidade) {
    //     $sql = "UPDATE estoque SET quantidade = ? WHERE id = ?";
    //     $stmt = Database::connect()->prepare($sql);
    //     $stmt->execute([$quantidade, $id]);
    // }
}
?>