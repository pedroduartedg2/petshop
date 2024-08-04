<?php
session_start();
include_once "header.html";
include_once "./config/conexao.php";
include_once "model/animal.php";
include_once "dao/AnimalDao.php";
include_once "model/cliente.php";  // Inclua o modelo de Cliente
include_once "dao/ClienteDao.php";  // Inclua o DAO de Cliente
include_once "config/utils.php";

// Verifica se um ID foi passado na URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$animalDao = new AnimalDao();
$animal = null;

if ($id) {
    // Busca os dados do animal se o ID for válido
    $animal = $animalDao->readId($id);
}

// Crie uma instância do ClienteDao e busque todos os clientes
$clienteDao = new ClienteDao();
$clientes = $clienteDao->readAll();

// Verifica se o usuário logado é um cliente
$isCliente = isset($_SESSION["user"]) && $_SESSION["user"]["type"] === "cliente";
$clienteId = $isCliente ? $_SESSION["user"]["id"] : null;
?>

<div class="h-[calc(100vh-71.75px)] flex flex-col gap-2 p-10 items-center justify-center">
    <div class="flex gap-6 flex-col w-1/3">
        <h1 class="font-bold text-purple-900 text-2xl"><?= $id ? 'Editar Animal' : 'Cadastro de Animal' ?></h1>
        <form id="animalForm" method="POST" class="flex flex-col gap-2">
            <input type="hidden" name="idAnimal" value="<?= $id ? htmlspecialchars($animal['idAnimal']) : '' ?>">

            <div class="flex flex-col">
                <label for="nome" class="text-sm">Nome *</label>
                <input name="nome" type="text" placeholder="Nome do animal" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($animal['nome']) : '' ?>">
            </div>
            <div class="flex flex-col">
                <label for="peso" class="text-sm">Peso *</label>
                <input name="peso" type="number" step="0.01" min="0" placeholder="Peso do animal" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($animal['peso']) : '' ?>">
            </div>
            <div class="flex flex-col">
                <label for="nascimento" class="text-sm">Nascimento *</label>
                <input name="nascimento" type="date" placeholder="Data de nascimento" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($animal['nascimento']) : '' ?>">
            </div>
            <div class="flex flex-col">
                <label for="cor" class="text-sm">Cor *</label>
                <input name="cor" type="text" placeholder="Cor do animal" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($animal['cor']) : '' ?>">
            </div>
            <div class="flex flex-col">
                <label for="observacao" class="text-sm">Observação</label>
                <textarea name="observacao" placeholder="Observações adicionais" class="p-2 rounded-md border border-slate-200"><?= $id ? htmlspecialchars($animal['observacao']) : '' ?></textarea>
            </div>
            <?php if ($isCliente) : ?>
                <input type="hidden" name="idCliente" value="<?= htmlspecialchars($clienteId) ?>">
            <?php else : ?>
                <div class="flex flex-col">
                    <label for="idCliente" class="text-sm">Cliente *</label>
                    <select name="idCliente" class="p-2 rounded-md border border-slate-200" required>
                        <option value="" disabled <?= !$id ? 'selected' : '' ?>>Selecione um cliente</option>
                        <?php foreach ($clientes as $cliente) : ?>
                            <option value="<?= htmlspecialchars($cliente->getIdCliente()) ?>" <?= $id && $animal['idCliente'] == $cliente->getIdCliente() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cliente->getNomeCliente()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="flex flex-row justify-between mt-2">
                <button id="btVoltar" name="btVoltar" type="button" class="py-2 px-4 text-purple-950 border border-purple-950 font-semibold rounded-md">Cancelar</button>
                <button name="btCreate" type="submit" class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md"><?= $id ? 'Atualizar Animal' : 'Cadastrar Animal' ?></button>
            </div>
        </form>
        <div id="mensagem" class="text-slate-700 text-center"></div>
    </div>
</div>

<script>
    document.getElementById("btVoltar").addEventListener("click", () => {
        history.back();
    });

    document.getElementById('animalForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        formData.append('btCreate', 'true');

        let response;

        response = await fetch('controller/animalController.php', {
            method: 'POST',
            body: formData
        })

        const result = await response.json();
        document.getElementById('mensagem').textContent = result.mensagem;

        if (result.success) {
            window.location.href = 'index.php';
        }
    });
</script>