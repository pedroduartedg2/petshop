<?php
session_start();
include_once "../config/conexao.php";
include_once "../dao/funcionarioDao.php";
include_once "../model/funcionario.php";
include_once "../config/utils.php";

$response = ["success" => false, "mensagem" => ""];

if (isset($_POST["btRegister"])) {
    $idFuncionario = isset($_POST['idFuncionario']) ? intval($_POST['idFuncionario']) : null;
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

    $funcionarioDao = new FuncionarioDao();

    if ($idFuncionario) {
        // Atualização do funcionario existente
        $funcionario = new Funcionario(
            $idFuncionario,
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

        $sucesso = $funcionarioDao->update($funcionario);

        if ($sucesso) {
            $response["success"] = true;
            $response["mensagem"] = "Funcionário atualizado com sucesso!";
        } else {
            $response["mensagem"] = "Erro ao atualizar funcionário.";
        }
    } else {
        // Criação de novo Funcionário
        $funcionario = new Funcionario(
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

        $sucesso = $funcionarioDao->create($funcionario);

        if ($sucesso) {
            $response["success"] = true;
            $response["mensagem"] = "Funcionário criado com sucesso!";
        } else {
            $response["mensagem"] = "Erro ao cadastrar funcionário.";
        }
    }
}

if (isset($_POST["btLogin"])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $funcionarioDao = new FuncionarioDao();
    $sucesso = $funcionarioDao->login($email, $senha);

    if ($sucesso) {
        $_SESSION["logado"] = true;
        $_SESSION["user"] = array(
            'id' => $sucesso[0]->getIdfuncionario(),
            'nome' => $sucesso[0]->getNomeFuncionario(),
            'type' => "funcionario"
        );

        $response["success"] = true;
        $response["mensagem"] = "Login realizado com sucesso!";
    } else {
        $response["mensagem"] = "E-mail e/ou senha inválidos.";
    }
}

echo json_encode($response);
