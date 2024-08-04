<?php
session_start();
include_once "../config/conexao.php";
include_once '../dao/agendamentoDao.php';
include_once '../dao/clienteDao.php';
include_once '../dao/animalDao.php';
include_once '../dao/servicoAgendamentoDao.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$agendamentoDao = new AgendamentoDao();
$clienteDao = new ClienteDao();
$animalDao = new AnimalDao();
$servicoAgendamentoDao = new ServicoAgendamentoDao();

if (isset($_POST['idVisita'])) {
    $idVisita = $_POST['idVisita'];
    $agendamento = $agendamentoDao->readById($idVisita);
    $cliente = $clienteDao->readId($agendamento['idCliente']);
    $animal = $animalDao->readId($agendamento['idAnimal']);

    if ($agendamento && $cliente) {
        $to = $cliente['email'];
        $subject = "Relatório de Serviço Realizado";
        $message = "Olá " . htmlspecialchars($cliente['nome']) . ",<br><br>";
        $message .= "Segue abaixo o relatório do serviço realizado:<br><br>";
        $message .= "Data: " . date('d/m/Y \à\s H:i', strtotime($agendamento['data'])) . "<br>";
        $message .= "Animal: " . htmlspecialchars($animal ? $animal["nome"] : 'Desconhecido') . "<br>";
        $message .= "Total: R$ " . number_format($agendamento['total'], 2, ',', '.') . "<br><br>";

        $servicos = $servicoAgendamentoDao->readByVisita($idVisita);

        if (!empty($servicos)) {
            $message .= "Serviços realizados:<br>";
            $message .= "<table border='1' cellpadding='5' cellspacing='0'>";
            $message .= "<thead><tr><th>Serviço</th><th>Quantidade</th><th>Preço</th></tr></thead>";
            $message .= "<tbody>";

            foreach ($servicos as $servico) {
                $message .= "<tr>";
                $message .= "<td>" . htmlspecialchars($servico['nomeServico']) . "</td>";
                $message .= "<td>" . htmlspecialchars($servico['quantidade']) . "</td>";
                $message .= "<td>R$ " . number_format($servico['preco'], 2, ',', '.') . "</td>";
                $message .= "</tr>";
            }

            $message .= "</tbody></table><br>";
        }

        $message .= "Obrigado,<br>Petshop";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pedrosmtpmail@gmail.com';
            $mail->Password = 'ihmf pvuq tkyx hgzs';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('pedrosmtpmail@gmail.com', 'Petshop');
            $mail->addAddress($to, $cliente['nome']);
            $mail->addReplyTo('pedrosmtpmail@gmail.com', 'Informações');

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = strip_tags(str_replace("<br>", "\n", $message));

            $mail->send();
            $_SESSION['flash_message'] = 'E-mail enviado com sucesso!';
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['flash_message'] = 'Agendamento ou cliente não encontrado.';
    }
} else {
    $_SESSION['flash_message'] = 'ID do agendamento não fornecido.';
}

header('Location: ../index.php');
exit();
