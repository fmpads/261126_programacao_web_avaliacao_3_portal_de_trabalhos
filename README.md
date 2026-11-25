# 26/11/2026 - FACULDADE MUNICIPAL DE PALHOÇA
# TURMA 93. ADS - MAT (2026/2)
# TECNOLÓGICO EM ANÁLISE E DESENVOLVIMENTO DE SISTEMAS
# PROGRAMAÇÃO WEB
# DOCENTE: LEANDRO PICKLER
# DISCENTE: JEAN DOUGLAS TOLEDO RODRIGUES JUNIOR (suporte@jeandouglas.com.br)

# AVALIAÇÃO 3 - SISTEMA DE CANDIDATURA A VAGAS DE TRABALHO

Um sistema web de candidatura a vagas de trabalho que permite aos usuários criar formulários e aos candidatos se candidatarem online. O sistema oferece perguntas dinâmicas e envio de currículo.

## Recursos

- Crie e gerencie formulários de candidatura dinamicamente.
- Suporte a diferentes tipos de pergunta: texto, área de texto, envio de arquivo, número e seleção.
- Envio de currículo com restrições de tamanho.
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
git clone https://github.com/fmpads/261126_programacao_web_avaliacao_3_portal_de_trabalhos
```

#### Passo 2: Criar o banco de dados

1. Abra o phpMyAdmin ou o terminal do MySQL.
2. Execute:

```sql
CREATE DATABASE portaldetrabalhos;
```

3. Importe o arquivo `database/portaldetrabalhos.sql` fornecido no projeto.

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
Email: `suporte@jeandouglas.com.br`
Site: `https://jeandouglas.com.br`