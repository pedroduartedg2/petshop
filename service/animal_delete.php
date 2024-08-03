<?php
// Inclua os arquivos necessários
include_once "../config/conexao.php";
include_once '../model/animal.php';
include_once '../dao/AnimalDao.php';

// Verifica se o ID foi enviado via POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Crie uma instância do AnimalDao
    $animalDao = new AnimalDao();

    // Chame o método delete
    $result = $animalDao->delete($id);

    // Redirecione para a página de lista de animais com uma mensagem de sucesso ou erro
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
