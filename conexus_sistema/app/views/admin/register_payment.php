<?php

$idaluno = $_GET['idaluno'] ?? null;

if (!$idaluno) {
    echo "Aluno não especificado.";
    exit();
}

require_once __DIR__ . '../../../config/conexao.php';
require_once __DIR__ . '../../../models/forma_pagamento.php';
require_once __DIR__ . '../../../models/aluno.php'; 

$formasPagamentoModel = new FormaPagamento(); 
$alunoModel = new Aluno();

$idusuario = $_SESSION['idusuario'] ?? null;

$formasPagamento = $formasPagamentoModel->listarTodos();

$modoEdicao = false;
$item = []; 
$idaluno_selecionado = null;
$alunos = [];

if (isset($_GET['idaluno'])) {
    $idaluno_selecionado = filter_var($_GET['idaluno'], FILTER_SANITIZE_NUMBER_INT);
    // se veio da view anterior com o id do aluno, traz só ele
    $aluno = $alunoModel->buscarPorId($idaluno_selecionado);
    if ($aluno) {
        $alunos[] = $aluno;
    }
} elseif ($idusuario) {
    // caso contrário, busca todos os alunos do usuário logado
    $alunos = $alunoModel->buscarIdPorUsuario($idusuario);
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conexus - Cadastrar Pagamento</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/admin_style.css">

    </head>
<body>

<?php include '../components/admin_header.php'; ?>
<section class="form-container">

    <form method="POST" action="/escola-de-idiomas/conexus_sistema/app/controllers/pagamento_controller.php">
    <input type="hidden" name="acao" value="cadastrar_pagamento">
    <input type="hidden" name="idaluno" value="<?= htmlspecialchars($idaluno) ?>">

        <div class="flex">
            <div class="col">
                <p><strong>Valor do Pagamento <span>*</span></strong></p>
                <input type="number" name="valor" min="0" step="0.01" required placeholder="Ex: 150.00" class="box"
                value="<?= $modoEdicao ? htmlspecialchars($item['valor']) : '' ?>">

                <p><strong>Data de Vencimento <span>*</span></strong></p>
                <input type="date" name="data_vencimento" required class="box"
                value="<?= $modoEdicao && isset($item['data_vencimento']) ? htmlspecialchars($item['data_vencimento']) : '' ?>">

                <p><strong>Forma de Pagamento <span>*</span></strong></p>
                <select name="idforma_pagamento" class="box" id="formaPagamento" onchange="mostrarCamposPagamento()">
                    <option value="">Selecione a forma de pagamento</option>
                <?php foreach ($formasPagamento as $forma): ?>
                    <option value="<?= htmlspecialchars($forma['forma_pagamento']) ?>" 
                        <?= $modoEdicao && $item['forma_pagamento'] == $forma['forma_pagamento'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($forma['forma_pagamento']) ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <div id="campoDinheiro" class="campo-pagamento">
                    <p><strong>Valor Recebido (Dinheiro)</strong></p>
                    <input type="number" name="valor_recebido_dinheiro" min="0" step="0.01" placeholder="Valor recebido" class="box">
                    <p><strong>Troco (Dinheiro)</strong></p>
                    <input type="text" name="troco_dinheiro" placeholder="Será calculado automaticamente" class="box" readonly>
                </div>

                <div id="campoBoleto" class="campo-pagamento">
                    <p><strong>Número do Boleto</strong></p>
                    <input type="text" name="numero_boleto" maxlength="255" placeholder="Número de referência do boleto" class="box">
                    <p><strong>Data de Vencimento do Boleto</strong></p>
                    <input type="date" name="vencimento_boleto" class="box">
                </div>

                <div id="campoCartão de Crédito" class="campo-pagamento">
                    <p><strong>Nome no Cartão (Crédito)</strong></p>
                    <input type="text" name="nome_cartao_credito" maxlength="255" placeholder="Nome impresso no cartão" class="box">

                    <p><strong>Bandeira do Cartão (Crédito)</strong></p> 
                    <input type="text" name="bandeira_cartao_credito" maxlength="20" placeholder="Ex: Visa, Mastercard" class="box">

                    <p><strong>Últimos Dígitos do Cartão (Crédito)</strong></p> 
                    <input type="text" name="ultimos_digitos_cartao_credito" maxlength="4" placeholder="Apenas os 4 últimos dígitos" class="box">

                    <p><strong>Parcelas (Crédito)</strong></p>
                    <input type="number" name="parcelas_credito" min="1" max="12" value="1" class="box">
                </div>

                <div id="campoCartaoDebito" class="campo-pagamento">
                    <p><strong>Nome no Cartão (Débito)</strong></p>
                    <input type="text" name="nome_cartao_debito" maxlength="255" placeholder="Nome impresso no cartão" class="box">

                    <p><strong>Bandeira do Cartão (Débito)</strong></p> 
                    <input type="text" name="bandeira_cartao_debito" maxlength="20" placeholder="Ex: Visa, Mastercard" class="box">

                    <p><strong>Últimos Dígitos do Cartão (Débito)</strong></p> 
                    <input type="text" name="ultimos_digitos_cartao_debito" maxlength="4" placeholder="Apenas os 4 últimos dígitos" class="box">
                </div>

                <div id="campoPix" class="campo-pagamento">
                    <p><strong>Chave Pix Utilizada</strong></p>
                    <input type="text" name="chave_pix" maxlength="255" placeholder="CPF, CNPJ, Telefone, E-mail ou Chave Aleatória" class="box">

                    <p><strong>ID da Transação Pix (opcional)</strong></p>
                    <input type="text" name="id_transacao_pix" maxlength="255" placeholder="ID único da transação Pix" class="box">
                </div>

                <p><strong>Observações (Opcional)</strong></p>
                <textarea name="observacoes" class="box" rows="3" placeholder="Informações adicionais sobre o pagamento"></textarea>
            </div>
        </div>

        <input type="submit" value="Registrar Pagamento" class="btn">

    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>
<script>
    // mostrar campos  pagamento
    function mostrarCamposPagamento() {
        const select = document.getElementById('formaPagamento');
        const selectedOption = select.options[select.selectedIndex];
        const tipo = selectedOption.getAttribute('value');

        //const formaPagamento = document.getElementById('formaPagamento').value;
        const campos = document.querySelectorAll('.campo-pagamento');


        campos.forEach(campo => {
            campo.style.display = 'none';
        });


        if (tipo === 'Dinheiro') {
            document.getElementById('campoDinheiro').style.display = 'block';
        } else if (tipo === 'Boleto') {
            document.getElementById('campoBoleto').style.display = 'block';
        } else if (tipo === 'Cartao de Credito') {
            document.getElementById('campoCartaoCredito').style.display = 'block';
        } else if (tipo === 'Cartão de Debito') {
            document.getElementById('campoCartaoDebito').style.display = 'block';
        } else if (tipo === 'Pix') {
            document.getElementById('campoPix').style.display = 'block';
        }
    }

    // calcula o troco se for dinheiro
    document.querySelector('input[name="valor_recebido_dinheiro"]').addEventListener('input', function() {
        const valorInput = document.querySelector('input[name="valor_pagamento"]');
        const valorPagamento = valorInput ? (parseFloat(valorInput.value) || 0) : 0;
        
        const valorRecebido = parseFloat(this.value) || 0;
        const troco = valorRecebido - valorPagamento;
        document.querySelector('input[name="troco_dinheiro"]').value = troco.toFixed(2);
    });

    //  carregar a página para 
    document.addEventListener('DOMContentLoaded', mostrarCamposPagamento);
</script>

</body>
</html>