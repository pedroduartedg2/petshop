<?php
session_start();
// Inclua o arquivo que contém as classes e métodos necessários
include_once "./config/conexao.php";
include_once './model/animal.php';
include_once './dao/AnimalDao.php';
include_once './dao/ClienteDao.php';

// Crie instâncias dos DAOs
$animalDao = new AnimalDao();
$clienteDao = new ClienteDao();

// Verifique o tipo de usuário na sessão
$isCliente = isset($_SESSION["user"]) && $_SESSION["user"]["type"] === "cliente";
$clienteId = $isCliente ? $_SESSION["user"]["id"] : null;

// Chame o método readAll para obter todos os animais, ou readByCliente se for um cliente
if ($isCliente) {
    $animais = $animalDao->readByCliente($clienteId);
} else {
    $animais = $animalDao->readAll();
}
?>

<div class="flex justify-between">
    <div>
        <h2 class="font-bold text-purple-950 text-2xl">Animais</h2>
        <p class="text-gray-500">Aqui estão os seus animais.</p>
    </div>
    <a href="animal_cadastro.php">
        <button class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md">Cadastrar animal</button>
    </a>
</div>

<!-- Exibir a lista de animais em uma tabela -->
<div class="mt-4">
    <?php if ($animais && !empty($animais)) : ?>
        <table class="min-w-full bg-white border-gray-100">
            <thead class="text-purple-950">
                <tr>
                    <th class="py-3 border-b border-gray-100 text-left">Nome</th>
                    <th class="py-3 border-b border-gray-100 text-left">Peso</th>
                    <th class="py-3 border-b border-gray-100 text-left">Nascimento</th>
                    <th class="py-3 border-b border-gray-100 text-left">Cor</th>
                    <th class="py-3 border-b border-gray-100 text-left">Observação</th>
                    <?php if (!$isCliente) : ?>
                        <th class="py-3 border-b border-gray-100 text-left">Cliente</th>
                    <?php endif; ?>
                    <th class="py-3 border-b border-gray-100 text-left flex justify-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animais as $animal) : ?>
                    <?php
                    // Busca o nome do cliente, se não for um cliente logado
                    if (!$isCliente) {
                        $clienteNome = $clienteDao->getClienteNomeById($animal->idCliente);
                    }
                    ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($animal->nome) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($animal->peso) ?> kg</td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars(date('d/m/Y', strtotime($animal->nascimento))) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($animal->cor) ?></td>
                        <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($animal->observacao) ?></td>
                        <?php if (!$isCliente) : ?>
                            <td class="py-2 border-b border-gray-100"><?= htmlspecialchars($clienteNome) ?></td>
                        <?php endif; ?>
                        <td class="py-2 border-b border-gray-100 flex justify-end gap-2">
                            <a href="animal_cadastro.php?id=<?= $animal->idAnimal ?>" class="bg-purple-500 text-white py-1 px-2 rounded hover:bg-purple-600">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="./service/animal_delete.php" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?= $animal->idAnimal ?>">
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
        <p class="text-neutral-700">Nenhum animal encontrado.</p>
    <?php endif; ?>
</div>