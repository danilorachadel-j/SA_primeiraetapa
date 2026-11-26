**O que é STMT**
É a abreviação de Statement que significa no inglês declaração ou instrução que refere-se a um objeto ou variável usada para preparar e executar comandos SQL em bancos de dados de forma segura e eficiente.

**O que é Prepared STMT**
É uma técnica usada para permitir executar, de forma segura, operações SQL que trazem risco de injeção de SQL, é indicado usar isso quando a operação SQL inclui parâmetros(placeholder) com póssiveis dados produzidos pelo usuário, essa técnica é suportada por diversos tipos de SGBDs e linguagens de progamação, sendo algumas delas: PHP, Python, Java, JavaScript/Node.js, C#, Golang, Ruby, C/C++ e Rust, e em relação as quais SGBDs suportam, basicamentes todas as modernas já detém um suporte para isso sendo elas por exemplo: PostgreSQL, MySQL/MariaDB, SQL Server, Oracle Database, SQlite, IBM Db2, Firebird.

**Como funciona na prática**
1. Preparação: Você envia o molde do comando SQL ao banco de dados usando marcadores de posição (? ou :nome) no lugar dos valores reais.
2. Vinculação e Execução (bind / execute): Os dados reais são enviados separadamente e preenchidos nesse marcadores, garantindo que o banco os trate apenas como texto/valores, e nunca como comando executáveis.

**Principais vantagens de usar**
- Segurança: Bloqueia tentativas de invasão via SQL injection.
- Desempenho: Se você precisar rodar a mesma consulta várias vezes(como inserir 1.000 registros em um loop), o banco compila o SQL, apenas uma vez e só troca os parâmetros, tornando o processo muito mais rápido.
- Tratamento automático: Lida automaticamente com aspas e caracteres especiais nos tetos enviados pelo usuário.

**Oque é SQL injection**
É uma falha de segurança em aplicações que usam banco de dados relacionais, permitindo que um invasor insira comando SQL maliciosos por meio de entradas de dados como formula´rios ou parâmentros de URL

**Como se aplica em nossa SA**
Em nossa SA o STMT tem como principais funções a segurança e desempenho, servindo como base para grande parte do projeto.

