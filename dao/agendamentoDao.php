<?php
// agendamentoDao.php

class AgendamentoDao
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conectar();
    }

    public function create(Agendamento $agendamento)
    {
        try {
            // Verificar se o cliente existe
            $stmtCliente = $this->pdo->prepare("SELECT 1 FROM cliente WHERE idCliente = ?");
            $stmtCliente->execute([$agendamento->getIdCliente()]);
            if ($stmtCliente->rowCount() == 0) {
                throw new Exception('Cliente não encontrado.');
            }

            // Verificar se o animal existe (se necessário)
            $stmtAnimal = $this->pdo->prepare("SELECT 1 FROM animal WHERE idAnimal = ?");
            $stmtAnimal->execute([$agendamento->getIdAnimal()]);
            if ($stmtAnimal->rowCount() == 0) {
                throw new Exception('Animal não encontrado.');
            }

            // Inserir o agendamento
            $stmt = $this->pdo->prepare("INSERT INTO visita (data, concluido, total, idAnimal, idCliente) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $agendamento->getData(),
                $agendamento->getConcluido(),
                $agendamento->getTotal(),
                $agendamento->getIdAnimal(),
                $agendamento->getIdCliente()
            ]);

            return $this->pdo->lastInsertId(); // Retorna o ID do último inserido
        } catch (PDOException $e) {
            throw new Exception('Erro ao criar agendamento: ' . $e->getMessage());
        }
    }

    public function getServicosByAgendamento($idAgendamento)
    {
        $query = "SELECT idServico, quantidade, preco FROM servicoVisita WHERE idVisita = :idVisita";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':idVisita', $idAgendamento);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function readAll()
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM visita ORDER BY data";
            $query = $banco->prepare($sql);
            $query->execute();
            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $resultados;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return [];
        }
    }

    public function readByCliente($idCliente)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM visita WHERE idCliente = ?";
            $query = $banco->prepare($sql);
            $query->execute([$idCliente]);
            $agendamentos = $query->fetchAll(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $agendamentos;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return [];
        }
    }

    public function readId($id)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM visita WHERE idVisita = ?";
            $query = $banco->prepare($sql);
            $query->execute([$id]);
            $linha = $query->fetch(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $linha;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return null;
        }
    }

    public function update(Agendamento $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "UPDATE visita SET data=?, concluido=?, total=?, idAnimal=?, idCliente=? WHERE idVisita=?";
            $param = [$obj->data, $obj->concluido, $obj->total, $obj->idAnimal, $obj->idCliente, $obj->idVisita];
            $query = $banco->prepare($sql);
            $query->execute($param);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtém um agendamento pelo ID
     * 
     * @param int $id
     * @return array
     */
    public function readById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM visita WHERE idVisita = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readCompletedInPeriod($startDate, $endDate)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM visita WHERE concluido = 1 AND data BETWEEN ? AND ?";
            $query = $banco->prepare($sql);
            $query->execute([$startDate, $endDate]);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function delete($id)
    {
        try {
            $banco = Conexao::conectar();
            $banco->beginTransaction();

            // Excluir registros relacionados na tabela ServicoVisita
            $sqlServicoVisita = "DELETE FROM ServicoVisita WHERE idVisita = ?";
            $queryServicoVisita = $banco->prepare($sqlServicoVisita);
            $queryServicoVisita->execute([$id]);

            // Excluir o registro na tabela Visita
            $sqlVisita = "DELETE FROM Visita WHERE idVisita = ?";
            $queryVisita = $banco->prepare($sqlVisita);
            $queryVisita->execute([$id]);

            $banco->commit();
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            $banco->rollBack();
            return false;
        }
    }
}
