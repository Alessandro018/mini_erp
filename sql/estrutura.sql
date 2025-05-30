CREATE DATABASE IF NOT EXISTS mini_erp;
USE mini_erp;

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    preco FLOAT(10,2)
);

CREATE TABLE IF NOT EXISTS produto_variacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT,
    tamanho VARCHAR(20),
    cor VARCHAR(20),
    FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT,
    id_variacao INT NULL,
    quantidade INT,
    FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_variacao) REFERENCES produto_variacoes(id)
);

CREATE TABLE IF NOT EXISTS cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20),
    desconto FLOAT(3,2)
);

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total FLOAT(10,2),
    id_cupom INT NULL,
    desconto FLOAT(10,2),
    status VARCHAR(20),
    cep VARCHAR(10),
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cupom) REFERENCES cupons(id)
);

CREATE TABLE IF NOT EXISTS pedidos_produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_produto INT,
    id_variacao INT NULL,
    quantidade INT,
    preco FLOAT(10,2),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id),
    FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_variacao) REFERENCES produto_variacoes(id)
);