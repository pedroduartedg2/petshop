<?php
session_start();
//Importando os arquivos de manipulação dos dados
include_once "header.html";
include_once "./config/conexao.php";
include_once "model/cliente.php";
include_once "dao/clienteDao.php";
include_once "config/utils.php";

?>
<div class="h-[calc(100vh-71.75px)] flex flex-col items-center justify-center gap-2">
    <div class="flex gap-6 flex-col">
        <h1 class="font-bold text-purple-900 text-2xl">Cadastro</h1>
        <form action="controller/clienteController.php" method="POST" class="flex flex-col gap-2">
            <div class="flex flex-col">
                <label for="nome" class="text-sm">Nome *</label>
                <input name="nome" type="text" placeholder="Digite seu nome" class="p-2 rounded-md border border-slate-200" required>
            </div>
            <div class="flex flex-col">
                <label for="email" class="text-sm">E-mail *</label>
                <input name="email" type="email" placeholder="Digite seu email" class="p-2 rounded-md border border-slate-200" required>
            </div>
            <div class="flex flex-col">
                <label for="senha" class="text-sm">Senha *</label>
                <input name="senha" type="password" placeholder="Digite sua senha" class="p-2 rounded-md border border-slate-200" required>
            </div>
            <div class="flex flex-col">
                <label for="cpf" class="text-sm">CPF *</label>
                <input name="cpf" type="text" placeholder="000.000.000-00" oninput="maskCPF(this)" class="p-2 rounded-md border border-slate-200" required>
            </div>
            <div class="flex flex-row gap-2 w-full">
                <div class="flex flex-col w-full">
                    <label for="celular" class="text-sm">Celular *</label>
                    <input name="celular" type="text" placeholder="00 000000000" class="p-2 rounded-md border border-slate-200" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
                </div>
                <div class="flex flex-col w-full">
                    <label for="telefone" class="text-sm">Telefone</label>
                    <input name="telefone" type="text" placeholder="00 000000000" class="p-2 rounded-md border border-slate-200" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);">
                </div>
            </div>
            <div class="flex flex-row gap-2 w-full">
                <div class="flex flex-col w-full">
                    <label for="cep" class="text-sm">CEP *</label>
                    <input name="cep" type="number" placeholder="00000000" class="p-2 rounded-md border border-slate-200" required>
                </div>
                <div class="flex flex-col w-full">
                    <label for="logradouro" class="text-sm">Logradouro *</label>
                    <input name="logradouro" type="text" placeholder="Digite o logradouro" class="p-2 rounded-md border border-slate-200" required>
                </div>
            </div>
            <div class="flex flex-row gap-2">
                <div class="flex flex-col">
                    <label for="numero" class="text-sm">Número *</label>
                    <input name="numero" type="number" placeholder="000" class="p-2 rounded-md border border-slate-200" required>
                </div>
                <div class="flex flex-col">
                    <label for="cidade" class="text-sm">Cidade *</label>
                    <input name="cidade" type="text" placeholder="Digite a cidade" class="p-2 rounded-md border border-slate-200" required>
                </div>
                <div class="flex flex-col">
                    <label for="estado" class="text-sm">Estado *</label>
                    <input name="estado" type="text" placeholder="Digite o estado" class="p-2 rounded-md border border-slate-200" required>
                </div>
            </div>
            <button name="btRegister" type="submit" class="bg-purple-900 p-2 text-slate-50 font-semibold rounded-md mt-2">Cadastrar</button>
        </form>
        <p class="text-center">Já possui uma conta? <a href="./login.php" class="underline">Entrar</a></p>
    </div>
</div>

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
            var v = mphone(o.value);
            if (v != o.value) {
                o.value = v;
            }
        }, 1);
    }

    function mphone(v) {
        var r = v.replace(/\D/g, ""); // Remove todos os caracteres não numéricos
        r = r.replace(/^0/, ""); // Remove o zero inicial, se houver
        if (r.length > 10) {
            r = r.replace(/^(\d{2})(\d{5})(\d{4}).*/, "$1 $2-$3"); // Formata para 11 dígitos (com espaço e hífen)
        } else if (r.length > 5) {
            r = r.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "$1 $2-$3"); // Formata para 8 ou 9 dígitos (com espaço e hífen)
        } else if (r.length > 2) {
            r = r.replace(/^(\d{2})(\d{0,5})/, "$1 $2"); // Formata para 6 dígitos (com espaço)
        } else {
            r = r.replace(/^(\d*)/, "$1"); // Formata para até 2 dígitos (sem espaço)
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
                        // document.getElementsByName('bairro')[0].value = data.bairro;
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