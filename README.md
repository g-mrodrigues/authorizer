# Autorizador

Aplicação que autoriza transações para uma conta específica via linha de comando.

## Contexto

Aplicação desenvolvida na linguagem PHP na sua ultima versão (PHP 8.0). Foi escolhido o uso do framework Symfony pois 
fornece uma simples e completa API para implementações de CLI e ser facilmente testável.
Esta aplicação foi baseada na Arquitetura Limpa (Clean Architecture), uma arquitetura simples, de baixo acomplamento,
altamente testável e fácilmente extensível.

![Architecture Driagram](files/authorizer-clean-architecture-2.png "Clean Architecture" )

## Stack

- [Git & Bash](https://git-scm.com/downloads)
- [Docker 20.10.7](https://www.docker.com/products/docker-desktop)
- [Docker Compose 1.29.2](https://docs.docker.com/compose/install/)
- [PHP 8.0](https://www.php.net/downloads.php#v8.0.9)
- [Symfony 5.3.4](https://github.com/symfony/symfony/tree/5.3)
- [PHPUnit 9.5.8](https://github.com/sebastianbergmann/phpunit/tree/9.5)

## Code Coverage
- [Code Coverage](./app/coverage/dashboard.html)

## Instruções
### Build
1) Verificar se as dependencias `Docker` and `Docker Compose` estão instaladas;
2) Na raiz do repositório, executar o seguinte comando para buildar e subir o container
   ```
    docker-compose up -d
   ```

### Execute
- Na raiz do projeto execute os seguintes comandos:
   ```shell
    chmod +x authorizer
    ```
   ```shell
   ./authorizer FILE_PATH
   ```
   Altere FILE_PATH para o caminho do arquivo que deseja executar

### Tests
- Para rodar os testes execute os seguintes comandos:
   ```shell
   chmod +x tests
   ```
  ```shell
   ./tests TESTS_TYPE
   ```
  Caso queira rodar um tipo especifico de teste, altere `TESTS_TYPE` para `Unit`, caso queira executar apenas testes 
  unitários, ou `Feature`, caso queira executar apenas testes de integração. Para executar ambos os testes, nenhum 
  argumento deve ser passado.
