<?php
class ServicoAgendamentoDao
{
    public function create(ServicoAgendamento $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "INSERT INTO ServicoVisita (idVisita, idServico, quantidade, preco) VALUES (?, ?, ?, ?)";
            $query = $banco->prepare($sql);
            $query->execute([
                $obj->idVisita,
                $obj->idServico,
                $obj->quantidade,
                $obj->preco
            ]);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return false;
        }
    }

    public function readByVisita($idVisita)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT sv.*, s.nome AS nomeServico 
                    FROM ServicoVisita sv
                    JOIN Servico s ON sv.idServico = s.idServico
                    WHERE sv.idVisita = ?";
            $query = $banco->prepare($sql);
            $query->execute([$idVisita]);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function readByServico($idServico)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM ServicoVisita WHERE idServico = ?";
            $query = $banco->prepare($sql);
            $query->execute([$idServico]);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $result;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return [];
        }
    }

    public function update(ServicoAgendamento $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "UPDATE ServicoVisita SET quantidade=?, preco=? WHERE idVisita=? AND idServico=?";
            $param = [$obj->quantidade, $obj->preco, $obj->idVisita, $obj->idServico];
            $query = $banco->prepare($sql);
            $query->execute($param);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($idVisita, $idServico)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "DELETE FROM ServicoVisita WHERE idVisita = ? AND idServico = ?";
            $query = $banco->prepare($sql);
            $query->execute([$idVisita, $idServico]);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
