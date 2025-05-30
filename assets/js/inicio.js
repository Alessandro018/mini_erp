$(document).ready(function () {
    const exibirPreco = (preco) => preco.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})

    $('#modalExibeErro').on('show.bs.modal', () => {
        Array.from($('.modal[id!=modalExibeErro]')).map(modal => {
            $(modal).css('z-index', 1040);
        })
    })

    $('#modalExibeErro').on('hide.bs.modal', () => {
        Array.from($('.modal[id!=modalExibeErro]')).map(modal => {
            $(modal).css('z-index', 1055);
        })
    })

    $('#modalCadastroProduto').on('hide.bs.modal', () => {
        $('#modalCadastroProduto form')[0].reset()
        $('#dadosProdutoPrincipal').removeClass('d-none').addClass('d-flex')
        $('#cadastroProduto input[name=precoProduto]').attr('required', true)
        $('#cadastroProduto input[name=quantidadeEstoque]').attr('required', true)
        $('#cadastroProduto input[name=idProduto]').val('')
        $('#cadastroProduto button[type=submit]').html('Cadastrar')
        $('#variacoesProduto').html('')
    })

    $("#cadastroProduto").on("submit", (e) => {
        e.preventDefault()
        let botaoEnviar = $(this).find('button[type=submit]')

        let idProduto = $(this).find('input[name=idProduto]').val()
        let nomeProduto = $(this).find('input[name=nomeProduto]').val()
        let precoProduto = parseFloat($(this).find('input[name=precoProduto]').val().replace('.', '').replace(',', '.'))
        let quantidadeEstoque = $(this).find('input[name=quantidadeEstoque]').val()

        $(botaoEnviar).attr('disabled', true)

        $.ajax({
            url: idProduto.length > 0 ? `produtos/${idProduto}` : 'produtos',
            method: idProduto.length > 0 ? 'PUT' : 'POST',
            contentType: 'application/json',
            accepts: ['application/json'],
            dataType: 'json',
            data: JSON.stringify({
                nome: nomeProduto,
                preco: precoProduto,
                estoque: quantidadeEstoque
            }),
            statusCode: {
                200: (response) => {
                    if(response.status == 'erro') {
                        $('#mensagemErro').html(response.mensagem)
                        $('#modalExibeErro').modal('show')
                    }
                    $(botaoEnviar).removeAttr('disabled').html('Cadastrar')
                },
                201: (response) => {
                    $('#exibeProdutos').append(`
                        <div class="card produto" data-id-produto="${response.id}">
                            <span class="imagem-produto w-100 rounded-top-1"></span>
                            <div class="card-body">
                                <span class="card-title nome-produto">${nomeProduto}</span>
                                <p class="card-text text-primary preco-produto">R$ ${exibirPreco(response.preco)}</p>
                                <button type="button" class="btn btn-primary w-100 adicionar-produto" data-bs-toggle="offcanvas" data-bs-target="#modalCarrinho">Comprar</button>
                            </div>
                        </div>
                    `)
                    $('#modalCadastroProduto').modal('hide')
                    $(botaoEnviar).removeAttr('disabled').html('Cadastrar')
                },
                204: () => {
                    let produto = $(`#exibeProdutos .produto[data-id-produto="${idProduto}"]`)
                    $(produto).find('.nome-produto').html(nomeProduto)
                    $(produto).find('.preco-produto').html(`R$ ${exibirPreco(precoProduto)}`)
                    $('#modalCadastroProduto').modal('hide')
                    $(botaoEnviar).removeAttr('disabled').html('Cadastrar')
                }
            }
        })
    })

    $('body').on('click', '.produto', (e) => {
        if(!e.target.classList.contains('adicionar-produto')) {
            let idProduto = e.currentTarget.getAttribute('data-id-produto')

            $.ajax({
                url: `produtos/${idProduto}`,
                method: 'GET',
                accepts: ['application/json'],
                dataType: 'json',
                success: (produto) => {
                    $('#cadastroProduto input[name=idProduto]').val(produto.id)
                    $('#cadastroProduto input[name=nomeProduto]').val(produto.nome)
                    $('#cadastroProduto input[name=precoProduto]').val(exibirPreco(produto.preco))
                    $('#cadastroProduto input[name=quantidadeEstoque]').val(produto.estoque)
                    $('#cadastroProduto button[type=submit]').html('Atualizar')
                    $('#modalCadastroProduto').modal('show')
                }
            })
        }
    })

    $('body').on('click', '.adicionar-produto', (e) => {
        let idProduto = $(e.currentTarget).parents('.produto').attr('data-id-produto')

        $.ajax({
            url: `pedidos/produtos/${idProduto}`,
            method: 'POST',
            accepts: ['application/json'],
            dataType: 'json',
            success: (carrinho) => {
                $('#produtosCarrinho').html('')
                let {produtos, frete} = carrinho
                let subtotal = 0;
                
                produtos.map(produto => {
                    subtotal += (produto.quantidade * produto.preco)

                    $('#produtosCarrinho').append(`
                        <div class="d-flex gap-4 border rounded-2 p-2 produto-carrinho">
                            <div class="cor-fundo-cinza imagem-produto"></div>
                            <div class="d-flex flex-column">
                                <span>${produto.nome}</span>
                                <span>Qtd.: ${produto.quantidade}</span>
                                <span>R$ ${exibirPreco(produto.preco)}</span>
                            </div>
                        </div>
                    `)
                })
                $('#subtotalPedido').html(`R$ ${exibirPreco(subtotal)}`)
                $('#valorFrete').html(`R$ ${exibirPreco(frete)}`)
                $('#totalPedido').html(`R$ ${exibirPreco(subtotal + frete)}`)
                $('#finalizarPedido button[type=submit]').removeClass('d-none')
            }
        })
    })

    $('#finalizarPedido').on('submit', async (e) => {
        e.preventDefault()
        let cepEntrega = $('#cepEntrega').val()
        let botaoFinalizar = $(this).find('button[type=submit]')
        $(botaoFinalizar).attr('disabled', true).html('Aguarde...')

        $.ajax({
            url: 'pedidos',
            method: 'POST',
            accepts: ['application/json'],
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                cepEntrega: cepEntrega
            }),
            success: () => {
                $(botaoFinalizar).removeAttr('disabled').html('Finalizar')
            },
            statusCode: {
                200: (response) => {
                    if(response.status == 'erro') {
                        $('#mensagemErro').html(response.mensagem)
                        $('#modalExibeErro').modal('show')
                    }
                },
                201: () => {
                    $('#finalizarPedido')[0].reset()
                    $('#produtosCarrinho').html('')
                    $('#subtotalPedido').html(`R$ 0,00`)
                    $('#valorFrete').html(`R$ 0,00`)
                    $('#totalPedido').html(`R$ 0,00`)
                    $('#modalCarrinho').modal('hide')
                    $('#finalizarPedido button[type=submit]').addClass('d-none').removeAttr('disabled').html('Finalizar')
                    $('#mensagemSucesso').html('Pedido realizado com sucesso')
                    $('#modalExibeSucesso').modal('show')
                }
            }
        })
    })
})