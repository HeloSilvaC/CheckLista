<?php

namespace models;

use crud\Create;
use crud\Read;

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
