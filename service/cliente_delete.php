<?php
// Inclua os arquivos necessários
include_once "../config/conexao.php";
include_once '../model/cliente.php';
include_once '../dao/ClienteDao.php';

// Verifica se o ID foi enviado via POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Crie uma instância do ClienteDao
    $clienteDao = new ClienteDao();

    // Chame o método delete
    $result = $clienteDao->delete($id);
    var_dump($result); // Adicione esta linha para verificar o valor retornado

    // Redirecione para a página de lista de clientes com uma mensagem de sucesso ou erro
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
