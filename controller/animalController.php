<?php
include_once "../config/conexao.php";
include_once "../model/animal.php";
include_once "../dao/animalDao.php";

$response = ['success' => false, 'mensagem' => ''];

try {
    $animalDao = new AnimalDao();

    $id = isset($_POST['idAnimal']) ? intval($_POST['idAnimal']) : null;
    $nome = $_POST['nome'];
    $peso = $_POST['peso'];
    $nascimento = $_POST['nascimento'];
    $cor = $_POST['cor'];
    $observacao = $_POST['observacao'];
    $idCliente = $_POST['idCliente'];

    if ($id) {
        // Atualizar animal existente
        $animal = new Animal($id, $nome, $peso, $nascimento, $cor, $observacao, $idCliente);
        $success = $animalDao->update($animal);
        $response['mensagem'] = $success ? 'Animal atualizado com sucesso!' : 'Falha ao atualizar o animal.';
    } else {
        // Criar novo animal
        $animal = new Animal(null, $nome, $peso, $nascimento, $cor, $observacao, $idCliente);
        $success = $animalDao->create($animal);
        $response['mensagem'] = $success ? 'Animal cadastrado com sucesso!' : 'Falha ao cadastrar o animal.';
    }

    $response['success'] = $success;
} catch (Exception $e) {
    $response['mensagem'] = 'Ocorreu um erro: ' . $e->getMessage();
}

echo json_encode($response);
