<?php
// Inclua o arquivo que contém a classe ServicoDao
include_once "./config/conexao.php";
include_once './model/cliente.php';
include_once './dao/ClienteDao.php';

// Crie uma instância do ServicoDao
$clienteDao = new ClienteDao();

// Chame o método readAll para obter todos os serviços
$clientes = $clienteDao->readAll();
?>

<div class="flex justify-between">
    <div>
        <h2 class="font-bold text-purple-950 text-2xl">Clientes</h2>
        <p class="text-gray-500">Aqui estão os seus clientes.</p>
    </div>
    <a href="cliente_cadastro.php">
        <button class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md">Cadastrar cliente</button>
    </a>
</div>

<!-- Exibir a lista de serviços em uma tabela -->
<div class="mt-4">
    <?php if ($clientes && !empty($clientes)) : ?>
        <table class="min-w-full bg-white border-gray-100">
            <thead class="text-purple-950">
                <tr>
                    <th class="py-3 border-b border-gray-100 text-left">Nome</th>
                    <th class="py-3 border-b border-gray-100 text-left">E-mail</th>
                    <th class="py-3 border-b border-gray-100 text-left">Celular</th>
                    <th class="py-3 border-b border-gray-100 text-left">CPF</th>
                    <th class="py-3 border-b border-gray-100 text-left flex justify-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente) : ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($cliente->nome) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($cliente->email) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($cliente->telefone1) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($cliente->cpf) ?></td>
                        <td class="py-2 border-b border-gray-100 flex justify-end gap-2">
                            <a href="cliente_cadastro.php?id=<?= $cliente->idCliente ?>" class="bg-purple-500 text-white py-1 px-2 rounded hover:bg-purple-600">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="./service/cliente_delete.php" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?= $cliente->idCliente ?>">
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
        <p class="text-neutral-700">Nenhum cliente encontrado.</p>
    <?php endif; ?>
</div>