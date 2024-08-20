# OR$E

## Descrição
Este projeto é um site responsivo que tem como objetivo conectar clientes e fornecedores de forma rápida e simples. A plataforma permite que os clientes economizem tempo e se conectem com profissionais adequados ao seu perfil de maneira prática e eficiente.

### Funcionalidades Principais:
- Conexão Rápida: Facilita a conexão entre clientes e fornecedores.
- Perfis Personalizados: Permite que os clientes encontrem profissionais que correspondam ao seu perfil e necessidades específicas.
- Interface Responsiva: Design adaptável para diferentes dispositivos, garantindo uma experiência de usuário consistente em desktops, tablets e smartphones.
- Facilidade de Uso: Interface intuitiva e de fácil navegação para uma experiência de usuário otimizada.

## Pré-requisitos
- [Lando](https://docs.lando.dev/basics/installation.html) - Para criar e gerenciar ambientes de desenvolvimento.
- [Drush](https://www.drush.org/latest/install/) - Ferramenta de linha de comando para gerenciar sites Drupal.

## Instalação
### Lando
1. Instale o Lando seguindo as instruções [aqui](https://docs.lando.dev/basics/installation.html).
2. Clone o repositório do projeto:
    ```sh
    git clone https://github.com/usuario/projeto.git
    cd projeto
    ```
3. Inicie o ambiente Lando:
    ```sh
    lando start
    ```

## Uso
### Sincronizando Bancos de Dados com Drush
1. Para sincronizar o banco de dados do ambiente de produção para o ambiente local, use o comando `drush sql-sync`:
    ```sh
    lando drush sql-sync @dev @local --yes --extra-dump=" | sed '1d'"
    ```
    - `@prod` refere-se ao alias do site de produção.
    - `@dev` refere-se ao alias do site de desenvolvimento.
    - `@self` refere-se ao alias do site local.
