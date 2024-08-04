<?php
session_start();
include_once "header.html";
include_once "./config/conexao.php";
include_once './dao/agendamentoDao.php';
include_once './dao/clienteDao.php';
include_once './dao/animalDao.php';

$agendamentoDao = new AgendamentoDao();
$clienteDao = new ClienteDao();
$animalDao = new AnimalDao();

$agendamentos = [];

$startDate = '';
$endDate = '';

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];

    // Validação básica das datas
    if (DateTime::createFromFormat('Y-m-d', $startDate) && DateTime::createFromFormat('Y-m-d', $endDate)) {
        try {
            $agendamentos = $agendamentoDao->readCompletedInPeriod($startDate, $endDate);
        } catch (Exception $e) {
            echo "Erro ao consultar o banco de dados: " . $e->getMessage();
            $agendamentos = [];
        }
    } else {
        echo "Datas inválidas fornecidas.";
        $agendamentos = [];
    }
}

$totalAgendamentos = count($agendamentos);
$totalValor = array_sum(array_column($agendamentos, 'total'));
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Agendamentos Concluídos</title>
    <!-- Inclua seus estilos CSS aqui -->
</head>

<body class="p-10 flex flex-col">
    <div id="formRelatorio" class="flex flex-col gap-8">
        <h2 class="font-bold text-purple-900 text-2xl">Relatório de Agendamentos Concluídos</h2>
        <form id="formRelatorioData" method="GET" class="flex flex-col gap-2">
            <div class="flex flex-col">
                <label for="dataInicio" class="text-sm">Data de Início *</label>
                <input name="start_date" type="date" class="p-2 rounded-md border border-slate-200" value="<?= htmlspecialchars($startDate) ?>" required>
            </div>
            <div class="flex flex-col">
                <label for="dataFim" class="text-sm">Data de Fim *</label>
                <input name="end_date" type="date" class="p-2 rounded-md border border-slate-200" value="<?= htmlspecialchars($endDate) ?>" required>
            </div>
            <div class="flex flex-row justify-between mt-2">
                <button id="btCancelarRelatorio" type="button" class="py-2 px-4 text-purple-950 border border-purple-950 font-semibold rounded-md">Cancelar</button>
                <button type="submit" class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md">Gerar Relatório</button>
            </div>
        </form>
        <div id="mensagemRelatorio" class="text-slate-700 text-center mt-2"></div>
    </div>

    <div class="mt-4">
        <?php if (!empty($agendamentos)) : ?>
            <button onclick="window.print()" class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md mb-4">Imprimir Relatório</button>
            <table class="min-w-full bg-white border-gray-100">
                <thead class="text-purple-950">
                    <tr>
                        <th class="py-3 border-b border-gray-100 text-left">Cliente</th>
                        <th class="py-3 border-b border-gray-100 text-left">Animal</th>
                        <th class="py-3 border-b border-gray-100 text-left">Data</th>
                        <th class="py-3 border-b border-gray-100 text-left">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $agendamento) : ?>
                        <?php
                        $cliente = $clienteDao->readId($agendamento['idCliente']);
                        $animal = $animalDao->readId($agendamento['idAnimal']);
                        $dataFormatada = DateTime::createFromFormat('Y-m-d H:i:s', $agendamento['data'])->format('d/m/Y \à\s H:i');
                        ?>
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($cliente ? $cliente["nome"] : 'Desconhecido') ?></td>
                            <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($animal ? $animal["nome"] : 'Desconhecido') ?></td>
                            <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($dataFormatada) ?></td>
                            <td class="py-2 border-b border-gray-100">R$ <?= number_format($agendamento['total'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-bold text-purple-900">
                        <td class="py-2 border-t border-gray-100 text-left" colspan="3">Total de Agendamentos</td>
                        <td class="py-2 border-t border-gray-100 text-left"><?= $totalAgendamentos ?></td>
                    </tr>
                    <tr class="font-bold text-purple-900">
                        <td class="py-2 border-t border-gray-100 text-left" colspan="3">Valor Total</td>
                        <td class="py-2 border-t border-gray-100 text-left">R$ <?= number_format($totalValor, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php else : ?>
            <p class="text-neutral-700">Nenhum agendamento encontrado para o período selecionado.</p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btCancelarRelatorio = document.getElementById('btCancelarRelatorio');
            btCancelarRelatorio.addEventListener('click', function() {
                window.location.href = "index.php";
            });
        });
    </script>
</body>

</html>