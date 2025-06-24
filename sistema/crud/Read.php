<?php

namespace crud;

/**
 * Read
 */
class Read
{
    /**
     * @var array|null
     */
    private $resultado;

    /**
     * @var string|null
     */
    private $erro;

    /**
     * @var int
     */
    private $rowCount = 0;

    /**
     * @param string $tabela
     * @param array $condicoes
     * @param string $orderBy
     * @param string $limit
     * @return bool
     */
    public function execute(string $tabela, array $condicoes = [], string $orderBy = '', string $limit = ''): bool
    {
        try {
            $conn = obterConexao();

            $sql = "SELECT * FROM $tabela";

            if (!empty($condicoes)) {
                $where = [];
                foreach ($condicoes as $campo => $valor) {
                    $where[] = "$campo = :$campo";
                }
                $sql .= " WHERE " . implode(' AND ', $where);
            }

            if ($orderBy) {
                $sql .= " ORDER BY $orderBy";
            }

            if ($limit) {
                $sql .= " LIMIT $limit";
            }

            $stmt = $conn->prepare($sql);

            foreach ($condicoes as $campo => $valor) {
                $stmt->bindValue(":$campo", $valor);
            }

            if ($stmt->execute()) {
                $this->resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $this->rowCount = $stmt->rowCount();
                return true;
            } else {
                $this->erro = "Erro ao buscar dados.";
                return false;
            }
        } catch (\PDOException $e) {
            $this->erro = "Erro: " . $e->getMessage();
            return false;
        }
    }

    /**
     * @return array|null
     */
    public function getResult()
    {
        return $this->resultado;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        return $this->erro;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
