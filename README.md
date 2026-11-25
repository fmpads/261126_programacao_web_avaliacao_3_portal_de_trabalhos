# Sistema de candidatura a vagas

Um sistema web de candidatura a vagas que permite às empresas criar formulários e aos candidatos se candidatarem online. O sistema oferece perguntas dinâmicas, envio de currículo e notificações por e-mail.

## Recursos

- Crie e gerencie formulários de candidatura dinamicamente.
- Suporte a diferentes tipos de pergunta: texto, área de texto, envio de arquivo, número e seleção.
- Envio de currículo com restrições de tamanho.
- Exibição do logotipo da empresa no formulário de candidatura.
- Notificações automáticas por e-mail, opcionais.
- Estrutura de banco de dados segura e otimizada.
- Interface responsiva baseada em Bootstrap.

## Guia de instalação

### Pré-requisitos

- PHP 7.4 ou posterior
- MySQL ou MariaDB
- Servidor web Apache ou Nginx
- Composer, opcional para dependências
- Bootstrap para a interface

### Configuração

#### Passo 1: Clonar o repositório

```sh
git clone https://github.com/seu-repositorio/sistema-candidatura-vagas.git
cd sistema-candidatura-vagas
```

#### Passo 2: Criar o banco de dados

1. Abra o phpMyAdmin ou o terminal do MySQL.
2. Execute:

```sql
CREATE DATABASE job_application;
```

3. Importe o arquivo `database/job_portal.sql` fornecido no projeto.

#### Passo 3: Configurar a conexão

Edite o arquivo `conn.php`:

```php
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "trabalho";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
```

#### Passo 4: Enviar os arquivos e definir permissões

1. Mova os arquivos do projeto para a raiz do servidor web (`htdocs` ou `public_html`).
2. Garanta permissão de gravação na pasta `uploads/`:

```sh
chmod -R 777 uploads/
```

#### Passo 5: Iniciar o servidor

Com o servidor integrado do PHP:

```sh
php -S localhost:8000
```

Depois, acesse `http://localhost:8000` no navegador.

## Como usar

### Painel administrativo

- Adicione vagas e gerencie as candidaturas.

### Candidato

- Abra o formulário usando um link como `apply.php?form_id=ID_CODIFICADO`.
- Preencha os dados e envie o currículo.
- Envie a candidatura.

## Estrutura do projeto

```
/job-application-system
│── css/                    # Estilos (Bootstrap)
│── js/                     # JavaScript
│── uploads/                # Currículos e arquivos enviados
│── conn.php                # Conexão com o banco de dados
│── index.php               # Página inicial
│── apply.php               # Formulário de candidatura
│── thankyou.php            # Página de confirmação
│── README.md               # Documentação
│── database.sql            # Estrutura do banco de dados
```

## Tecnologias utilizadas

- PHP com MySQLi
- Banco de dados MySQL
- Bootstrap 5
- JavaScript

## Recursos de segurança

- **Validação de entradas:** verifica os dados enviados pelos usuários.
- **Restrições de envio:** limita tipos e tamanhos de arquivos.
- **Prevenção contra SQL injection:** utiliza parâmetros e escape adequados.
- **Proteção contra XSS:** faz o escape das entradas exibidas.

## Contato

Para questões e suporte, contate:
📧 Email: `seu-email@exemplo.com`
🌍 Site: `seusite.com`
