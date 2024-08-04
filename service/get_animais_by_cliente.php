<?php
include_once "../config/conexao.php";
include_once "../dao/animalDao.php";

$idCliente = isset($_GET['idCliente']) ? intval($_GET['idCliente']) : null;

if ($idCliente) {
    $animalDao = new AnimalDao();
    $animais = $animalDao->getAnimalsByClientId($idCliente);
    echo json_encode($animais);
} else {
    echo json_encode([]);
}
