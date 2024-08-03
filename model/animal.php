<?php
class Animal
{
    //Atributos da tabela aluno
    private $idAnimal;
    private $nome;
    private $peso;
    private $nascimento;
    private $cor;
    private $observacao;
    private $idCliente;


    public function __construct($idAnimal, $nome, $peso, $nascimento, $cor, $observacao, $idCliente)
    {
        $this->idAnimal = $idAnimal;
        $this->nome = $nome;
        $this->peso = $peso;
        $this->nascimento = $nascimento;
        $this->cor = $cor;
        $this->observacao = $observacao;
        $this->idCliente = $idCliente;
    }

    public function getIdAnimal()
    {
        return $this->idAnimal;
    }

    public function getNomeAnimal()
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
