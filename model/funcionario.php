<?php
class Funcionario
{
    //Atributos da tabela aluno
    private $idFuncionario;
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

    public function __construct($idFuncionario, $nome, $email, $senha, $cpf, $telefone1, $telefone2, $cep, $logradouro, $numero, $bairro, $cidade, $estado)
    {
        $this->idFuncionario = $idFuncionario;
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

    public function getIdfuncionario()
    {
        return $this->idFuncionario;
    }
    public function getNomeFuncionario()
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
}
