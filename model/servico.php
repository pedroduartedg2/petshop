<?php
class Servico
{
    //Atributos da tabela aluno
    private $idServico;
    private $nome;
    private $descricao;
    private $preco;


    public function __construct($idServico, $nome, $descricao, $preco)
    {
        $this->idServico = $idServico;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
    }

    public function getIdServico()
    {
        return $this->idServico;
    }

    public function getNomeServico()
    {
        return $this->nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getPreco()
    {
        return $this->preco;
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
