<?php

namespace crud;

/**
 * Update
 */
class Update
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
     * @param string $tabela
     * @param array $dados
     * @param array $condicoes
     * @return bool
     */
    public function execute(string $tabela, array $dados, array $condicoes): bool
    {
        try {
            $conn = obterConexao();

            $set = [];
            foreach ($dados as $campo => $valor) {
                $set[] = "$campo = :$campo";
            }

            $where = [];
            foreach ($condicoes as $campo => $valor) {
                $where[] = "$campo = :cond_$campo";
            }

            $sql = "UPDATE $tabela SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $where);
            $stmt = $conn->prepare($sql);

            foreach ($dados as $campo => $valor) {
                $stmt->bindValue(":$campo", $valor);
            }

            foreach ($condicoes as $campo => $valor) {
                $stmt->bindValue(":cond_$campo", $valor);
            }

            if ($stmt->execute()) {
                $this->resultado = $stmt->rowCount();
                return true;
            } else {
                $this->erro = "Erro ao atualizar.";
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