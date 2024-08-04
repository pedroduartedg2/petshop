<?php
session_start();
include_once "../config/conexao.php";
include_once '../dao/agendamentoDao.php';
include_once '../dao/clienteDao.php';
include_once '../dao/animalDao.php';

$agendamentoDao = new AgendamentoDao();
$clienteDao = new ClienteDao();
$animalDao = new AnimalDao();

// Verifica se o ID do agendamento foi enviado
if (isset($_POST['idVisita'])) {
    $idVisita = $_POST['idVisita'];

    // Obtém o agendamento e o cliente correspondente
    $agendamento = $agendamentoDao->readById($idVisita);
    $cliente = $clienteDao->readId($agendamento['idCliente']);
    $animal = $animalDao->readId($agendamento['idAnimal']);

    if ($agendamento && $cliente) {
        $to = $cliente['email'];
        $subject = "Relatório de Serviço Realizado";
        $message = "Olá " . htmlspecialchars($cliente['nome']) . ",\n\n";
        $message .= "Segue abaixo o relatório do serviço realizado:\n\n";
        $message .= "Data: " . date('d/m/Y \à\s H:i', strtotime($agendamento['data'])) . "\n";
        $message .= "Animal: " . htmlspecialchars($animal ? $animal["nome"] : 'Desconhecido') . "\n";
        $message .= "Total: R$ " . number_format($agendamento['total'], 2, ',', '.') . "\n\n";
        $message .= "Obrigado,\nSua Empresa";
        $headers = "From: no-reply@suaempresa.com";

        // Envia o e-mail
        if (mail($to, $subject, $message, $headers)) {
            echo "E-mail enviado com sucesso!";
        } else {
            echo "Falha ao enviar o e-mail.";
        }
    } else {
        echo "Agendamento ou cliente não encontrado.";
    }
} else {
    echo "ID do agendamento não fornecido.";
}
