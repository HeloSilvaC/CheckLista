<?php

namespace models;

/**
 *
 */
class Checklist
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
     * @param $titulo
     * @param $descricao
     * @return bool
     */
    public function create($titulo, $descricao)
    {
        try {
            $conn = obterConexao();

            $sql = "INSERT INTO checklist (titulo, descricao, idUsuario) 
                    VALUES (:titulo, :descricao, :idUsuario)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $usuario_id = usuario_logado_id();
            $stmt->bindParam(':idUsuario', $usuario_id);

            if ($stmt->execute()) {
                $this->resultado = "Novo registro criado com sucesso";
                return true;
            } else {
                $this->erro = "Erro ao criar checklist";
                return false;
            }
        } catch (\PDOException $e) {
            $this->erro = "Erro: " . $e->getMessage();
            return false;
        }
    }

    /**
     * @param $usuario_id
     * @return bool
     */
    public function read($usuario_id)
    {
        try {
            $conn = obterConexao();

            $sql = "SELECT * FROM checklist WHERE idUsuario = :idUsuario";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idUsuario', $usuario_id);

            if ($stmt->execute()) {
                $this->resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                return true;
            } else {
                $this->erro = "Erro ao buscar checklists";
                return false;
            }
        } catch (\PDOException $e) {
            $this->erro = "Erro: " . $e->getMessage();
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
