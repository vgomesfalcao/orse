# Arquitetura do Projeto

Este documento detalha a arquitetura do projeto, incluindo as bibliotecas e frameworks utilizados, tanto no backend quanto no frontend.

## Backend (PHP/Drupal)

O backend é construído sobre o **Drupal 10**. A gestão de pacotes PHP é feita através do **Composer**.

### Principais Dependências (do `composer.json`):

- **Core:**
  - `drupal/core-recommended`: ^10
  - `drush/drush`: ^12

- **Módulos Contrib (Principais):**
  - `drupal/commerce`: ^2.39 (Módulo de E-commerce)
  - `drupal/webform`: ^6.2 (Criação de formulários)
  - `drupal/paragraphs`: ^1.12 (Criação de conteúdo estruturado)
  - `drupal/gin`: ^4.0 (Tema de administração)
  - `drupal/radix`: ^5.0 (Tema base para o frontend)
  - `drupal/clientside_validation`: ^4.0 (Validação de formulários no lado do cliente)
  - `drupal/mautic`: ^1.11 (Integração com Mautic)
  - `drupal/smtp`: ^1.2 (Envio de e-mails via SMTP)

- **Bibliotecas PHP:**
  - `commerceguys/intl`: ^2 (Biblioteca de internacionalização)
  - `cweagans/composer-patches`: ^1.7 (Para aplicar patches em dependências)

### Estrutura de Diretórios (definida no `composer.json`):

- **Core do Drupal:** `web/core`
- **Bibliotecas:** `web/libraries/{$name}`
- **Módulos Contrib:** `web/modules/contrib/{$name}`
- **Módulos Customizados:** `web/modules/custom/{$name}`
- **Temas Contrib:** `web/themes/contrib/{$name}`
- **Temas Customizados:** `web/themes/custom/{$name}`

## Frontend (Tema `orse`)

O tema customizado `orse` está localizado em `web/themes/custom/orse`. Ele é um subtema do **Radix**. A gestão de pacasets frontend é feita através do **NPM**.

### Principais Dependências (do `package.json`):

- **Frameworks e Bibliotecas:**
  - `bootstrap`: ^5.2.0 (Framework CSS)
  - `@popperjs/core`: ^2.9.2 (Para posicionamento de tooltips e popovers do Bootstrap)

- **Ferramentas de Build e Desenvolvimento:**
  - `laravel-mix`: ^6.0.18 (Wrapper para Webpack)
  - `sass`: ^1.63.6 (Pré-processador CSS)
  - `stylelint`: ^14.1.0 (Linter para CSS/Sass)
  - `autoprefixer`: 10.4.5 (Adiciona prefixos de fornecedores ao CSS)
  - `browser-sync`: ^2.11.2 (Sincronização de navegadores para desenvolvimento)

### Scripts NPM:

- `dev`: Compila os assets para desenvolvimento.
- `watch`: Observa as alterações nos arquivos e recompila automaticamente.
- `production`: Compila e minifica os assets para produção.
- `stylint`: Executa o linter nos arquivos SCSS.

## Tema Radix 5 e Subtemas

O tema **Radix 5** é um tema base para Drupal que integra o **Bootstrap 5** e segue uma abordagem de desenvolvimento baseada em componentes.

### Características do Radix 5:

- **Baseado em Bootstrap 5:** Oferece todos os recursos e componentes do Bootstrap 5 para um desenvolvimento frontend rápido e responsivo.
- **Component-driven:** Incentiva a organização do código em componentes reutilizáveis, facilitando a manutenção e escalabilidade.
- **Ferramentas de Desenvolvimento:** Utiliza `laravel-mix` para compilação de assets (Sass para CSS, JavaScript), `BrowserSync` para sincronização de navegador e `Stylelint` para linting de CSS.
- **Módulo `Components`:** Para Radix 5.x, o módulo `drupal/components` é um requisito para o funcionamento adequado da estrutura de componentes.

### Como funcionam os Subtemas Radix:

Subtemas são a maneira recomendada de personalizar um tema base como o Radix, sem modificar diretamente o código do tema pai. Isso garante que as atualizações do tema base possam ser aplicadas sem perder as personalizações.

- **Criação:** Um subtema Radix é geralmente criado usando o comando Drush `drush radix:create [SUBTHEME_NAME]`.
- **Herança:** O subtema herda todos os estilos, scripts e templates do tema base Radix.
- **Personalização:**
    - **SCSS:** Os arquivos SCSS do subtema podem sobrescrever ou estender os estilos do tema base. O `package.json` do subtema contém scripts para compilar esses arquivos.
    - **Templates:** Templates Twig (`.html.twig`) podem ser sobrescritos no diretório do subtema para alterar a marcação HTML.
    - **Componentes:** A estrutura de componentes permite sobrescrever componentes específicos, colocando arquivos modificados no diretório `components` do subtema.
- **Fluxo de Trabalho de Desenvolvimento:**
    1. Instalar dependências Node.js (`npm install`) no diretório do subtema.
    2. Usar `npm run watch` para compilar assets e observar mudanças durante o desenvolvimento.
    3. Usar `npm run production` para gerar assets otimizados para o ambiente de produção.