README Técnico Atualizado (Foco em Escalabilidade)
1. Abstração de Gateways
O Controller foi projetado para ser agnóstico ao gateway. A lógica de processamento externo é encapsulada em classes Service que seguem um contrato implícito:

Entrada: Um $payload padronizado contendo dados do cliente, produto e token.

Saída: Um array $gatewayResult contendo obrigatoriamente status, charge_id e paymentmethod.

2. Como Adicionar um Novo Gateway
Para integrar um novo meio de pagamento (ex: Stripe, PagSeguro):

Crie uma nova classe Service em Microfw\Src\Main\Common\Service\Public\Payment\.

No switch ($type) do Controller, adicione o novo case.

Configure o payment_config_id correspondente às configurações de banco de dados desse novo gateway.

3. Mapeamento Dinâmico de Status
Diferente de sistemas rígidos, este script busca o payment_status_id e payment_method_id dinamicamente no banco através do:

description: O status retornado pelo gateway (ex: "approved", "pending").

payment_config_id: Garante que o status "Aprovado" do Gateway A não se confunda com o do Gateway B.

Fluxo de Decisão (Multi-Gateway)
Pontos de Atenção para Futuros Gateways
Conversão de Unidades: O script atualmente converte o valor para centavos (* 100). Certifique-se de que novos gateways sigam esse padrão ou ajuste a conversão dentro do case específico.

Tratamento de Resposta: A classe EfiPaymentMessageHelper é usada para traduzir o retorno. Para novos gateways, recomenda-se criar Helpers similares para manter as mensagens de erro amigáveis e traduzidas.