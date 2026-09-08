PHP Data Projects

O'Que é?
É uma extensão nativa do php, que fornece uma interface leve e consistente para banco de dados relacionais

Para que é utilizado PDO?
Funciona como uma camada de abstração, oque significa que pode usá-lo para conectar diferentes bancos de dados, mudando assim apenas a string de conexão.

Como funciona uma conexão utilizando PDO?
Funciona por meio de uma camada leve e consistente de abstração que permite ao PHP acessar diferentes bancos de dados.

Para estabelecer a conexão, você instancia a classe nativa PDO informando o DSN, além do usuário e da senha, deve-se envolver este bloco em um bloco try… catch para tratar possíveis falhas.

Quais as tuas principais características?
É mantido por suas principais características é marcado por sua evolução de uma linguagem focada apenas em páginas web simples para um ecossistema robusto,  capaz de manipular grandes volumes de informação e integrar-se a arquiteturas modernas.

Camada de Abstração robustas.

Ecossistema assíncrono de Alta performance.

Forte integração com APIs e Ferramentas Modernas.

Tipagem Forte e Performance Aprimorada.

Diferenças entre PDO e MySQLi?
O PDO funciona com vários tipos de bancos de dados, enquanto o MySQLi funciona somente para MySQLi e MariaDB.

Suporte a Bancos de Dados.
PDO: É uma camada de abstração que suporta mais de 12 drivers.
MySQLi: Funciona apenas com servidores MySQLi e MariaDB.

Flexibilidade de Migração.
PDO: Facilita a troca de bancos de dados no futuro.
MySQLi: Exige reescrever parte dos acessos aos dados se houver mudança para outro                                sistema de bancos de dados.  

Vantagens e desvantagens de utilizar PDO?
Código aberto e gratuito: não há custo de licenciamento para usar a linguagem ou seus ecossistema

Fácil conexão com bancos: integra-se de forma rápida e eficaz ao MySQL, PostgreSQL, SQLite e Oracle.
Grande Ecossistema: Frameworks como laravel ajudam a estruturar projetos e organizadores e seguros.

Comunidade Ativa: Ampla disponibilidade de bibliotecas, fóruns e documentação em português.
Desvantagens do PHP
Desempenho em dados: Não é a melhor escolha para a manipulação massiva de dados.

Consistência de funções: Algumas funções nativas antigas possuem nomenclaturas e ordens de parâmetros consistentes

Comunidade Ativas: Códigos mal feitos ou mal estruturados feitos no passado ainda mancham a reputação moderna da linguagem.

O que são Prepared Statements e por que são importantes?
Segurança contra SQL injection: Separa o comando SQL dos dados enviados pelo usuário, impedindo assim que entradas maliciosas alterem a lógica da consulta.

Melhoria de desempenho: O banco de dados analisa, compila e otimiza a estrutura do SQL apenas uma vez, utilizando assim o mesmo plano de execução em chamadas repetidas.

Eficiência da rede: Reduz o tráfego de dados, pois a instrução principal não precisa ser retransmitida.

Em quais situações o PDO pode ser uma boa escolha.
Quando você está desenvolvendo aplicações em PHP que interagem com bancos de dados relacionais e precisa de segurança, flexibilidade e código limpo.
