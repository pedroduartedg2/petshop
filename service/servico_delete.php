<?php
// Inclua os arquivos necessários
include_once "../config/conexao.php";
include_once '../model/servico.php';
include_once '../dao/ServicoDao.php';

// Verifica se o ID foi enviado via POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Crie uma instância do ServicoDao
    $servicoDao = new ServicoDao();

    // Chame o método delete
    $result = $servicoDao->delete($id);

    // Redirecione para a página de lista de serviços com uma mensagem de sucesso ou erro
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
