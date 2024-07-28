<?php
class Cliente
{
    //Atributos da tabela aluno
    private $idaluno;
    private $nome;
    private $email;
    private $foto;
    private $senha;
    private $adm;
    private $apelido;

    public function __construct($idaluno, $nome, $email, $foto, $senha, $adm, $apelido)
    {
        $this->idaluno = $idaluno;
        $this->nome = $nome;
        $this->email = $email;
        $this->foto = $foto;
        $this->senha = $senha;
        $this->adm = $adm;
        $this->apelido = $apelido;
    }

    public function __get($key)
    {
        return $this->{$key};
    }

    public function __set($key, $value)
    {
        $this->{$key} = $value;
    }
}
