## APIs (Application Programming Interfaces)

**O que é uma API e qual é sua finalidade?**
APIs são mecanismos que permitem que dois componentes de software se comuniquem usando um conjunto de definições e protocolos, serve para a integração de serviços, aceleração do desenvolvimento, segurança de dados, comparação com o tempo real, pagamentos online, ele no geral funciona como um tradutor entre dois sistemas: um aplicativo faz o pedido a API entrega esse pedido a outro sistema.

**Como funciona a comunicação entre sistemas por meio de APIs?**
O sistema funciona por meio de requisição e resposta.

_Aplicação solicitante_: É o sistema que precisa de um dado ou de uma funcionalidade. 
_APIs_: Recebe o pedido, traduz essa solicitação em regras que o sistema de origem compreenda e leva o pedido até ele.
_Banco de dados/ Sistema de origem_: Processa o pedido e devolve a resposta para a API.
_O retorno_: A APIs pega o resultado do banco de dados e entrega de volta para o cliente em formato limpo e legível.

**O que são APIs REST?**
É um interface que permite a comunicação entre diferentes sistemas  utilizando protocolo HTTP, porém ele se baseia no estilo arquitetônico REST(Representational State Transfer) além de servir para a integração de sistemas, ele também serve para a separação de camadas permitindo que o Front-end e que o Back-End sejam atualizados e desenvolvido de forma independente.

O papel dos métodos HTTP, como GET, POST, PUT, PATCH e DELETE.

_GET_ - Consultar informações.
_POST_ - Criar um novo recurso.
_PUT_ - Substituir completamente um recurso.
_PATCH_ - Modificar parcialmente um recurso.
_DELETE_ - Excluir um recurso.

Os métodos HTTP definem qual operação um cliente deseja realizaram se comunicar com o servidor por uma API.

**O são requisições e respostas HTTP?**
São mensagens trocadas entre o cliente(navegador, navegador, aplicativo, etc)e um servidor web para permitir a navegação e o funcionamento de sites e sistemas da internet. 

**O que são endpoints?**
São endereços específicos de uma API utilizados para acessar recursos ou executar operações em um sistema, isso também é conhecido com URL onde uma aplicação cliente pode acessar um recurso ou realizar uma ação.

**Como funciona a troca de dados no formato JSON?**
 
_Serialização_:  O sistema de origem, pega um objeto interno da linguagem e o converte em uma string de texto no padrão JSON

_Envio pela rede_: Essa string de texto é transmitida via HTTP. codificada em UTF-8

_Recepção e Desserialização_: O sistema de destino recebe o texto e o converte de volta em um objeto nativo da sua própria linguagem de programação

**Diferenças entre uma API e um sistema web tradicional?**
A API é um conjunto de regras que permite que diferentes softwares se comuniquem entre si, enquanto um sistema web tradicional é um aplicação completa voltada para o usuário final, visualizada diretamente através de um navegador.

As principais diferenças entre os dois são no público alvo por exemplo já que o foco da API é em máquina para máquina enquanto o sistema web tradicional é focado no ser humano.

**Como uma aplicação PHP pode consumir uma API?**
Uma aplicação PHP pode consumir uma API enviando requisições HTTP para obter ou enviar dados  a outro sistema. Isso permite integrar serviços externos, como previsão do tempo, pagamentos, consultas de CEP e sistema de cadastro.

-Definir o endpoint
-Enviar uma requisição HTTP
-Receber a resposta
-Interpretar os dados
-Exibir ou utilizar os resultados

**Como uma API pode disponibilizar informações para outros sistemas?**
De que maneira uma API pode compartilhar dados com outros sistemas?Uma API compartilha informações através de pontos de acesso (endpoints), que autorizam outros sistemas a encaminhar requisições HTTP para buscar ou gerenciar dados, de acordo com as autorizações concedidas.

_Oferecimento_: o programador estabelece pontos de acesso que possibilitam a consulta de determinados dados.
_Solicitação_: um sistema externo envia um pedido usando um protocolo HTTP, como o GET.
_Execução_: a API verifica o banco de dados ou outra origem de dados.
_Retorno_: a API envia de volta as informações requisitadas, usualmente no formato JSON.
_Emprego_: o sistema que recebeu os dados pode apresentá-los ou aplicá-los em suas próprias capacidades.

**Importância da autenticação, autorização e segurança no consumo de APIs**
A verificação verifica quem você é em relação ao sistema, verificando o que você pode ou não fazer no sistema, e a segurança geral protege dados e canais. Juntas, elas evitam vazamentos de dados, roubo de identidade e acessos indevidos em ecossistemas conectados.

_Autenticação_: A autenticação é o processo de verificar a identidade de um usuário ou sistema autenticador pode acessar.
_Autorização_: A autorização determina quais recursos e operações um usuário ou sistema pode acessar.
_Segurança_: A segurança envolve medidas que protegem as informações durante a comunicação e o processamento dos dados.

**Exemplos de utilização de APIs em sistemas empresariais.**
Elas integram ferramentas como ERPs, CRMs, gateways de pagamento, plataformas de e-commerce e assistentes de inteligência artificial, permitindo a troca de dados em tempo real sem intervenção manual.

_Principais exemplos._

_Sistema financeiros_: Integração com bancos para consultar saldos, realizar pagamentos e verificar transações.
_Comércio eletrônico_: Comunicação entre lojas virtuais, sistemas de estoque e plataformas de pagamento.
_Gestão empresarial_: Compartilhamento de informações entre os setores financeiro, comercial, de vendas e estoque.
_Logística_: Consulta de fretes, rastreamento de encomendas e atualização de status das entregas.
_Atendimento ao cliente_: Integração com sistemas de mensagens, chatbots e plataformas de suporte.
_Recursos humanos_: Integração entre sistemas de folha de pagamento, controle de ponto e gestão de funcionários.

