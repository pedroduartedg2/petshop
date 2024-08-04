<?php
//alunoDao.php
class FuncionarioDao
{
    public function create(Funcionario $obj)
    {
        try {
            //criando a conexao com o banco
            $banco = Conexao::conectar();
            //criando o comando SQL
            $sql = "INSERT INTO funcionario(nome, email, senha, cpf, telefone1, telefone2, cep, logradouro, numero, bairro, cidade, estado) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            //Prepara o banco para execução
            $query = $banco->prepare($sql);
            //Executa o comando
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
            //conectando com o banco
            $banco = Conexao::conectar();
            //Consulta SQL
            $sql = "SELECT * FROM funcionario ORDER BY nome";
            //Executar o comando e armazenar o resultado
            $resultado = $banco->query($sql);
            //Criando o array
            $lista = [];
            //percorrendo o resultado
            foreach ($resultado as $linha) {
                $lista[] = new Funcionario(
                    $linha["idFuncionario"],
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
            $sql = "SELECT * FROM funcionario WHERE email=? AND senha=?";
            //Executar o comando e armazenar o resultado
            $query = $banco->prepare($sql);
            $query->execute([$email, MD5($senha)]);
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            //Criando o array
            $lista = [];
            //percorrendo o resultado
            if (is_array($resultado)) {
                $lista[] = new Funcionario(
                    $resultado["idFuncionario"],
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
            $sql = "DELETE FROM funcionario WHERE idFuncionario = ?";
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
            $sql = "SELECT * FROM funcionario WHERE idFuncionario = ? ORDER BY nome";
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

    public function update(Funcionario $obj)
    {
        try {
            $banco = Conexao::conectar();
            $sql = "UPDATE funcionario SET nome=?, email=?, cpf=?, telefone1=?, telefone2=?, cep=?, logradouro=?, numero=?, bairro=?, cidade=?, estado=?";
            $param = [$obj->nome, $obj->email, $obj->cpf, $obj->telefone1, $obj->telefone2, $obj->cep, $obj->logradouro, $obj->numero, $obj->bairro, $obj->cidade, $obj->estado];
            if (isset($obj->senha)) {
                $sql .= ", senha=?";
                $param[] = $obj->senha;
            }
            $sql .= " WHERE idFuncionario=?";
            $param[] = $obj->idFuncionario;
            $query = $banco->prepare($sql);

            $query->execute($param);
            Conexao::desconectar();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
