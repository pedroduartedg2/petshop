<?php
// Inclua o arquivo que contém a classe ServicoDao
include_once "./config/conexao.php";
include_once './model/servico.php';
include_once './dao/ServicoDao.php';

// Crie uma instância do ServicoDao
$servicoDao = new ServicoDao();

// Chame o método readAll para obter todos os serviços
$servicos = $servicoDao->readAll();
?>

<div class="flex justify-between">
    <div>
        <h2 class="font-bold text-purple-950 text-2xl">Agendamentos</h2>
        <p class="text-gray-500">Aqui estão os seus agendamentos.</p>
    </div>
    <a href="servico_cadastro.php">
        <button class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md">Cadastrar serviço</button>
    </a>
</div>

<!-- Exibir a lista de serviços em uma tabela -->
<div class="mt-4">
    <?php if ($servicos && !empty($servicos)) : ?>
        <table class="min-w-full bg-white border-gray-100">
            <thead class="text-purple-950">
                <tr>
                    <th class="py-3 border-b border-gray-100 text-left">Nome</th>
                    <th class="py-3 border-b border-gray-100 text-left">Descrição</th>
                    <th class="py-3 border-b border-gray-100 text-left">Preço</th>
                    <th class="py-3 border-b border-gray-100 text-left flex justify-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servicos as $servico) : ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($servico->nome) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($servico->descricao) ?></td>
                        <td class="py-2 border-b border-gray-100">R$ <?= number_format($servico->preco, 2, ',', '.') ?></td>
                        <td class="py-2 border-b border-gray-100 flex justify-end gap-2">
                            <a href="servico_cadastro.php?id=<?= $servico->idServico ?>" class="bg-purple-500 text-white py-1 px-2 rounded hover:bg-purple-600">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="./service/servico_delete.php" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?= $servico->idServico ?>">
                                <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600">

                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="text-neutral-700">Nenhum serviço encontrado.</p>
    <?php endif; ?>
</div>