<?php
include_once "../config/conexao.php";
include_once "../model/servicoAgendamento.php";
include_once "../dao/servicoAgendamentoDao.php";

$response = ['success' => false, 'mensagem' => ''];

try {
    $servicoAgendamentoDao = new ServicoAgendamentoDao();

    $idVisita = isset($_POST['idVisita']) ? intval($_POST['idVisita']) : null;
    $idServico = isset($_POST['idServico']) ? intval($_POST['idServico']) : null;
    $quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : null;
    $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : null;

    if ($idVisita && $idServico) {
        // Atualizar serviço de agendamento existente
        $servicoAgendamento = new ServicoAgendamento($idVisita, $idServico, $quantidade, $preco);
        $success = $servicoAgendamentoDao->update($servicoAgendamento);
        $response['mensagem'] = $success ? 'Serviço de agendamento atualizado com sucesso!' : 'Falha ao atualizar o serviço de agendamento.';
    } else {
        // Criar novo serviço de agendamento
        if ($idVisita && $idServico && $quantidade !== null && $preco !== null) {
            $servicoAgendamento = new ServicoAgendamento($idVisita, $idServico, $quantidade, $preco);
            $success = $servicoAgendamentoDao->create($servicoAgendamento);
            $response['mensagem'] = $success ? 'Serviço de agendamento cadastrado com sucesso!' : 'Falha ao cadastrar o serviço de agendamento.';
        } else {
            $response['mensagem'] = 'Dados insuficientes para criar o serviço de agendamento.';
        }
    }

    $response['success'] = $success;
} catch (Exception $e) {
    $response['mensagem'] = 'Ocorreu um erro: ' . $e->getMessage();
}

echo json_encode($response);
