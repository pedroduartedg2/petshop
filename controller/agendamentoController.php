<?php
session_start();
include_once "../config/conexao.php";
include_once "../model/agendamento.php";
include_once "../dao/agendamentoDao.php";
include_once "../dao/clienteDao.php";
include_once "../dao/servicoAgendamentoDao.php";
include_once "../model/cliente.php";
include_once "../model/servicoAgendamento.php";
include_once "../config/utils.php";

$response = ['success' => false, 'mensagem' => ''];

try {
    $agendamentoDao = new AgendamentoDao();
    $servicoAgendamentoDao = new ServicoAgendamentoDao();

    $id = isset($_POST['idVisita']) ? intval($_POST['idVisita']) : null;
    $data = $_POST['data'];
    $concluido = isset($_POST['concluido']) ? intval($_POST['concluido']) : 0;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0.0;
    $idCliente = isset($_POST['idCliente']) ? intval($_POST['idCliente']) : null;
    $idAnimal = isset($_POST['idAnimal']) ? intval($_POST['idAnimal']) : null;

    if ($id) {
        // Atualizar agendamento existente
        $agendamento = new Agendamento($id, $data, $concluido, $total, $idAnimal, $idCliente);
        $success = $agendamentoDao->update($agendamento);
        $response['mensagem'] = $success ? 'Agendamento atualizado com sucesso!' : 'Falha ao atualizar o agendamento.';

        // Atualizar ou criar serviços de agendamento
        if ($success && isset($_POST['servicos']) && is_array($_POST['servicos'])) {
            $success = true;
            foreach ($_POST['servicos'] as $servicoData) {
                $servicoAgendamento = new ServicoAgendamento($id, intval($servicoData['idServico']), intval($servicoData['quantidade']), floatval($servicoData['preco']));
                if (!$servicoAgendamentoDao->update($servicoAgendamento)) {
                    $success = false;
                    break;
                }
            }
        } else {
            $response['mensagem'] = 'Dados dos serviços inválidos.';
        }
    } else {
        // Criar novo agendamento
        $agendamento = new Agendamento(null, $data, $concluido, $total, $idAnimal, $idCliente);
        $success = $agendamentoDao->create($agendamento);
        $response['mensagem'] = $success ? 'Agendamento cadastrado com sucesso!' : 'Falha ao cadastrar o agendamento.';

        if ($success) {
            // Obter o ID do último agendamento inserido
            $idVisita = $agendamentoDao->getLastInsertId();

            if (isset($_POST['servicos']) && is_array($_POST['servicos'])) {
                foreach ($_POST['servicos'] as $servicoData) {
                    $servicoAgendamento = new ServicoAgendamento($idVisita, intval($servicoData['idServico']), intval($servicoData['quantidade']), floatval($servicoData['preco']));
                    if (!$servicoAgendamentoDao->create($servicoAgendamento)) {
                        $success = false;
                        break;
                    }
                }
            } else {
                $response['mensagem'] = 'Dados dos serviços inválidos.';
            }
        }
    }

    $response['success'] = $success;
} catch (PDOException $e) {
    $response['mensagem'] = 'Erro de banco de dados: ' . $e->getMessage();
} catch (Exception $e) {
    $response['mensagem'] = 'Ocorreu um erro: ' . $e->getMessage();
}

echo json_encode($response);
