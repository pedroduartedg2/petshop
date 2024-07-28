<?php
//utils.php
function exibeAtributo($atr, $vet)
{
    if (isset($vet[$atr])) {
        return $vet[$atr];
    } else {
        return "";
    }
}

function exibeMensagem()
{
    //Exibindo o retorno da mensagem
    if (isset($_SESSION["mensagem"])) {
        echo '<div class="alert alert-secondary alert-dismissible fade show" ';
        echo 'role="alert">';
        echo $_SESSION["mensagem"];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" ';
        echo 'aria-label="Close"></button>';
        echo '</div>';

        $_SESSION["mensagem"] = null;
    }
}

function formata_data($data)
{
    $format = date_create($data);
    return date_format($format, "d/m/Y");
}
