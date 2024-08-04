<?php
// Inclua o arquivo que contém a classe ServicoDao
include_once "./config/conexao.php";
include_once './model/funcionario.php';
include_once './dao/FuncionarioDao.php';

// Crie uma instância do ServicoDao
$funcionarioDao = new FuncionarioDao();

// Chame o método readAll para obter todos os serviços
$funcionarios = $funcionarioDao->readAll();
?>

<div class="flex justify-between">
    <div>
        <h2 class="font-bold text-purple-950 text-2xl">Funcionários</h2>
        <p class="text-gray-500">Aqui estão os seus funcionários.</p>
    </div>
    <a href="funcionario_cadastro.php">
        <button class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md">Cadastrar cliente</button>
    </a>
</div>

<!-- Exibir a lista de serviços em uma tabela -->
<div class="mt-4">
    <?php if ($funcionarios && !empty($funcionarios)) : ?>
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
                <?php foreach ($funcionarios as $funcionario) : ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($funcionario->nome) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($funcionario->email) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($funcionario->telefone1) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($funcionario->cpf) ?></td>
                        <td class="py-2 border-b border-gray-100 flex justify-end gap-2">
                            <a href="funcionario_cadastro.php?id=<?= $funcionario->idFuncionario ?>" class="bg-purple-500 text-white py-1 px-2 rounded hover:bg-purple-600">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="./service/funcionario_delete.php" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?= $funcionario->idFuncionario ?>">
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
        <p class="text-neutral-700">Nenhum funcionário encontrado.</p>
    <?php endif; ?>
</div>