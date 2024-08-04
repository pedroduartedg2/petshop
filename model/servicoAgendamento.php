<?php
class ServicoAgendamento
{
    // Atributos da tabela ServicoVisita
    private $idVisita;
    private $idServico;
    private $quantidade;
    private $preco;

    public function __construct($idVisita, $idServico, $quantidade, $preco)
    {
        $this->idVisita = $idVisita;
        $this->idServico = $idServico;
        $this->quantidade = $quantidade;
        $this->preco = $preco;
    }

    public function getIdVisita()
    {
        return $this->idVisita;
    }

    public function getIdServico()
    {
        return $this->idServico;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
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
