<?php

namespace crud;

/**
 * Delete
 */
class Delete
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
     * @param array $condicoes
     * @return bool
     */
    public function execute(string $tabela, array $condicoes): bool
    {
        try {
            $conn = obterConexao();

            $where = [];
            foreach ($condicoes as $campo => $valor) {
                $where[] = "$campo = :$campo";
            }

            $sql = "DELETE FROM $tabela WHERE " . implode(' AND ', $where);
            $stmt = $conn->prepare($sql);

            foreach ($condicoes as $campo => $valor) {
                $stmt->bindValue(":$campo", $valor);
            }

            if ($stmt->execute()) {
                $this->resultado = $stmt->rowCount();
                return true;
            } else {
                $this->erro = "Erro ao deletar.";
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