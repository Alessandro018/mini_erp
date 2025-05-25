<?php
namespace MiniERP\Config\BancoDados;
use PDO;

class Conexao {
    private static $host = "";
    private static $bancoDados = "";
    private static $nomeUsuario = "";
    private static $senha = "";
    public static $conexao;

    public static function conectar() {
        if (self::$conexao == null) {
            self::$conexao = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$bancoDados, self::$nomeUsuario, self::$senha);
        }
        return self::$conexao;
    }
}
?>