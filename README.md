# 📦 Sistema de Gestão de Pedidos

Este projeto consiste em um sistema para cadastro e atualização de produtos e pedidos, com validações de frete e integração via webhook.

## ✅ Funcionalidades Desenvolvidas

- 📝 Cadastro de produto  
- 🔄 Atualização do produto  
- 🛒 Cadastro de pedido  
- 🚚 Validação de frete baseado no subtotal do pedido  
- 📍 Validação de CEP  
- 🔔 Webhook para atualização do pedido  

## 💻 Tecnologias Utilizadas

- **PHP** - Lógica de backend e integração com banco de dados  
- **jQuery** - Manipulação do DOM e requisições assíncronas  
- **MySQL** - Armazenamento de dados  
- **Bootstrap** - Estilização responsiva da interface  

## 📡 Exemplo de Payload para o Webhook


```json
{
  "id": 1,
  "status": "cancelado"
}
```
## ⚙️ Configure o ambiente:  
   - PHP >= 8.1  
   - Servidor MySQL  
   - Servidor Apache ou Nginx

### Observação
Para acessar os dados do produto e editar, basta clicar no card
