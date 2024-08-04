<?php
class Cliente
{
    //Atributos da tabela aluno
    private $idCliente;
    private $nome;
    private $email;
    private $senha;
    private $cpf;
    private $telefone1;
    private $telefone2;
    private $cep;
    private $logradouro;
    private $numero;
    private $bairro;
    private $cidade;
    private $estado;

    public function __construct($idCliente, $nome, $email, $senha, $cpf, $telefone1, $telefone2, $cep, $logradouro, $numero, $bairro, $cidade, $estado)
    {
        $this->idCliente = $idCliente;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->cpf = $cpf;
        $this->telefone1 = $telefone1;
        $this->telefone2 = $telefone2;
        $this->cep = $cep;
        $this->logradouro = $logradouro;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
    }

    public function getIdCliente()
    {
        return $this->idCliente;
    }

    public function getNomeCliente()
    {
        return $this->nome;
    }

    public function __get($key)
    {
        return $this->{$key};
    }

    public function __set($key, $value)
    {
        $this->{$key} = $value;
    }

    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
