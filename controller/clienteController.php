<?php
session_start();
include_once "../config/conexao.php";
include_once "../dao/clienteDao.php";
include_once "../model/cliente.php";

if ((isset($_POST["btSalvar"])) || (isset($_GET["id"]))) {
    //Armazenando os nomes do arquivo
    $temp = $_FILES["foto"]["tmp_name"];
    $foto = date("YmdHis") . $_FILES["foto"]["name"];


    //objeto do tipo aluno
    $obj = new Cliente(
        $_POST["idAluno"],
        $_POST["nome"],
        $_POST["email"],
        $foto,
        MD5($_POST["senha"]),
        isset($_POST["adm"]) ? 1 : 0,
        $_POST["apelido"]
    );


    //objeto alunoDao
    $objDao = new ClienteDao();

    //verificando a operação que o usuário escolheu
    if (isset($_GET["id"])) {
        //excluir
        $resultado = $objDao->delete($_GET["id"]);
        if ($resultado)
            $_SESSION["mensagem"] = "Excluido com sucesso!";
        else
            $_SESSION["mensagem"] = "Erro ao excluir";
    } elseif ($_POST["idAluno"] == "") {
        //inserir
        if ($_FILES["foto"]["name"] == "")
            $obj->foto = null;
        $resultado = $objDao->create($obj);
        if (($resultado) && ($_FILES["foto"]["name"] != "")) {
            if (move_uploaded_file($temp, "../foto/" . $foto))
                $_SESSION["mensagem"] = "Imagem enviada com sucesso!";
            else
                $_SESSION["mensagem"] = "Erro ao enviar a imagem";
        } else {
            if ($_FILES["foto"]["name"] != "")
                $_SESSION["mensagem"] = "Erro ao cadastrar e/ou enviar a imagem";
            elseif (!$resultado)
                $_SESSION["mensagem"] = "Erro ao cadastrar";
            else
                $_SESSION["mensagem"] = "Cadastro realizado com sucesso";
        }
    } else {
        //alterar
        if ($_POST["senha"] == "")
            $obj->senha = null;
        if ($_FILES["foto"]["name"] == "")
            $obj->foto = null;
        $resultado = $objDao->update($obj);
        if (($resultado) && ($_FILES["foto"]["name"] != "")) {
            if (move_uploaded_file($temp, "../foto/" . $foto))
                $_SESSION["mensagem"] = "Imagem enviada com sucesso!";
            else
                $_SESSION["mensagem"] = "Erro ao enviar a imagem";
        } else {
            if ($_FILES["foto"]["name"] != "")
                $_SESSION["mensagem"] = "Erro ao cadastrar e/ou enviar a imagem";
            elseif (!$resultado)
                $_SESSION["mensagem"] = "Erro ao cadastrar";
            else
                $_SESSION["mensagem"] = "Cadastro realizado com sucesso";
        }
    }
    $_SESSION["resultado"] = $resultado;
}
header("location:../aluno.php");
