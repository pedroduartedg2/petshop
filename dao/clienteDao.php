<?php
//alunoDao.php
class ClienteDao
{
    public function create(Cliente $obj)
    {
        try {
            //criando a conexao com o banco
            $banco = Conexao::conectar();
            //criando o comando SQL
            $sql = "INSERT INTO cliente(nome, email, senha, cpf, telefone1, telefone2, cep, logradouro, numero, cidade, estado) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            //Prepara o banco para execução
            $query = $banco->prepare($sql);
            //Executa o comando
            $query->execute([
                $obj->nome, $obj->email, $obj->senha, $obj->cpf, $obj->telefone1, $obj->telefone2, $obj->cep, $obj->logradouro, $obj->numero, $obj->cidade, $obj->estado
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
            //conectando com o banco
            $banco = Conexao::conectar();
            //Consulta SQL
            $sql = "SELECT * FROM cliente ORDER BY nome";
            //Executar o comando e armazenar o resultado
            $resultado = $banco->query($sql);
            //Criando o array
            $lista = [];
            //percorrendo o resultado
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
                    $linha["cidade"],
                    $linha["estado"]
                );
            } //fim foreach
            Conexao::desconectar();
            return $lista;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function login($email, $senha)
    {
        try {
            //conectando com o banco
            $banco = Conexao::conectar();
            //Consulta SQL
            $sql = "SELECT * FROM cliente WHERE email=? AND senha=?";
            //Executar o comando e armazenar o resultado
            $query = $banco->prepare($sql);
            $query->execute([$email, MD5($senha)]);
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            //Criando o array
            $lista = [];
            //percorrendo o resultado
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
            $sql = "DELETE FROM cliente WHERE idaluno = ?";
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
            $sql = "UPDATE cliente SET nome=?, email=?, cpf=?, telefone1=?, telefone2=?, cep=?, logradouro=?, numero=?, cidade=?, estado=?";
            $param = [$obj->nome, $obj->email, $obj->cpf, $obj->telefone1, $obj->telefone2, $obj->cep, $obj->logradouro, $obj->numero, $obj->cidade, $obj->estado];
            if (isset($obj->senha)) {
                $sql .= ", senha=?";
                $param[] = $obj->senha;
            }
            $sql .= " WHERE idaluno=?";
            $param[] = $obj->idaluno;
            $query = $banco->prepare($sql);

            $query->execute($param);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
