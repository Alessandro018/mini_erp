<?php
namespace MiniERP\Utils;

class Preco
{
    public static function exibir(float $preco)
    {
        return number_format($preco, 2, ",", ".");
    }
}