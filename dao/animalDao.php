<?php
class AnimalDao
{
    public function create(Animal $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "INSERT INTO animal(nome, peso, nascimento, cor, observacao, idCliente) VALUES (?, ?, ?, ?, ?, ?)";
            $query = $banco->prepare($sql);
            $query->execute([
                $obj->nome, $obj->peso, $obj->nascimento, $obj->cor, $obj->observacao, $obj->idCliente
            ]);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return false;
        }
    }

    public function getAnimalsByClientId($idCliente)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT idAnimal, nome FROM animal WHERE idCliente = ?";
            $query = $banco->prepare($sql);
            $query->execute([$idCliente]);
            $animais = $query->fetchAll(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $animais;
        } catch (PDOException $e) {
            return [];
        }
    }


    public function readAll()
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM animal ORDER BY nome";
            $resultado = $banco->query($sql);
            $lista = [];
            foreach ($resultado as $linha) {
                $lista[] = new Animal(
                    $linha["idAnimal"],
                    $linha["nome"],
                    $linha["peso"],
                    $linha["nascimento"],
                    $linha["cor"],
                    $linha["observacao"],
                    $linha["idCliente"]
                );
            }
            Conexao::desconectar();
            return $lista;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function readByCliente($idCliente)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM animal WHERE idCliente = :idCliente ORDER BY nome";
            $stmt = $banco->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultado as $linha) {
                $lista[] = new Animal(
                    $linha["idAnimal"],
                    $linha["nome"],
                    $linha["peso"],
                    $linha["nascimento"],
                    $linha["cor"],
                    $linha["observacao"],
                    $linha["idCliente"]
                );
            }
            Conexao::desconectar();
            return $lista;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function readId($id)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM animal WHERE idAnimal = ?";
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

    public function update(Animal $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "UPDATE animal SET nome=?, peso=?, nascimento=?, cor=?, observacao=?, idCliente=? WHERE idAnimal=?";
            $param = [$obj->nome, $obj->peso, $obj->nascimento, $obj->cor, $obj->observacao, $obj->idCliente, $obj->idAnimal];
            $query = $banco->prepare($sql);
            $query->execute($param);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "DELETE FROM animal WHERE idAnimal = ?";
            $query = $banco->prepare($sql);
            $query->execute([$id]);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
