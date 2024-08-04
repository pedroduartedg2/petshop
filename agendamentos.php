<?php
session_start();
include_once "./config/conexao.php";
include_once './model/agendamento.php';
include_once './dao/agendamentoDao.php';
include_once './dao/clienteDao.php';  // Inclua ClienteDao
include_once './model/cliente.php';
include_once './dao/animalDao.php';    // Inclua AnimalDao

// Crie instâncias dos DAOs
$agendamentoDao = new AgendamentoDao();
$clienteDao = new ClienteDao(); // Instância do ClienteDao
$animalDao = new AnimalDao();   // Instância do AnimalDao

// Verifique se o usuário está logado e é um cliente
if (isset($_SESSION["user"]) && $_SESSION["user"]['type'] === "cliente") {
    $idCliente = $_SESSION["user"]['id'];
    // Obtenha apenas os agendamentos do cliente logado
    $agendamentos = $agendamentoDao->readByCliente($idCliente);
} else {
    // Caso contrário, obtenha todos os agendamentos
    $agendamentos = $agendamentoDao->readAll();
}
?>

<div class="flex justify-between">
    <div>
        <h2 class="font-bold text-purple-950 text-2xl">Agendamentos</h2>
        <p class="text-gray-500">Aqui estão os seus agendamentos.</p>
    </div>
    <div class="flex gap-2">
        <a href="relatorio_agendamentos.php">
            <button id="gerarRelatorioBtn" class="text-purple-950 border border-purple-900 py-2 px-4 rounded-md h-fit">Gerar Relatório</button>
        </a>
        <a href="agendamento_cadastro.php">
            <button class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md">Cadastrar agendamento</button>
        </a>
    </div>
</div>

<!-- Exibir a lista de agendamentos em uma tabela -->
<div class="mt-4">
    <?php if ($agendamentos && !empty($agendamentos)) : ?>
        <table class="min-w-full bg-white border-gray-100">
            <thead class="text-purple-950">
                <tr>
                    <th class="py-3 border-b border-gray-100 text-left">Cliente</th>
                    <th class="py-3 border-b border-gray-100 text-left">Animal</th>
                    <th class="py-3 border-b border-gray-100 text-left">Data</th>
                    <th class="py-3 border-b border-gray-100 text-left">Concluído</th>
                    <th class="py-3 border-b border-gray-100 text-left">Total</th>
                    <th class="py-3 border-b border-gray-100 text-left flex justify-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agendamentos as $agendamento) : ?>
                    <?php
                    // Obter os nomes dos clientes e animais
                    $cliente = $clienteDao->readId($agendamento['idCliente']);
                    $animal = $animalDao->readId($agendamento['idAnimal']);
                    $dataHoraFormatada = date('d/m/Y \à\s H:i', strtotime($agendamento['data']));
                    ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($cliente ? $cliente["nome"] : 'Desconhecido') ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($animal ? $animal["nome"] : 'Desconhecido') ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($dataHoraFormatada) ?></td>
                        <td class="py-2 border-b border-gray-100">
                            <?= $agendamento['concluido'] ? '<p class="bg-green-600 flex w-fit rounded-full px-2 py-0 text-sm text-green-50">Sim</p>' : '<p class="bg-red-600 flex w-fit rounded-full px-2 py-0 text-sm text-red-50">Não</p>' ?>
                        </td>
                        <td class="py-2 border-b border-gray-100">R$ <?= number_format($agendamento['total'], 2, ',', '.') ?></td>
                        <td class="py-2 border-b border-gray-100 flex justify-end gap-2">
                            <a href="agendamento_cadastro.php?id=<?= htmlspecialchars($agendamento['idVisita']) ?>" class="bg-purple-500 text-white py-1 px-2 rounded hover:bg-purple-600">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="./service/agendamento_delete.php" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($agendamento['idVisita']) ?>">
                                <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            <form id="emailForm<?= htmlspecialchars($agendamento['idVisita']) ?>" action="./service/enviar_email.php" method="POST" class="inline">
                                <input type="hidden" name="idVisita" value="<?= htmlspecialchars($agendamento['idVisita']) ?>">
                                <button type="submit" class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600">
                                    <i class="fa-solid fa-envelope"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="text-neutral-700">Nenhum agendamento encontrado.</p>
    <?php endif; ?>
</div>