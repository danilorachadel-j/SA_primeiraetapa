O que é XAMPP?

O objetivo do XAMPP é criar uma distribuição fácil de instalar para que desenvolvedores possam entrar no mundo do Apache. Para facilitar o trabalho dos desenvolvedores, o XAMPP já vem configurado com todos os recursos ativados.


Principais componentes do ambiente:


Apache
Módulos de Processamento (MPM): Controlam como o servidor lida com requisições simultâneas. Os principais são o mpm_prefork e mpm_worker.
Arquivos de Configuração: O arquivo principal é o httpd.conf, onde ficam definidas as portas, segurança e os diretórios do site. O DocumentRoot é a pasta onde o servidor busca os arquivos do site.
Módulos Dinâmicos: Permitem adicionar funcionalidades como suporte a SSL/TLS (criptografia), reescrita de URL ou integração com outras linguagens.


MySQL/MariaDB
o Servidor (Camada Lógica) responsável pela conexão e pelo processamento de consultas; o Motor de Armazenamento (Camada Física) encarregado de ler e gravar dados nos discos; e o conjunto de Arquivos e Logs usados para armazenamento e recuperação. 


PHP
Interpretador PHP: O "cérebro" que lê e converte os scripts em código de máquina para processar a lógica no servidor.
Servidor Web: Responsável por receber as requisições HTTP e encaminhá-las ao PHP.
Banco de Dados: Onde as informações da aplicação são armazenadas e consultadas, sendo MySQL e PostgreSQL os mais comuns. [1, 2]


phpMyAdmin
phpMyAdmin é uma aplicação de fácil utilização que serve para controlar seu banco de dados MySql. Você pode utilizá-lo para criar, copiar, deletar, renomear e alterar tabelas, fazer a manutenção de tabelas, deletar, editar e adicionar campos, exportar ou importar um banco de dados, e muito mais.


Como realizar a instalação e configuração básica
O XAMPP é um pacote que transforma seu computador em um servidor local, instalando de uma só vez o Apache, o MySQL e o PHP.
1. Instalação Rápida
Baixe o instalador no site oficial da Apache Friends.
Avance as telas do instalador ("Next") e mantenha a pasta padrão de instalação.
2. Ativação
Abra o Painel de Controle do XAMPP e clique em "Start" ao lado de Apache e MySQL. Eles devem ficar com o fundo verde.
Para testar se deu certo, abra o navegador e digite localhost.
3. Onde colocar seus projetos?
Todos os seus arquivos  devem ser salvos dentro da pasta htdocs.
Exemplo: Se você criar a pasta C:\xampp\htdocs\projeto1, para acessá-la no navegador basta digitar localhost/projeto1.
Para gerenciar seus bancos de dados de forma visual, basta acessar localhost/phpmyadmin no seu navegador.
Importância do ambiente para o desenvolvimento local:
É essencial para criar, testar e depurar aplicações web  diretamente na sua máquina, sem a necessidade de pagar por hospedagem ou correr o risco de quebrar um site que já está no ar.