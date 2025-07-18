# CheckLista - Sistema de Anotações Online

Um aplicativo simples e eficiente para criar, organizar e gerenciar anotações de forma rápida. Similar ao Google Keep, o CheckLista permite salvar notas, categorizá-las e personalizá-las com cores para melhor organização.

## 🚀 Tecnologias Utilizadas

O projeto utiliza as seguintes tecnologias:

### Front-end
- HTML
- CSS
- JavaScript
- Bootstrap

### Back-end
- PHP

### Banco de Dados
- MySQL

## 🔹 Funcionalidades

✅ Criar, editar e excluir notas facilmente.
✅ Armazenamento seguro no banco de dados (MySQL).
✅ Interface intuitiva.
✅ Gerenciamento eficiente de anotações.

## 🖼️ Interface
| Home |
| :---: |
| ![Imagem da tela inicial do CheckLista](https://github.com/user-attachments/assets/b69e052a-22ed-402f-94d7-a5fd2de985be) |

## 📌 Como Executar o Projeto

### 🔧 Pré-requisitos
Certifique-se de ter um dos ambientes a seguir instalado:

- **Para ambiente local:** Um servidor como XAMPP ou WAMP.
- **Para ambiente containerizado:** Docker e Docker Compose.
- Um navegador atualizado para acessar a interface.

### ▶️ Passos para rodar o projeto

#### Opção 1: Usando XAMPP
1.  Clone o repositório:
    ```sh
    git clone git@github.com:HeloSilvaC/CheckLista.git
    ```
2.  Mova a pasta `CheckLista` para o diretório `htdocs` da sua instalação do XAMPP.
3.  Inicie os módulos Apache e MySQL no painel de controle do XAMPP.
4.  Acesse `http://localhost/phpmyadmin` e crie um banco de dados com o nome `checklista_db`.
5.  Importe o arquivo `.sql` do projeto para o banco de dados recém-criado.
6.  Acesse `http://localhost/CheckLista` no seu navegador.

#### Opção 2: Usando Docker
Com o Docker, o ambiente completo (servidor web + banco de dados) é criado e configurado automaticamente.

1.  Clone o repositório:
    ```sh
    git clone git@github.com:HeloSilvaC/CheckLista.git
    ```
2.  Navegue até a pasta do projeto pelo terminal:
    ```sh
    cd CheckLista
    ```
3.  Execute o Docker Compose para construir e iniciar os containers:
    ```sh
    docker-compose up -d --build
    ```
   * **Explicação do comando:**
      * `docker-compose up`: Lê o arquivo `docker-compose.yml` para criar e iniciar os contêineres.
      * `-d`: Executa os contêineres em modo "detached" (em segundo plano).
      * `--build`: Força a reconstrução das imagens a partir dos arquivos `Dockerfile`, garantindo que quaisquer alterações no código sejam aplicadas.

4.  **Banco de dados:** O Docker Compose irá criar o serviço do MySQL, o banco de dados `checklista_db` e importar os dados do arquivo `.sql` automaticamente na primeira inicialização. Você não precisa fazer nenhuma configuração manual.

5.  Aguarde os containers iniciarem e acesse `http://localhost:8080` (ou a porta que você configurou no arquivo `docker-compose.yml`) no seu navegador.

💡 Desenvolvido por **Heloísa Contrera, Paola Miyuki e Elisa Hiroki** 🚀
