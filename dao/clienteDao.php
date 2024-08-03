<?php
// clienteDao.php
class ClienteDao
{
    public function create(Cliente $obj)
    {
        try {
            // Criando a conexão com o banco
            $banco = Conexao::conectar();
            // Criando o comando SQL
            $sql = "INSERT INTO cliente(nome, email, senha, cpf, telefone1, telefone2, cep, logradouro, numero, bairro, cidade, estado) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            // Preparando o banco para execução
            $query = $banco->prepare($sql);
            // Executando o comando
            $query->execute([
                $obj->nome, $obj->email, $obj->senha, $obj->cpf, $obj->telefone1, $obj->telefone2, $obj->cep, $obj->logradouro, $obj->numero, $obj->bairro, $obj->cidade, $obj->estado
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
            // Conectando com o banco
            $banco = Conexao::conectar();
            // Consulta SQL
            $sql = "SELECT * FROM cliente ORDER BY nome";
            // Executando o comando e armazenando o resultado
            $resultado = $banco->query($sql);
            // Criando o array
            $lista = [];
            // Percorrendo o resultado
            foreach ($resultado as $linha) {
                $lista[] = new Cliente(
                    $linha["idCliente"],
                    $linha["nome"],
                    $linha["email"],
                    $linha["senha"],
                    $linha["cpf"],
                    $linha["telefone1"],
                    $linha["telefone2"],
                    $linha["cep"],
                    $linha["logradouro"],
                    $linha["numero"],
                    $linha["bairro"],
                    $linha["cidade"],
                    $linha["estado"]
                );
            }
            Conexao::desconectar();
            return $lista;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function login($email, $senha)
    {
        try {
            // Conectando com o banco
            $banco = Conexao::conectar();
            // Consulta SQL
            $sql = "SELECT * FROM cliente WHERE email=? AND senha=?";
            // Preparando e executando o comando
            $query = $banco->prepare($sql);
            $query->execute([$email, MD5($senha)]);
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            // Criando o array
            $lista = [];
            // Percorrendo o resultado
            if (is_array($resultado)) {
                $lista[] = new Cliente(
                    $resultado["idCliente"],
                    $resultado["nome"],
                    $resultado["email"],
                    $resultado["senha"],
                    $resultado["cpf"],
                    $resultado["telefone1"],
                    $resultado["telefone2"],
                    $resultado["cep"],
                    $resultado["logradouro"],
                    $resultado["numero"],
                    $resultado["bairro"],
                    $resultado["cidade"],
                    $resultado["estado"]
                );
            }

            Conexao::desconectar();
            return $lista;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function delete($id)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "DELETE FROM cliente WHERE idCliente = ?";
            $query = $banco->prepare($sql);
            $query->execute([$id]);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function readId($id)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT * FROM cliente WHERE idCliente = ? ORDER BY nome";
            $query = $banco->prepare($sql);
            $query->execute([$id]);
            $lista = $query->fetch(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $lista;
        } catch (PDOException $e) {
            //echo $e->getMessage();
            return null;
        }
    }

    public function update(Cliente $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "UPDATE cliente SET nome=?, email=?, cpf=?, telefone1=?, telefone2=?, cep=?, logradouro=?, numero=?, bairro=?, cidade=?, estado=?";
            $param = [$obj->nome, $obj->email, $obj->cpf, $obj->telefone1, $obj->telefone2, $obj->cep, $obj->logradouro, $obj->numero, $obj->bairro, $obj->cidade, $obj->estado];
            if ($obj->senha) {
                $sql .= ", senha=?";
                $param[] = MD5($obj->senha);
            }
            $sql .= " WHERE idCliente=?";
            $param[] = $obj->idCliente;
            $query = $banco->prepare($sql);

            $query->execute($param);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getClienteNomeById($id)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "SELECT nome FROM cliente WHERE idCliente = ?";
            $query = $banco->prepare($sql);
            $query->execute([$id]);
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            Conexao::desconectar();
            return $resultado ? $resultado['nome'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
