# Api de Transações em PHP

### Sobre o projeto:

Tem o intuito de exemplificar uma api RESTFul que possua tolerança a falha assim como notificações assincronas.

Tolera falha de:

- Indisponibilidade da api de autorização consumida.
- Indisponibilidade da api de notificação.
- Percistencia de banco de dados.

### Requisitos:

- Docker
- Docker-compose
- Make

### Instruções de Uso

- Dev ```make deploy_dev``` a api vai estar rodando na porta 8012 http
- HML ```make deploy_hml``` a api vai estar rodando na porta 8012 http e 12443 para https
- Testes ```make run_test```
- Descer ambiente ```make down```

Api consiste em dois endoints POST a serem consumidos de acordo com a documentação
[swagger](https://github.com/edsonls/desafio-back/blob/master/swagger/api.yaml).

### Feature:

- Criação de Usuario com os Tipos Comum e Lojista
- Criação de Transação

