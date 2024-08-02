<?php
session_start();
include_once "header.html";
include_once "./config/conexao.php";

echo '<div class="flex bg-red-600 text-slate-200">
    <h1>Hello World!</h1>
    <a href="logout.php" class="text-white underline">Sair</a>
</div>';

var_dump($_SESSION["logado"]);
if (!$_SESSION["logado"]) {
    header("location:login.php");
    exit;
}
