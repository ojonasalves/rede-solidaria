# 🤝 Rede Solidária

### Conectando pessoas e causas

O **Rede Solidária** é uma plataforma web desenvolvida como projeto acadêmico com o objetivo de aproximar **organizações sociais, voluntários e cidadãos solidários**, facilitando a divulgação e o gerenciamento de demandas sociais.

A proposta é utilizar a tecnologia como ferramenta de apoio à participação comunitária, permitindo que organizações publiquem necessidades e que pessoas interessadas encontrem oportunidades para contribuir.

---

## 🎯 Objetivo

Desenvolver uma plataforma simples, acessível e intuitiva capaz de conectar pessoas dispostas a ajudar com organizações que possuem demandas sociais.

O sistema busca facilitar:

* Divulgação de demandas sociais;
* Participação de cidadãos solidários;
* Gerenciamento de demandas pelas ONGs;
* Comunicação entre organizações e interessados;
* Organização e acompanhamento das participações.

---

## 👥 Público-alvo

O sistema possui dois principais tipos de usuários:

### 🏢 ONG

As organizações podem:

* Criar uma conta;
* Acessar seu painel;
* Publicar novas demandas;
* Editar demandas;
* Encerrar demandas;
* Acompanhar interessados;
* Visualizar informações relacionadas às suas demandas.

### 🙋 Solidário

Os usuários solidários podem:

* Criar uma conta;
* Acessar demandas sociais;
* Pesquisar demandas;
* Filtrar demandas por cidade e categoria;
* Visualizar os detalhes de uma demanda;
* Demonstrar interesse em ajudar;
* Acompanhar suas participações.

---

## ⚙️ Funcionalidades

### Autenticação e usuários

* Cadastro de usuários;
* Login;
* Controle de acesso por tipo de usuário;
* Perfil do usuário;
* Sessão autenticada.

### Demandas sociais

* Cadastro de demandas;
* Título e descrição;
* Categoria;
* Cidade;
* Prazo;
* Status da demanda;
* Edição de demandas;
* Encerramento de demandas.

### Pesquisa

A plataforma permite pesquisar demandas utilizando:

* Palavra-chave;
* Cidade;
* Categoria.

### Participações

O usuário solidário pode demonstrar interesse em uma demanda aberta.

O sistema registra a participação e evita que o mesmo usuário demonstre interesse mais de uma vez na mesma demanda.

### Notificações

Quando um usuário demonstra interesse em uma demanda, a ONG responsável recebe uma notificação informando sobre o novo interessado.

### Controle de status

As demandas possuem os estados:

* **Aberta**
* **Encerrada**

Quando uma demanda é encerrada, ela deixa de aparecer entre as demandas sociais abertas, permanecendo registrada no sistema para histórico e controle da ONG.

---

## 🛠️ Tecnologias utilizadas

O projeto foi desenvolvido utilizando tecnologias web e banco de dados:

* **PHP**
* **MySQL**
* **HTML5**
* **CSS3**
* **SQL**
* **Apache**
* **Git/GitHub**

A aplicação foi hospedada em ambiente web utilizando a infraestrutura da **HostGator**.

---

## 🗄️ Banco de dados

O projeto utiliza o **MySQL** para armazenamento das informações da aplicação.

Entre os principais dados armazenados estão:

* Usuários;
* ONGs;
* Demandas;
* Participações;
* Notificações.

O arquivo de estrutura do banco de dados está disponível em:

```text
database/rede_solidaria.sql
```

---

## 📁 Estrutura do projeto

```text
rede-solidaria/
│
├── admin/
│   └── index.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   └── img/
│       └── logo.png
│
├── config/
│   ├── config.php
│   └── database.php
│
├── database/
│   └── rede_solidaria.sql
│
├── includes/
│   ├── footer.php
│   ├── functions.php
│   └── header.php
│
├── views/
│   ├── cadastro.php
│   ├── demanda.php
│   ├── demandas.php
│   ├── editar-demanda.php
│   ├── home.php
│   ├── interessados.php
│   ├── login.php
│   ├── notificacoes.php
│   ├── nova-demanda.php
│   ├── painel.php
│   ├── participacoes.php
│   └── perfil.php
│
├── .htaccess
└── index.php
```

---

## 🔐 Segurança

O projeto utiliza alguns mecanismos básicos de segurança para controle das operações, incluindo:

* Controle de acesso por tipo de usuário;
* Verificação de sessão;
* Proteção CSRF em operações por formulário;
* Consultas preparadas com PDO;
* Validação de dados recebidos;
* Controle de acesso às demandas pertencentes às respectivas ONGs.

---

## 🚀 Execução do projeto

Para executar o projeto em um ambiente local ou servidor compatível:

1. Disponibilizar um servidor web com suporte a PHP;
2. Criar um banco de dados MySQL;
3. Importar o arquivo:

```text
database/rede_solidaria.sql
```

4. Configurar os dados de conexão no arquivo:

```text
config/database.php
```

5. Configurar as informações da aplicação conforme necessário;
6. Colocar os arquivos no diretório do servidor web;
7. Acessar o sistema pelo navegador.

---

## 🌐 Demonstração

O sistema encontra-se hospedado em ambiente web para demonstração e apresentação do projeto.

**Aplicação:**

https://app.projetoredesolidaria.online/

---

## 📚 Contexto acadêmico

O projeto foi desenvolvido como uma aplicação prática de tecnologia social, buscando aplicar conhecimentos de **desenvolvimento web, banco de dados, autenticação, controle de acesso e desenvolvimento de sistemas** na criação de uma solução voltada para a comunidade.

A plataforma foi planejada para contribuir com a aproximação entre organizações que necessitam de apoio e pessoas interessadas em participar de ações solidárias.

---

## 👨‍💻 Projeto

**Rede Solidária – Conectando pessoas e causas**

Projeto acadêmico desenvolvido para aplicação prática de conceitos de desenvolvimento de sistemas.

---

## 📌 Observação

Este repositório contém o código-fonte utilizado no desenvolvimento da aplicação. Para utilização em produção, recomenda-se revisar as configurações de segurança, credenciais do banco de dados e demais parâmetros do ambiente de hospedagem.
