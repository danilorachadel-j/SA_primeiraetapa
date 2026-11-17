# Situação de Aprendizagem (S.A.) 

![Status](https://img.shields.io/badge/Status-Em_Desenvolvimento-yellow)
![License](https://img.shields.io/badge/License-MIT-blue)

##  Sobre o Projeto

Este repositório contém a entrega da Situação de Aprendizagem (S.A.). O objetivo desta fase é estabelecer a fundação da aplicação, abrangendo a estruturação do ambiente, documentação inicial, modelagem do sistema e prototipagem do software.

MANUAL DE DESENVOLVIMENTO DO SISTEMA
1. Introdução e Visão Geral
Este manual tem como objetivo orientar a equipe de desenvolvimento na estrutura, configuração e manutenção do projeto baseado em PHP, Bootstrap e metodologias ágeis (Scrum). O sistema foi planejado para integrar funcionalidades de gerenciamento de rotas, sensores, trens e controle de usuários.
2. Estrutura de Diretórios e Arquivos
A organização do projeto divide-se em pastas principais para garantir a separação de responsabilidades:
Bootstrap/: Contém os arquivos de estilização e componentes de interface (CSS, JS, Fontes) baseados no framework Bootstrap.
DOC/: Documentações técnicas e pesquisas de suporte, incluindo:
pdo.md: Diretrizes para conexão e manipulação de banco de dados via PDO.
pesquisa-apis.md: Levantamento e especificação de APIs.
pesquisa-crud.md: Padrões para operações de Create, Read, Update e Delete.
pesquisa-scrum.md: Organização do fluxo de trabalho ágil.
pesquisa-xampp.md: Guia de configuração do ambiente local de desenvolvimento.
INFRA/: Arquivos de infraestrutura, conexões de banco de dados (conexao.php), layouts base (layout.php) e validadores de sessão/administração (verificar_login.php, verificar_administrador.php).
PUBLIC/: Páginas acessíveis ao usuário final e scripts de manipulação de dados, tais como:
Gerenciamento de cadastros e edições (usuários, trens e sensores).
Telas de controle de rotas.
Módulos específicos de localidade/clima e autenticação (ex: home.php, login.php).
3. Configuração do Ambiente de Desenvolvimento
Para configurar e executar o projeto localmente, siga as etapas abaixo:
1. Ambiente Servidor: Instale e configure o ambiente local utilizando o XAMPP (conforme diretrizes em pesquisa-xampp.md), certificando-se de que os serviços do Apache e do MySQL estejam ativos.
2. Repositório: Clone ou extraia os arquivos do projeto para o diretório raiz do servidor web local (por exemplo, htdocs do XAMPP).
3. Banco de Dados: Configure os parâmetros de conexão no arquivo localizado na pasta INFRA/conexao.php, ajustando host, nome do banco, usuário e senha conforme o SGBD utilizado.
4. Padrões de Desenvolvimento e Segurança
Autenticação e Permissões: Utilize os scripts da pasta INFRA/ (verificar_login.php e verificar_administrador.php) para proteger rotas críticas e assegurar que apenas usuários autenticados ou administradores tenham acesso a determinadas funcionalidades.
Criptografia de Senhas: O gerenciamento de credenciais deve empregar funções nativas robustas do PHP (como password_hash e password_verify, observando o exemplo utilitário de hash em Hasher.php).
Interface Responsiva: O design visual das páginas deve respeitar os componentes da biblioteca Bootstrap incluída no repositório, garantindo compatibilidade multiplataforma.

---

##  Objetivos da Etapa

- [x] Definição de escopo e levantamento de requisitos.
- [x] Estruturação da arquitetura inicial da aplicação.
- [x] Modelagem do banco de dados e diagramas do sistema.
- [x] Configuração e padronização do ambiente de desenvolvimento.
- [ ] Implementação do protótipo/versão inicial.

---

##  Tecnologias e Ferramentas

As tecnologias previstas e utilizadas nesta etapa do projeto incluem:

- **Linguagem / Runtime:** *[ex: JavaScript / Node.js, Python, Java]*
- **Frameworks:** *[ex: React, Express, Spring Boot]*
- **Banco de Dados:** *[ex: PostgreSQL, MySQL, MongoDB]*
- **Controle de Versão:** Git & GitHub
- **Ferramentas de Modelagem:** *[ex: Figma, Draw.io, Lucidchart]*

---

##  Estrutura de Pastas e Arquivos

```text
SA_primeiraetapa/
├── docs/                # Documentação técnica, requisitos e diagramas
├── src/                 # Código-fonte da aplicação
│   ├── assets/          # Arquivos estáticos e mídias
│   ├── components/      # Componentes reutilizáveis
│   └── ...
├── .gitignore           # Arquivos/pastas ignorados pelo Git
├── README.md            # Documentação principal do projeto
└── package.json         # Gerenciamento de dependências (ou equivalente)
