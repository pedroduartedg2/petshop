<?php
include_once "../config/conexao.php";
include_once "../model/servico.php";
include_once "../dao/servicoDao.php";

$response = ['success' => false, 'mensagem' => ''];

try {
    $servicoDao = new ServicoDao();

    $id = isset($_POST['idServico']) ? intval($_POST['idServico']) : null;
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    if ($id) {
        // Atualizar serviço existente
        $servico = new Servico($id, $nome, $descricao, $preco);
        $success = $servicoDao->update($servico);
        $response['mensagem'] = $success ? 'Serviço atualizado com sucesso!' : 'Falha ao atualizar o serviço.';
    } else {
        // Criar novo serviço
        $servico = new Servico(null, $nome, $descricao, $preco);
        $success = $servicoDao->create($servico);
        $response['mensagem'] = $success ? 'Serviço cadastrado com sucesso!' : 'Falha ao cadastrar o serviço.';
    }

    $response['success'] = $success;
} catch (Exception $e) {
    $response['mensagem'] = 'Ocorreu um erro: ' . $e->getMessage();
}

echo json_encode($response);
