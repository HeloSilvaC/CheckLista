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
     * @var mixed Armazena o resultado da última operação bem-sucedida.
     */
    private $resultado;
    /**
     * @var string|null Armazena a mensagem de erro em caso de falha.
     */
    private $erro;

    /**
     * Cria um novo usuário, verificando se o e-mail já existe e hasheando a senha.
     *
     * @param string $nome O nome do usuário.
     * @param string $email O e-mail do usuário (deve ser único).
     * @param string $senha A senha do usuário (será hasheada).
     * @return bool Retorna true em caso de sucesso.
     */
    public function create($nome, $email, $senha): bool
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
     * Autentica um usuário, verifica a senha e inicia a sessão.
     *
     * @param string $email O e-mail fornecido para login.
     * @param string $senha A senha fornecida para login.
     * @return bool Retorna true se a autenticação for bem-sucedida.
     */
    public function login($email, $senha): bool
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
            $this->erro = "Erro de PDO: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Lê usuários do banco de dados com base em um conjunto de critérios.
     *
     * @param array $criterios Array associativo de condições para a busca.
     * @return bool Retorna true se a leitura for bem-sucedida.
     */
    public function read(array $criterios): bool
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
     * Atualiza os dados de um usuário, com validações para e-mail e dados inalterados.
     *
     * @param int $id_usuario O ID do usuário a ser atualizado.
     * @param string $nome O novo nome.
     * @param string $email O novo e-mail.
     * @param string $senha A nova senha (opcional).
     * @return bool Retorna true em caso de sucesso.
     */
    public function update($id_usuario, $nome, $email, $senha): bool
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

        if ($nome === $usuarioAtual['nome'] && $email === $usuarioAtual['email'] && $senhaIgual) {
            $this->erro = "Você precisa alterar pelo menos um campo.";
            return false;
        }

        $dados = ['nome' => $nome, 'email' => $email];
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

    /**
     * Lista todos os usuários cadastrados, exceto o que está logado.
     * Útil para a funcionalidade de compartilhamento.
     *
     * @param int $id_usuario_logado O ID do usuário logado.
     * @return bool Retorna true se a consulta for bem-sucedida.
     */
    public function listarTodosExcetoLogado($id_usuario_logado): bool
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
     * Retorna o resultado da última operação bem-sucedida.
     * @return mixed
     */
    public function getResult()
    {
        return $this->resultado;
    }

    /**
     * Retorna a mensagem de erro se a última operação falhou.
     * @return string|null
     */
    public function getError()
    {
        return $this->erro;
    }
}
