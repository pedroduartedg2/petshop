<?php
//alunoDao.php
class ServicoDao
{
    public function create(Servico $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "INSERT INTO servico(nome, descricao, preco) VALUES (?, ?, ?)";
            $query = $banco->prepare($sql);
            $query->execute([
                $obj->nome, $obj->descricao, $obj->preco
            ]);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return false;
        }
    }

    public function readAll()
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM servico ORDER BY nome";
            $resultado = $banco->query($sql);
            $lista = [];
            foreach ($resultado as $linha) {
                $lista[] = new Servico(
                    $linha["idServico"],
                    $linha["nome"],
                    $linha["descricao"],
                    $linha["preco"]
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
            $sql = "SELECT * FROM servico WHERE idServico = ?";
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

    public function update(Servico $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "UPDATE servico SET nome=?, descricao=?, preco=? WHERE idServico=?";
            $param = [$obj->nome, $obj->descricao, $obj->preco, $obj->idServico];
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
            $sql = "DELETE FROM servico WHERE idServico = ?";
            $query = $banco->prepare($sql);
            $query->execute([$id]);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
