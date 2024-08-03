<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['last_page'])) {
    echo json_encode(['last_page' => $_SESSION['last_page']]);
} else {
    echo json_encode(['last_page' => 'agendamentos.php']); // Página padrão, se não houver valor na sessão
}
