<?php
// Inclua os arquivos necessários
include_once "../config/conexao.php";
include_once '../model/agendamento.php';
include_once '../dao/AgendamentoDao.php';

// Verifica se o ID foi enviado via POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Crie uma instância do AgendamentoDao
    $agendamentoDao = new AgendamentoDao();

    // Chame o método delete
    $result = $agendamentoDao->delete($id);

    // Redirecione para a página de lista de agendamentos com uma mensagem de sucesso ou erro
    if ($result) {
        header('Location: ../index.php?message=deleted');
    } else {
        header('Location: ../index.php?message=error');
    }
    exit();
} else {
    // Redirecione se o ID não for fornecido
    header('Location: ../index.php?message=error');
    exit();
}
