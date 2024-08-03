<?php
session_start();
include_once "../config/conexao.php";
include_once "../dao/clienteDao.php";
include_once "../model/cliente.php";
include_once "../config/utils.php";

$response = ["success" => false, "mensagem" => ""];

if (isset($_POST["btRegister"])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];
    $telefone1 = $_POST['celular'];
    $telefone2 = $_POST['telefone'];
    $cep = $_POST['cep'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    $cliente = new Cliente(
        null,
        $nome,
        $email,
        MD5($senha),
        $cpf,
        $telefone1,
        $telefone2,
        $cep,
        $logradouro,
        $numero,
        $cidade,
        $estado
    );

    $clienteDao = new ClienteDao();
    $sucesso = $clienteDao->create($cliente);

    if ($sucesso) {
        $response["success"] = true;
        $response["mensagem"] = "Cliente criado com sucesso!";
    } else {
        $response["mensagem"] = "Erro ao criar cliente.";
    }
}

if (isset($_POST["btLogin"])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $clienteDao = new ClienteDao();
    $res = $clienteDao->login($email, $senha);

    if ($res && count($res) > 0) {
        $_SESSION["logado"] = true;
        $_SESSION["idaluno"] = $res[0]->idaluno;

        $response["success"] = true;
        $response["mensagem"] = "Login realizado com sucesso!";
    } else {
        $response["mensagem"] = "E-mail e/ou senha inválidos.";
    }
}

echo json_encode($response);
