<?php
namespace MiniERP\Model;
use MiniERP\Config\BancoDados\Conexao;
use MiniERP\Utils\Preco;
use PDO;

class Produto
{
    public int|null $id;
    public string $nome;
    public float|null $preco;
    public int|null $estoque;
    public array|null $variacoes;

    public function __construct(string $nome, float|null $preco = null, int|null $estoque = null, array|null $variacoes = null, int|null $id = null)
    {
        $this->nome = $nome;
        $this->preco = $preco;
        $this->estoque = $estoque;
        $this->variacoes = $variacoes;
    }
    public static function buscarTodos()
    {
        $sql = "SELECT produtos.*, estoque.quantidade AS estoque FROM produtos LEFT JOIN estoque ON estoque.id_produto = produtos.id";
        $buscarProdutos = Conexao::conectar()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $produtos = [];

        foreach($buscarProdutos as $dadosProduto) {
            $produtos[] = new Produto(
                id: $dadosProduto["id"],
                nome: $dadosProduto["nome"],
                preco: $dadosProduto["preco"],
                estoque: $dadosProduto["estoque"]
            );
        }

        return $produtos;
    }

    public function salvar()
    {
        $sql = "INSERT INTO produtos SET nome = ?, preco = ?";
        $sqlCadastro = Conexao::conectar()->prepare($sql);
        $produtoCadastrado = $sqlCadastro->execute([$this->nome, $this->preco]);

        if($produtoCadastrado) {
            $this->id = Conexao::conectar()->lastInsertId();
        }
        return $produtoCadastrado;
    }

    public function exibirPreco()
    {
        return Preco::exibir($this->preco);
    }
}
?>