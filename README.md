# Teste Técnico - Beer and Code

Este projeto é uma aplicação web para o CTO avaliar e filtrar desenvolvedores de código aberto usando métricas definidas
para ajudar na avaliação. A aplicação utiliza Laravel, Laravel Sail, e a API do GitHub para recuperar e armazenar
informações dos desenvolvedores.

## Instalação

O projeto utiliza o pacote *Laravel Sail* que facilita configuração do ambiente
de desenvolvimento. Será necessário a instalação do Docker e o Docker Compose em sua máquina.

Links para instalação e configuração de Docker:

- [Windows](https://docs.docker.com/docker-for-windows/install/)
- [Linux (Debian based)](https://docs.docker.com/engine/install/ubuntu/)

### Passos para Instalação

1. **Clone o repositório:**

   ```
   git clone https://github.com/mariotinelli/dev-search.git
   cd dev-search
    ```

2. **Copie o arquivo .env.example para .env e configure:**

   ``` 
   GITHUB_TOKEN=your_github_token
   ```

3. **Execute o comando de configuração**

    ```shell
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
     ```
4. **Inicie o ambiente de desenvolvimento**

   Execute o comando abaixo para iniciar o ambiente de desenvolvimento:
    ```shell
    ./vendor/bin/sail up -d
    ```

5. **Execute as migrações e seeders**

    ```shell
    ./vendor/bin/sail artisan migrate
    ./vendor/bin/sail artisan db:seed
    ```

6. **Carregue os dados dos desenvolvedores**

    ```shell
    ./vendor/bin/sail artisan horizon
    ./vendor/bin/sail artisan horizon:work
    ./vendor/bin/sail artisan horizon:work --queue=developers-sync
    ./vendor/bin/sail artisan fetch:developers
    ```
   ou

    ```shell
    ./vendor/bin/sail artisan db:seed DeveloperSeeder
    ```

   #### O processo de busca dos desenvolvedores no GitHub funciona da seguinte forma:
    - Este processo pode demorar cerca de 1:30h para buscar todos os desenvolvedores.
    - O comando `horizon` inicia o serviço de monitoramento de filas.
    - O comando `horizon:work` inicia o serviço de processamento de filas.
    - O comando `horizon:work --queue=developers-sync` inicia o serviço de processamento de filas que buscam os
      desenvolvedores.
    - O comando `fetch:developers` irá iniciar a busca dos desenvolvedores no GitHub e armazenar no banco de dados.

   #### O processo de carregamento dos desenvolvedores via seeders funciona da seguinte forma:

    - O comando db:seed DeveloperSeeder irá carregar os dados dos desenvolvedores que estão no
      arquivo `database/seeders/jsons/developers.json`.
    - Esse processo é mais rápido, pois os dados já estão no arquivo e não é necessário buscar no GitHub.


7. **Acesse a aplicação**

   Acesse a aplicação em [http://localhost](http://localhost)


8. **Usuários**

    - **Administrador**
        - E-mail: admin@example.com
        - Senha: password
    - **CTO**
        - E-mail: cto@example.com
        - Senha: password


9. **Métricas de Avaliação**

   As métricas usadas para avaliar os desenvolvedores são calculadas com base nos seguintes critérios:

    - **Seguidores**: Número de seguidores no GitHub.
        - Ponderação: 0.1
    - **Repositórios**: Número de repositórios do desenvolvedor.
        - Ponderação: 0.03
    - **Estrelas**: Número total de estrelas que o desenvolvedor recebeu em seus repositórios.
        - Ponderação: 0.2
    - **Commits no último ano**: Número de commits realizados pelo desenvolvedor no último ano.
        - Ponderação: 0.01
    - **Contribuições em repositórios**: Número de repositórios em que o desenvolvedor contribuiu.
        - Ponderação: 0.1

   

   

   

