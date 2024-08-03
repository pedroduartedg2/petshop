<?php
session_start(); // Certifique-se de iniciar a sessão

// Atualize a sessão conforme necessário
if (isset($_POST['page'])) {
    $_SESSION['last_page'] = $_POST['page'];
}

// Responda com sucesso ou qualquer outra informação necessária
echo json_encode(['status' => 'success']);
