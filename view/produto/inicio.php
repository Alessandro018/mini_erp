<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Mini ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="./assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php
            use MiniERP\Utils\Preco;

            require_once "./view/layout/barraLateral.php";
            ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 d-flex flex-column gap-4 overflow-hidden" style="max-height: 100vh;">
                <div class="d-flex justify-content-between">
                    <h1>Mini ERP</h1>
                    <button class="btn cor-fundo-clara text-primary px-4 py-2 fs-4 border border-primary" data-bs-toggle="offcanvas" data-bs-target="#modalCarrinho">
                        <i class="bi bi-cart-fill"></i>
                    </button>
                </div>
                <div class="d-flex flex-column gap-1" style="overflow-y: auto;">
                    <div class="d-flex justify-content-between rounded-2 p-3 cor-fundo-clara">
                        <h3>Produtos</h3>
                        <button type="button" class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#modalCadastroProduto">Cadastrar</button>
                    </div>
                    <div class="conteudo d-flex flex-column rounded-2 p-3" style="overflow-y: auto;">
                        <div class="d-flex flex-wrap column-gap-4 row-gap-5" id="exibeProdutos">
                            <?php
                            foreach ($produtos as $produto) { ?>
                                <div class="card produto" data-id-produto="<?= $produto->id; ?>">
                                    <span class="imagem-produto w-100 rounded-top-1"></span>
                                    <div class="card-body">
                                        <span class="card-title nome-produto"><?= $produto->nome; ?></span>
                                        <p class="card-text text-primary preco-produto">R$ <?= $produto->exibirPreco(); ?></p>
                                        <button type="button" class="btn btn-primary w-100 adicionar-produto" data-bs-toggle="offcanvas" data-bs-target="#modalCarrinho">Comprar</button>
                                    </div>
                                </div>
                            <?php
                            } ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="modal fade " id="modalCadastroProduto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form class="modal-content" id="cadastroProduto">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">Cadastrar Produto</h1>
                </div>
                <div class="modal-body d-flex flex-column gap-4">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-column gap-1">
                            <label class="label-form">Nome</label>
                            <input type="text" class="form-control" name="nomeProduto" required>
                        </div>
                        <div class="d-flex gap-4" id="dadosProdutoPrincipal">
                            <div class="d-flex flex-column gap-1 w-25">
                                <label class="label-form">Preço</label>
                                <input type="text" class="form-control" name="precoProduto" required>
                            </div>
                            <div class="d-flex flex-column gap-1 w-25">
                                <label class="label-form">Estoque</label>
                                <input type="text" class="form-control" name="quantidadeEstoque" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-4 border-top-0">
                    <input type="hidden" name="idProduto">
                    <button type="button" class="btn btn-secondary px-5" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary px-5">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalExibeErro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center gap-4">
                    <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                    <h6 class="text-danger mb-0" id="mensagemErro"></h6>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-4 border-top-0">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalExibeSucesso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center gap-4">
                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    <h6 class="text-success mb-0" id="mensagemSucesso"></h6>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-4 border-top-0">
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="modalCarrinho">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Meu Carrinho</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="d-flex flex-column gap-5">
                <div class="d-flex flex-column gap-2" id="produtosCarrinho">
                    <?php
                    $produtosCarrinho = $carrinho["produtos"];
                    $subtotal = 0;
                    foreach($produtosCarrinho as $produto) {?>
                        <div class="d-flex gap-4 border rounded-2 p-2 produto-carrinho">
                            <div class="cor-fundo-cinza imagem-produto"></div>
                            <div class="d-flex flex-column">
                                <span><?=$produto["nome"];?></span>
                                <span>R$ <?=Preco::exibir($produto["preco"]);?></span>
                            </div>
                        </div>
                        <?php
                        $subtotal += $produto["preco"] * $produto["quantidade"];
                    }?>
                </div>
                <form class="d-flex flex-column gap-4" id="finalizarPedido">
                    <div class="d-flex flex-column gap-1 w-50">
                        <label class="label-form">Cep</label>
                        <input type="text" class="form-control" id="cepEntrega" autocomplete="off" minlength="8" maxlength="9" required>
                    </div>
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <h6>Subtotal</h6>
                                <h6 id="subtotalPedido">R$ <?=Preco::exibir($subtotal);?></h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6>Frete</h6>
                                <h6 id="valorFrete">R$ <?=Preco::exibir($carrinho["frete"]);?></h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h5>Total</h5>
                                <h5 id="totalPedido">R$ <?=Preco::exibir($subtotal + $carrinho["frete"]);?></h5>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary <?=sizeof($produtosCarrinho) == 0 ? 'd-none' : '';?>">Finalizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="./assets/js/inicio.js"></script>
</body>

</html>