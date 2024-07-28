<?php
session_start();
//Importando os arquivos de manipulação dos dados
include_once "header.html";
include_once "./config/conexao.php";
include_once "model/user.php";
include_once "dao/userDao.php";
include_once "config/utils.php";

//Criando o objeto Dao
$objDao = new ClienteDao();

// //Validando o email e a senha
if (isset($_POST["btSalvar"])) {
    $res = $objDao->login($_POST["email"], $_POST["senha"]);
    if (count($res) == 0) {
        $_SESSION["mensagem"] = "Usuário e/ou senha incorreto(s)";
        exibeMensagem();
    } else {
        $_SESSION["logado"] = true;
        $_SESSION["idaluno"] = $res[0]->idaluno;

        header("location:index.php");
    }
}
?>

<div class="h-[calc(100vh-71.75px)] flex flex-col items-center justify-center gap-2">
    <div class="flex gap-6 flex-col">
        <h1 class="font-bold text-purple-900 text-2xl">Login</h1>
        <form action="POST" class="flex flex-col gap-2 w-80">
            <div class="flex flex-col">
                <label for="type" class="text-sm">Tipo de login</label>
                <select name="" id="type" class="p-2 rounded-md border border-slate-200">
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
            <button name="btSalvar" type="submit" class="bg-purple-900 p-2 text-slate-50 font-semibold rounded-md mt-2">Entrar</button>
        </form>
        <p class="text-center">Não tem uma conta? <a href="./register.php" class="underline">Criar conta</a></p>
    </div>
</div>
<!-- formulário de cadastro/alterar
    <form method="post" class="py-5">
        <div class="col-sm-12 col-md-4 offset-md-4 p-1">
        <input type="email" name="email" class="form-control" 
        placeholder="Digite o e-mail">
    </div>
    <div class="col-sm-12 col-md-4 offset-md-4 p-1">
        <input type="password" name="senha" class="form-control" 
        placeholder="Digite a senha" required >
    </div>
    <div class="col-sm-12 col-md-4 offset-md-4 p-1">
        <div class="d-grid gap-2">
            <input type="submit" name="btSalvar" class="btn btn-success" 
            value="Entrar">
            <a href="cadastro.php" class="btn btn-link">Registre-se</a>
        </div>
    </div>
</form> -->