<?php
class Cliente
{
    //Atributos da tabela aluno
    private $idaluno;
    private $nome;
    private $email;
    private $senha;
    private $cpf;
    private $telefone1;
    private $telefone2;
    private $cep;
    private $logradouro;
    private $numero;
    private $cidade;
    private $estado;

    public function __construct($idaluno, $nome, $email, $senha, $cpf, $telefone1, $telefone2, $cep, $logradouro, $numero, $cidade, $estado)
    {
        $this->idaluno = $idaluno;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->cpf = $cpf;
        $this->telefone1 = $telefone1;
        $this->telefone2 = $telefone2;
        $this->cep = $cep;
        $this->logradouro = $logradouro;
        $this->numero = $numero;
        $this->cidade = $cidade;
        $this->estado = $estado;
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
