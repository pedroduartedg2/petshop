<?php
session_start();
include_once "header.html";
include_once "./config/conexao.php";
include_once "model/agendamento.php";
include_once "dao/agendamentoDao.php";
include_once "dao/clienteDao.php";
include_once "model/cliente.php";
include_once "config/utils.php";

// Verifica se um ID foi passado na URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$agendamentoDao = new AgendamentoDao();
$agendamento = null;
$servicosAssociados = [];

if ($id) {
    // Busca os dados do agendamento se o ID for válido
    $agendamento = $agendamentoDao->readId($id);
    $servicosAssociados = $agendamentoDao->getServicosByAgendamento($id);
}

$clienteDao = new ClienteDao();
$clientes = $clienteDao->readAll();
$clientes = array_map(function ($cliente) {
    return [
        'idCliente' => $cliente->getIdCliente(),
        'nome' => $cliente->getNomecliente(),
    ];
}, $clientes);

$tipoUsuario = isset($_SESSION["user"]["type"]) ? $_SESSION["user"]["type"] : null;
$idClienteLogado = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : null;
?>

<div class="h-[calc(100vh-71.75px)] flex flex-col gap-2 p-10 items-center justify-center">
    <div class="flex gap-6 flex-col w-1/3">
        <h1 class="font-bold text-purple-900 text-2xl"><?= $id ? 'Editar Agendamento' : 'Cadastro de Agendamento' ?></h1>
        <form id="agendamentoForm" method="POST" class="flex flex-col gap-2">
            <input type="hidden" name="idVisita" value="<?= $id ? htmlspecialchars($agendamento['idVisita']) : '' ?>">

            <div class="flex flex-col">
                <label for="data" class="text-sm">Data *</label>
                <input name="data" type="datetime-local" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($agendamento['data']) : '' ?>">
            </div>
            <div class="flex flex-col <?= $tipoUsuario === 'cliente' ? 'hidden' : '' ?>">
                <label for="concluido" class="text-sm">Concluído *</label>
                <select name="concluido" class="p-2 rounded-md border border-slate-200" required>
                    <option value="1" <?= $id && $agendamento['concluido'] ? 'selected' : '' ?>>Sim</option>
                    <option value="0" <?= $id && !$agendamento['concluido'] ? 'selected' : '' ?>>Não</option>
                </select>
            </div>
            <div class="flex flex-col" id="clienteContainer" <?= $tipoUsuario === 'cliente' ? 'style="display:none;"' : '' ?>>
                <label for="idCliente" class="text-sm">Cliente *</label>
                <select id="idCliente" name="idCliente" class="p-2 rounded-md border border-slate-200" <?= $tipoUsuario === 'cliente' ? 'disabled' : '' ?> required>
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clientes as $cliente) : ?>
                        <option value="<?= htmlspecialchars($cliente['idCliente']) ?>" <?= $id && $agendamento['idCliente'] == $cliente['idCliente'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cliente['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="idCliente" id="hiddenIdCliente" value="<?= $tipoUsuario === 'cliente' ? htmlspecialchars($idClienteLogado) : '' ?>">
            <div class="flex flex-col">
                <label for="idAnimal" class="text-sm">Animal *</label>
                <select id="idAnimal" name="idAnimal" class="p-2 rounded-md border border-slate-200" required>
                    <!-- Opções serão carregadas dinamicamente -->
                </select>
            </div>
            <div class="flex flex-col gap-2" id="servicosContainer">
                <label for="servicos" class="text-sm">Serviços *</label>
                <div id="servicoList" class="flex flex-col gap-2">
                    <!-- Serviços serão adicionados dinamicamente aqui -->
                </div>
                <button type="button" id="addServico" class="py-2 px-4 text-white bg-purple-600 rounded-md">Adicionar Serviço</button>
            </div>
            <div id="valor-total">

            </div>
            <div class="flex flex-col">
                <input id="total" name="total" type="hidden" step="0.01" min="0" placeholder="Digite o total" class="p-2 rounded-md border border-slate-200" required value="<?= $id ? htmlspecialchars($agendamento['total']) : '' ?>">
            </div>
            <div class="flex flex-row justify-between mt-2">
                <button id="btVoltar" name="btVoltar" type="button" class="py-2 px-4 text-purple-950 border border-purple-950 font-semibold rounded-md">Cancelar</button>
                <button name="btCreate" type="submit" class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md"><?= $id ? 'Atualizar Agendamento' : 'Cadastrar Agendamento' ?></button>
            </div>
        </form>
        <div id="mensagem" class="text-slate-700 text-center"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const servicoList = document.getElementById('servicoList');
        const addServicoBtn = document.getElementById('addServico');
        let servicoCount = 0;

        // Função para carregar os serviços associados ao agendamento
        function carregarServicosAssociados(servicos) {
            servicos.forEach((servico, index) => {
                console.log(index)
                addServico(); // Adiciona um novo serviço no formulário
                const div = document.querySelector(`#servicoList > div:last-child`);
                const select = div.querySelector('select[name^="servicos"]');
                const quantidadeInput = div.querySelector('input[name$="[quantidade]"]');
                const precoInput = div.querySelector('input[name$="[preco]"]');

                // Carregar opções de serviços e selecionar o apropriado
                loadServicos(select).then(() => {
                    select.value = servico.idServico;
                    precoInput.value = servico.preco;
                    quantidadeInput.value = servico.quantidade;
                    calcularTotal(); // Atualizar o total
                });
            });

        }

        // Adicionar um bloco para obter e preencher os serviços
        <?php if ($id) : ?>
            const servicosAssociados = <?= json_encode($servicosAssociados) ?>;
            carregarServicosAssociados(servicosAssociados);
            setTimeout(() => {
                document.querySelector(`#servicoList > div:last-child`).remove()
            }, 10)
        <?php endif; ?>

        // Função para adicionar um novo serviço
        function addServico() {
            servicoCount++;
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center w-full justify-between box-service';

            div.innerHTML = `
                <select name="servicos[${servicoCount}][idServico]" class="p-2 rounded-md border border-slate-200 w-full" required>
                    <!-- Opções serão carregadas dinamicamente -->
                </select>
                <input name="servicos[${servicoCount}][quantidade]" type="number" value="1" step="1" min="1" placeholder="Quantidade" class="p-2 rounded-md border border-slate-200 w-14" required>
                <input name="servicos[${servicoCount}][preco]" type="hidden" placeholder="Preço" class="p-2 rounded-md border border-slate-200 w-24">
                <button type="button" class="removeServico py-1 px-2 text-red-500">Remover</button>
            `;
            servicoList.appendChild(div);

            // Carregar opções de serviços
            loadServicos(div.querySelector('select'));

            // Adicionar funcionalidade de remover serviço
            div.querySelector('.removeServico').addEventListener('click', function() {
                div.remove();
                calcularTotal(); // Recalcular total após remoção
            });

            // Adicionar eventos para atualizar o total
            div.querySelector('select').addEventListener('change', calcularTotal);
            div.querySelector('input[name$="[quantidade]"]').addEventListener('input', calcularTotal);
        }

        function calcularTotal() {
            const servicos = document.querySelectorAll('#servicoList > div');
            let total = 0;

            servicos.forEach(servico => {
                const select = servico.querySelector('select');
                const quantidadeInput = servico.querySelector('input[name$="[quantidade]"]');
                const precoInput = servico.querySelector('input[name$="[preco]"]');
                const quantidade = parseFloat(quantidadeInput.value) || 1;
                const preco = parseFloat(precoInput.value) || 0;

                total += preco * quantidade;
            });

            document.getElementById('total').value = total.toFixed(2);
            document.getElementById('valor-total').innerHTML = '<p><b>Valor total:</b> R$' + total.toFixed(2) + '</p>';
        }

        async function loadServicos(selectElement) {
            try {
                const response = await fetch('service/get_servicos.php');
                const servicos = await response.json();
                console.log("servicos:::: ", servicos)
                selectElement.innerHTML = '<option value="">Selecione um serviço</option>';
                servicos.forEach(servico => {
                    let option = document.createElement('option');
                    option.value = servico.idServico;
                    option.textContent = servico.nome + " - R$ " + servico.preco;
                    option.dataset.preco = servico.preco; // Armazenar preço no atributo de dados
                    selectElement.appendChild(option);
                });


                // Atualizar o campo de preço quando o serviço for selecionado
                selectElement.addEventListener('change', function() {
                    const preco = selectElement.options[selectElement.selectedIndex].dataset.preco || 0;
                    const precoInput = selectElement.parentElement.querySelector('input[name$="[preco]"]');
                    precoInput.value = preco;
                    calcularTotal(); // Atualizar o total ao selecionar um serviço
                });
            } catch (error) {
                console.error('Erro ao carregar serviços:', error);
            }
        }

        // Adicionar o primeiro serviço ao carregar a página
        addServico();

        // Adicionar evento para o botão de adicionar serviço
        addServicoBtn.addEventListener('click', addServico);
    });

    document.getElementById("btVoltar").addEventListener("click", () => {
        history.back();
    });

    document.getElementById('idCliente').addEventListener('change', async function() {
        const idCliente = this.value;
        const selectAnimal = document.getElementById('idAnimal');

        if (idCliente) {
            let response = await fetch(`service/get_animais_by_cliente.php?idCliente=${idCliente}`);
            let animais = await response.json();
            console.log("animais: ", animais)
            // Limpar o select de animais
            selectAnimal.innerHTML = '<option value="">Selecione um animal</option>';

            // Adicionar opções ao select de animais
            animais.forEach(animal => {
                let option = document.createElement('option');
                option.value = animal.idAnimal;
                option.textContent = animal.nome;
                selectAnimal.appendChild(option);
            });
        } else {
            selectAnimal.innerHTML = '<option value="">Selecione um cliente primeiro</option>';
        }
    });

    document.getElementById('agendamentoForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        formData.append('btCreate', 'true');

        let response = await fetch('controller/agendamentoController.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        document.getElementById('mensagem').textContent = result.mensagem;

        if (result.success) {
            window.location.href = 'index.php';
        }
    });

    // Carregar animais se estiver editando um agendamento
    window.addEventListener('DOMContentLoaded', async () => {
        const idCliente = document.getElementById('idCliente');
        const idAnimalSelecionado = "<?= $id ? $agendamento['idAnimal'] : '' ?>";

        if ("<?= $tipoUsuario ?>" === 'cliente') {
            idCliente.value = "<?= $idClienteLogado ?>";
            document.getElementById('hiddenIdCliente').value = "<?= $idClienteLogado ?>";
            idCliente.disabled = true;
        } else {
            document.getElementById('hiddenIdCliente').remove();
        }

        if (idCliente.value) {
            const selectAnimal = document.getElementById('idAnimal');
            try {
                let response = await fetch(`service/get_animais_by_cliente.php?idCliente=${idCliente.value}`);
                let animais = await response.json();

                // Limpar o select de animais
                selectAnimal.innerHTML = '<option value="">Selecione um animal</option>';

                // Adicionar opções ao select de animais
                animais.forEach(animal => {
                    let option = document.createElement('option');
                    option.value = animal.idAnimal;
                    option.textContent = animal.nome;
                    if (animal.idAnimal == idAnimalSelecionado) {
                        option.selected = true;
                    }
                    selectAnimal.appendChild(option);
                });
            } catch (error) {
                console.error('Erro ao carregar animais:', error);
            }
        } else {
            const selectAnimal = document.getElementById('idAnimal');
            selectAnimal.innerHTML = '<option value="">Selecione um cliente primeiro</option>';
        }
    });
</script>