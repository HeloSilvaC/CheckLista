<?php

namespace models;

use crud\Create;
use crud\Read;
use crud\Update;

/**
 * Usuarios
 */
class Usuarios
{
    /**
     * @var
     */
    private $resultado;
    /**
     * @var
     */
    private $erro;

    /**
     * @param $nome
     * @param $email
     * @param $senha
     * @return bool
     */
    public function create($nome, $email, $senha)
    {
        $read = new Read();
        $read->execute('usuarios', ['email' => $email]);

        if ($read->getRowCount() > 0) {
            $this->erro = "E-mail já cadastrado.";
            return false;
        }

        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $dados = [
            'nome' => $nome,
            'email' => $email,
            'senha' => $hash
        ];

        $create = new Create();
        if ($create->execute('usuarios', $dados)) {
            $this->resultado = $create->getResult();
            return true;
        } else {
            $this->erro = $create->getError();
            return false;
        }
    }

    /**
     * @param string $email
     * @param string $senha
     * @return bool
     */
    public function login($email, $senha)
    {
        try {
            $read = new Read();
            $read->execute('usuarios', ['email' => $email]);

            if ($read->getRowCount() === 1) {
                $usuario = $read->getResult()[0];

                if (password_verify($senha, $usuario['senha'])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['nome_usuario'] = $usuario['nome'];

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

    /**
     * @param array $criterios
     * @return bool
     */
    public function read(array $criterios)
    {
        $read = new Read();
        if ($read->execute('usuarios', $criterios)) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }

    public function update($id_usuario, $nome, $email, $senha)
    {
        $read = new Read();
        if (!$read->execute('usuarios', ['id_usuario' => $id_usuario])) {
            $this->erro = "Usuário não encontrado.";
            return false;
        }
        $usuarioAtual = $read->getResult()[0];

        $read->execute('usuarios', ['email' => $email]);
        if ($read->getRowCount() > 0) {
            $usuarioExistente = $read->getResult()[0];
            if ($usuarioExistente['id_usuario'] != $id_usuario) {
                $this->erro = "Este e-mail já está em uso por outro usuário.";
                return false;
            }
        }

        $senhaHash = !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : null;
        $senhaIgual = empty($senha) || password_verify($senha, $usuarioAtual['senha']);

        if (
            $nome === $usuarioAtual['nome'] &&
            $email === $usuarioAtual['email'] &&
            $senhaIgual
        ) {
            $this->erro = "Você precisa alterar pelo menos um campo.";
            return false;
        }

        $dados = [
            'nome' => $nome,
            'email' => $email,
        ];

        if (!empty($senha)) {
            $dados['senha'] = $senhaHash;
        }

        $update = new Update();
        if ($update->execute('usuarios', $dados, ['id_usuario' => $id_usuario])) {
            $this->resultado = $update->getResult();
            $_SESSION['nome_usuario'] = $nome;
            return true;
        } else {
            $this->erro = $update->getError();
            return false;
        }
    }

    public function listarTodosExcetoLogado($id_usuario_logado)
    {
        $sql = "SELECT id_usuario, nome FROM usuarios WHERE id_usuario != :id_usuario_logado ORDER BY nome ASC";

        $read = new Read();
        if ($read->query($sql, [':id_usuario_logado' => $id_usuario_logado])) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->resultado;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->erro;
       }
}
