<?php
session_start();
//Importando os arquivos de manipulação dos dados
include_once "header.html";
include_once "./config/conexao.php";
include_once "model/cliente.php";
include_once "dao/clienteDao.php";
include_once "config/utils.php";

//Criando o objeto Dao
// $objDao = new ClienteDao();

// // //Validando o email e a senha
// if (isset($_POST["btSalvar"])) {
//     $res = $objDao->login($_POST["email"], $_POST["senha"]);
//     if (count($res) == 0) {
//         $_SESSION["mensagem"] = "Usuário e/ou senha incorreto(s)";
//         exibeMensagem();
//     } else {
//         $_SESSION["logado"] = true;
//         $_SESSION["idaluno"] = $res[0]->idaluno;

//         header("location:index.php");
//     }
// }
?>

<div class="h-[calc(100vh-71.75px)] flex items-center justify-center">
    <div class="flex gap-6 flex-col">
        <h1 class="font-bold text-purple-900 text-2xl">Login</h1>
        <div class="flex flex-col gap-2">
            <form id="loginForm" method="POST" class="flex flex-col gap-2 w-80">
                <div class="flex flex-col">
                    <label for="type" class="text-sm">Tipo de login</label>
                    <select name="type" id="type" class="p-2 rounded-md border border-slate-200">
                        <option value="cliente" selected>Cliente</option>
                        <option value="funcionario">Funcionário</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="email" class="text-sm">E-mail</label>
                    <input name="email" type="text" placeholder="Digite seu email" class="p-2 rounded-md border border-slate-200" required>
                </div>
                <div class="flex flex-col">
                    <label for="senha" class="text-sm">Senha</label>
                    <input name="senha" type="password" placeholder="Digite sua senha" class="p-2 rounded-md border border-slate-200" required>
                </div>
                <button name="btLogin" type="submit" class="bg-purple-900 p-2 text-slate-50 font-semibold rounded-md mt-2">Entrar</button>
            </form>
            <div id="mensagem" class="text-slate-700 text-center"></div>
            <p class="text-center">Não possui uma conta? <a href="./register.php" class="underline">Cadastrar</a></p>
        </div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
        event.preventDefault(); // Evita o envio normal do formulário

        const formData = new FormData(this);

        // Adiciona o parâmetro btLogin manualmente
        formData.append('btLogin', 'true');

        // Logar os parâmetros do FormData no console
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        const response = await fetch('controller/clienteController.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        document.getElementById('mensagem').textContent = result.mensagem;

        if (result.success) {
            window.location.href = 'index.php';
        }
    });
</script>