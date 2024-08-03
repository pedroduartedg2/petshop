<?php
session_start();
include_once "header.html";
include_once "./config/conexao.php";
include_once "model/servico.php";
include_once "dao/servicoDao.php";
include_once "config/utils.php";

// Verifica se um ID foi passado na URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$servicoDao = new ServicoDao();
$servico = null;

if ($id) {
    // Busca os dados do serviço se o ID for válido
    $servico = $servicoDao->readId($id);
}

?>

<div class="h-[calc(100vh-71.75px)] flex flex-col gap-2 p-10 items-center justify-center">
    <div class="flex gap-6 flex-col w-1/3">
        <h1 class="font-bold text-purple-900 text-2xl"><?= $id ? 'Editar Serviço' : 'Cadastro de Serviço' ?></h1>
        <form id="serviceForm" method="POST" class="flex flex-col gap-2">
            <input type="hidden" name="idServico" value="<?= $id ? htmlspecialchars($servico['idServico']) : '' ?>">

            <div class="flex flex-col">
                <label for="nome" class="text-sm">Nome *</label>
                <input name="nome" type="text" placeholder="Nome do serviço" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($servico['nome']) : '' ?>">
            </div>
            <div class="flex flex-col">
                <label for="descricao" class="text-sm">Descrição *</label>
                <input name="descricao" type="text" placeholder="Descrição do serviço" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($servico['descricao']) : '' ?>">
            </div>
            <div class="flex flex-col">
                <label for="preco" class="text-sm">Preço *</label>
                <input id="preco" name="preco" type="number" step="0.01" min="0" placeholder="Digite o preço" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($servico['preco']) : '' ?>">
            </div>
            <div class="flex flex-row justify-between mt-2">
                <button id="btVoltar" name="btVoltar" type="button" class="py-2 px-4 text-purple-950 border border-purple-950 font-semibold rounded-md">Cancelar</button>
                <button name="btCreate" type="submit" class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md"><?= $id ? 'Atualizar Serviço' : 'Cadastrar Serviço' ?></button>
            </div>
        </form>
        <div id="mensagem" class="text-slate-700 text-center"></div>
    </div>
</div>

<script>
    document.getElementById("btVoltar").addEventListener("click", () => {
        history.back();
    });
    document.getElementById('serviceForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        formData.append('btCreate', 'true');

        let response;

        response = await fetch('controller/servicoController.php', {
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