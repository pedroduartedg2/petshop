<?php
session_start();
include_once "../config/conexao.php";
include_once "../dao/clienteDao.php";
include_once "../model/cliente.php";
include_once "../config/utils.php";

$response = ["success" => false, "mensagem" => ""];

if (isset($_POST["btRegister"])) {
    $idCliente = isset($_POST['idCliente']) ? intval($_POST['idCliente']) : null;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];
    $telefone1 = $_POST['celular'];
    $telefone2 = $_POST['telefone'];
    $cep = $_POST['cep'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    $clienteDao = new ClienteDao();

    if ($idCliente) {
        // Atualização do cliente existente
        $cliente = new Cliente(
            $idCliente,
            $nome,
            $email,
            MD5($senha),
            $cpf,
            $telefone1,
            $telefone2,
            $cep,
            $logradouro,
            $numero,
            $bairro,
            $cidade,
            $estado
        );

        $sucesso = $clienteDao->update($cliente);

        if ($sucesso) {
            $response["success"] = true;
            $response["mensagem"] = "Cliente atualizado com sucesso!";
        } else {
            $response["mensagem"] = "Erro ao atualizar cliente.";
        }
    } else {
        // Criação de novo cliente
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
            $bairro,
            $cidade,
            $estado
        );

        $sucesso = $clienteDao->create($cliente);

        if ($sucesso) {
            $response["success"] = true;
            $response["mensagem"] = "Cliente criado com sucesso!";
        } else {
            $response["mensagem"] = "Erro ao cadastrar cliente.";
        }
    }
}

if (isset($_POST["btLogin"])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $clienteDao = new ClienteDao();
    $sucesso = $clienteDao->login($email, $senha);

    if ($sucesso) {
        $_SESSION["logado"] = true;
        $_SESSION["user"] = array(
            'id' => $sucesso[0]->getIdCliente(),
            'nome' => $sucesso[0]->getNomeCliente(),
            'type' => "cliente"
        );

        $response["success"] = true;
        $response["mensagem"] = "Login realizado com sucesso!";
    } else {
        $response["mensagem"] = "E-mail e/ou senha inválidos.";
    }
}

echo json_encode($response);
