<?php

namespace models;

class Usuarios
{
    private $resultado;
    private $erro;

    public function create($nome, $email, $senha)
    {
        try {
            $conn = obterConexao();

            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt->bindParam(':senha', $hash);

            if ($stmt->execute()) {
                $this->resultado = "Novo registro criado com sucesso";
                return true;
            } else {
                $this->erro = "Erro ao criar usuário";
                return false;
            }
        } catch (\PDOException $e) {
            $this->erro = "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function login($email, $senha)
    {
        try {
            $conn = obterConexao();
            $sql = "SELECT idUsuario, nome, email, senha FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

                if (password_verify($senha, $usuario['senha'])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['usuario_id'] = $usuario['idUsuario'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];

                    $this->resultado = $usuario;
                    return true;
                } else {
                    $this->erro = "Senha incorreta.";
                    return false;
                }
            } else {
                $this->erro = "Usuário não encontrado.";
                return false;
            }

        } catch (\PDOException $e) {
            $this->erro = "Erro: " . $e->getMessage();
            return false;
        }
    }


    public function getResultado()
    {
        return $this->resultado;
    }

    public function getErro()
    {
        return $this->erro;
    }
}