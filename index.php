<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Mini ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php require_once "./view/layout/barraLateral.php"; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 d-flex flex-column gap-4 overflow-hidden" style="max-height: 100vh;">
                <h1>Mini ERP</h1>
                <div class="d-flex flex-column gap-1" style="overflow-y: auto;">
                    <div class="d-flex justify-content-between rounded-2 p-3 cor-fundo-clara">
                        <h3>Produtos</h3>
                        <button type="button" class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#modalCadastroProduto">Cadastrar</button>
                    </div>
                    <div class="conteudo d-flex flex-column rounded-2 p-3" style="overflow-y: auto;">
                        <div class="exibe-produtos d-flex flex-wrap column-gap-4 row-gap-5">
                            <?php
                            for ($i = 0; $i < 10; $i++) { ?>
                                <div class="card produto">
                                    <span class="imagem-produto w-100 rounded-top-1"></span>
                                    <div class="card-body">
                                        <span class="card-title nome-produto">Nome do produto aqui</span>
                                        <p class="card-text text-primary">R$ 59,90</p>
                                        <button type="button" class="btn btn-primary w-100 adicionar-produto">Comprar</button>
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
    <div class="modal fade " id="modalCadastroProduto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cadastrar Produto</h1>
                </div>
                <div class="modal-body d-flex flex-column gap-4">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-column gap-1">
                            <label class="label-form">Nome</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="d-flex gap-4">
                            <div class="d-flex flex-column gap-1 w-25">
                                <label class="label-form">Preço</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="d-flex flex-column gap-1 w-25">
                                <label class="label-form">Estoque</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex justify-content-between">
                            <h6>Variações</h6>
                            <button type="button" class="btn btn-primary px-4 btn-sm">Adicionar</button>
                        </div>
                        <div class="d-flex flex-lg-row column-gap-4 row-gap-2">
                            <div class="d-flex flex-column gap-1 w-25">
                                <label class="label-form">Cor</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="d-flex flex-column gap-1 w-25">
                                <label class="label-form">Tamanho</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="d-flex flex-column gap-1 w-25">
                                <label class="label-form">Preço</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="d-flex flex-column gap-1 w-25">
                                <label class="label-form">Estoque</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-4 border-top-0">
                    <button type="button" class="btn btn-secondary px-5" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary px-5">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="./assets/js/inicio.js"></script>
</body>

</html>