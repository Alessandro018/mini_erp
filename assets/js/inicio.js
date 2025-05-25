$(document).ready(function () {
    const exibirPreco = (preco) => preco.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})

    $('#modalCadastroProduto').on('hide.bs.modal', () => {
        $('#modalCadastroProduto form')[0].reset()
        $('#dadosProdutoPrincipal').removeClass('d-none').addClass('d-flex')
        $('#cadastrarProduto input[name=precoProduto]').attr('required', true)
        $('#cadastrarProduto input[name=quantidadeEstoque]').attr('required', true)
        $('#variacoesProduto').html('')
    })

    $('#btnAdicionarVariacao').on('click', () => {
        $('#dadosProdutoPrincipal').removeClass('d-flex').addClass('d-none')
        $('#cadastrarProduto input[name=precoProduto]').removeAttr('required')
        $('#cadastrarProduto input[name=quantidadeEstoque]').removeAttr('required')
        let indiceVariacao = $('#variacoesProduto .variacao-produto').length

        $('#variacoesProduto').append(`
            <div class="d-flex column-gap-4 rounded-2 p-2 cor-fundo-cinza variacao-produto">
                <div class="d-flex flex-column gap-1 w-25">
                    <label class="label-form">Cor</label>
                    <input type="text" class="form-control" name="cor_${indiceVariacao}">
                </div>
                <div class="d-flex flex-column gap-1 w-25">
                    <label class="label-form">Tamanho</label>
                    <input type="text" class="form-control" name="tamanho_${indiceVariacao}">
                </div>
                <div class="d-flex flex-column gap-1 w-25">
                    <label class="label-form">Preço</label>
                    <input type="text" class="form-control" name="preco_${indiceVariacao}">
                </div>
                <div class="d-flex flex-column gap-1 w-25">
                    <label class="label-form">Estoque</label>
                    <input type="text" class="form-control" name="estoque_${indiceVariacao}">
                </div>
            </div>
        `)
    })

    $("#cadastrarProduto").on("submit", (e) => {
        e.preventDefault()

        let nomeProduto = $(this).find('input[name=nomeProduto]').val()
        let precoProduto = $(this).find('input[name=precoProduto]').val()
        let quantidadeEstoque = $(this).find('input[name=quantidadeEstoque]').val()

        $.ajax({
            url: 'produtos',
            method: 'POST',
            contentType: 'application/json',
            accepts: ['application/json'],
            dataType: 'json',
            data: JSON.stringify({
                nome: nomeProduto,
                preco: precoProduto,
                estoque: quantidadeEstoque
            }),
            success: (response) => {
                if(response.id) {
                    $('#exibeProdutos').append(`
                        <div class="card produto" data-idproduto="${response.id}">
                            <span class="imagem-produto w-100 rounded-top-1"></span>
                            <div class="card-body">
                                <span class="card-title nome-produto">${nomeProduto}</span>
                                <p class="card-text text-primary">${exibirPreco(response.preco)}</p>
                                <button type="button" class="btn btn-primary w-100 adicionar-produto">Comprar</button>
                            </div>
                        </div>
                    `)
                    $('#modalCadastroProduto').modal('hide')
                }
            }
        })
    })

    // $('body').on('click', '.adicionar-produto', () => {
    //     $('#modalCadastroProduto').modal('show')
    // })
})