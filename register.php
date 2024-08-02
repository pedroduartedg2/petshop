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
                <input name="cpf" type="number" placeholder="00000000000" class="p-2 rounded-md border border-slate-200" required>
            </div>
            <div class="flex flex-row gap-2 w-full">
                <div class="flex flex-col w-full">
                    <label for="celular" class="text-sm">Celular *</label>
                    <input name="celular" type="number" placeholder="00 000000000" class="p-2 rounded-md border border-slate-200" required>
                </div>
                <div class="flex flex-col w-full">
                    <label for="telefone" class="text-sm">Telefone</label>
                    <input name="telefone" type="number" placeholder="00 000000000" class="p-2 rounded-md border border-slate-200">
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