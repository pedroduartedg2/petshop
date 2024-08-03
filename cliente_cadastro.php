<?php
session_start();
include_once "header.html";
include_once "./config/conexao.php";
include_once "model/cliente.php";
include_once "dao/clienteDao.php";
include_once "config/utils.php";

// Verifica se um ID foi passado na URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$clienteDao = new ClienteDao();
$cliente = null;

if ($id) {
    // Busca os dados do cliente se o ID for válido
    $cliente = $clienteDao->readId($id);
}

?>

<div class="h-[calc(100vh-71.75px)] flex flex-col gap-2 p-10 items-center justify-center">
    <div class="flex gap-6 flex-col">
        <h1 class="font-bold text-purple-900 text-2xl"><?= $id ? 'Editar Cliente' : 'Cadastro de Cliente' ?></h1>
        <form id="serviceForm" method="POST" class="flex flex-col gap-2">
            <input type="hidden" name="idCliente" value="<?= $id ? htmlspecialchars($cliente['idCliente']) : '' ?>">
            <div class="flex flex-col gap-2">
                <div class="flex flex-col gap-1">
                    <div class="flex-col hidden">
                        <label for="type" class="text-sm">Tipo de cadastro</label>
                        <select name="type" id="type" class="p-2 rounded-md border border-slate-200">
                            <option value="cliente" selected>Cliente</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label for="nome" class="text-sm">Nome *</label>
                        <input name="nome" type="text" value="<?= $cliente ? htmlspecialchars($cliente['nome']) : '' ?>" placeholder="Digite seu nome" class="p-2 rounded-md border border-slate-200" required>
                    </div>
                    <div class="flex flex-col">
                        <label for="email" class="text-sm">E-mail *</label>
                        <input name="email" type="email" value="<?= $cliente ? htmlspecialchars($cliente['email']) : '' ?>" placeholder="Digite seu email" class="p-2 rounded-md border border-slate-200" required>
                    </div>
                    <div class="flex flex-col">
                        <label for="senha" class="text-sm"><?= $id ? 'Nova Senha' : 'Senha *' ?></label>
                        <input name="senha" type="password" placeholder="Digite sua senha" class="p-2 rounded-md border border-slate-200" <?= $id ? '' : 'required' ?>>
                    </div>
                    <div class="flex flex-col">
                        <label for="cpf" class="text-sm">CPF *</label>
                        <input name="cpf" type="text" value="<?= $cliente ? htmlspecialchars($cliente['cpf']) : '' ?>" placeholder="000.000.000-00" oninput="maskCPF(this)" class="p-2 rounded-md border border-slate-200" required>
                    </div>
                    <div class="flex flex-row gap-2 w-full">
                        <div class="flex flex-col w-full">
                            <label for="celular" class="text-sm">Celular *</label>
                            <input name="celular" type="text" value="<?= $cliente ? htmlspecialchars($cliente['telefone1']) : '' ?>" placeholder="00 000000000" class="p-2 rounded-md border border-slate-200" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
                        </div>
                        <div class="flex flex-col w-full">
                            <label for="telefone" class="text-sm">Telefone</label>
                            <input name="telefone" type="text" value="<?= $cliente ? htmlspecialchars($cliente['telefone2']) : '' ?>" placeholder="00 000000000" class="p-2 rounded-md border border-slate-200" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);">
                        </div>
                    </div>
                    <div class="flex flex-row gap-2 w-full">
                        <div class="flex flex-col w-full">
                            <label for="cep" class="text-sm">CEP *</label>
                            <input name="cep" type="text" value="<?= $cliente ? htmlspecialchars($cliente['cep']) : '' ?>" placeholder="00000000" class="p-2 rounded-md border border-slate-200" required>
                        </div>
                        <div class="flex flex-col w-full">
                            <label for="logradouro" class="text-sm">Logradouro *</label>
                            <input name="logradouro" type="text" value="<?= $cliente ? htmlspecialchars($cliente['logradouro']) : '' ?>" placeholder="Digite o logradouro" class="p-2 rounded-md border border-slate-200" required>
                        </div>
                    </div>
                    <div class="flex flex-row gap-2">
                        <div class="flex flex-col">
                            <label for="numero" class="text-sm">Número *</label>
                            <input name="numero" type="number" value="<?= $cliente ? htmlspecialchars($cliente['numero']) : '' ?>" placeholder="000" class="p-2 rounded-md border border-slate-200" required>
                        </div>
                        <div class="flex flex-col">
                            <label for="bairro" class="text-sm">Bairro *</label>
                            <input name="bairro" type="text" value="<?= $cliente ? htmlspecialchars($cliente['bairro']) : '' ?>" placeholder="Digite o bairro" class="p-2 rounded-md border border-slate-200" required>
                        </div>
                        <div class="flex flex-col">
                            <label for="cidade" class="text-sm">Cidade *</label>
                            <input name="cidade" type="text" value="<?= $cliente ? htmlspecialchars($cliente['cidade']) : '' ?>" placeholder="Digite a cidade" class="p-2 rounded-md border border-slate-200" required>
                        </div>
                        <div class="flex flex-col">
                            <label for="estado" class="text-sm">Estado *</label>
                            <input name="estado" type="text" value="<?= $cliente ? htmlspecialchars($cliente['estado']) : '' ?>" placeholder="Digite o estado" class="p-2 rounded-md border border-slate-200" required>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-between mt-2">
                    <button id="btVoltar" name="btVoltar" type="button" class="py-2 px-4 text-purple-950 border border-purple-950 font-semibold rounded-md">Cancelar</button>
                    <button name="btRegister" type="submit" class="bg-purple-900 py-2 px-4 text-slate-50 font-semibold rounded-md"><?= $id ? 'Atualizar Cliente' : 'Cadastrar Cliente' ?></button>
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

        formData.append('btRegister', 'true');

        let response;

        response = await fetch('controller/clienteController.php', {
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

<script>
    function maskCPF(o) {
        setTimeout(function() {
            var v = mcpf(o.value);
            if (v != o.value) {
                o.value = v;
            }
        }, 1);
    }

    function mcpf(v) {
        var r = v.replace(/\D/g, ""); // Remove todos os caracteres não numéricos
        if (r.length > 11) {
            r = r.slice(0, 11); // Limita a 11 dígitos
        }
        if (r.length > 6) {
            r = r.replace(/^(\d{3})(\d{3})(\d{0,3})/, "$1.$2.$3"); // Adiciona pontos
        }
        if (r.length > 9) {
            r = r.replace(/^(\d{3}\.\d{3}\.\d{3})(\d{0,2})/, "$1-$2"); // Adiciona hífen
        }
        return r;
    }

    function mask(o, f) {
        setTimeout(function() {
            var v = f(o.value);
            if (v != o.value) {
                o.value = v;
            }
        }, 1);
    }

    function mphone(v) {
        var r = v.replace(/\D/g, ""); // Remove todos os caracteres não numéricos
        if (r.length > 11) {
            r = r.slice(0, 11); // Limita a 11 dígitos
        }
        if (r.length > 2 && r.length <= 7) {
            r = r.replace(/^(\d{2})(\d)/, "($1) $2"); // Adiciona parênteses
        } else if (r.length > 7) {
            r = r.replace(/^(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3"); // Adiciona hífen
        }
        return r;
    }

    document.getElementsByName('cep')[0].addEventListener('input', function(event) {
        let value = event.target.value;

        // Remove tudo que não for número
        value = value.replace(/\D/g, '');

        // Limita o valor a 8 dígitos
        if (value.length > 8) {
            value = value.slice(0, 8);
        }

        // Adiciona a máscara
        if (value.length > 8) {
            value = value.replace(/^(\d{5})(\d{0,3})/, '$1-$2');
        }

        // Atualiza o valor do campo
        event.target.value = value;
    });

    document.getElementsByName('cep')[0].addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        if (cep.length === 8) { // Verifica se o CEP tem 8 dígitos
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        alert('CEP não encontrado.');
                    } else {
                        document.getElementsByName('logradouro')[0].value = data.logradouro;
                        document.getElementsByName('bairro')[0].value = data.bairro;
                        document.getElementsByName('cidade')[0].value = data.localidade;
                        document.getElementsByName('estado')[0].value = data.uf;
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar o CEP:', error);
                });
        }
    });
</script>