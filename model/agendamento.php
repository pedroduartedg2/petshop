<?php
class Agendamento
{
    // Atributos da tabela Visita
    private $idVisita;
    private $data;
    private $concluido;
    private $total;
    private $idAnimal;
    private $idCliente;

    public function __construct($idVisita, $data, $concluido, $total, $idAnimal, $idCliente)
    {
        $this->idVisita = $idVisita;
        $this->data = $data;
        $this->concluido = $concluido;
        $this->total = $total;
        $this->idAnimal = $idAnimal;
        $this->idCliente = $idCliente;
    }

    public function getIdVisita()
    {
        return $this->idVisita;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getConcluido()
    {
        return $this->concluido;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getIdAnimal()
    {
        return $this->idAnimal;
    }

    public function getIdCliente()
    {
        return $this->idCliente;
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
