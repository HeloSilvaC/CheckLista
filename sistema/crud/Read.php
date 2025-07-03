<?php

namespace crud;

class Read
{
    private $resultado;
    private $erro;
    private $rowCount = 0;

    /**
     * SELECT genérico com flexibilidade total
     */
    public function execute(
        string $tabelaOuSQL,
        array $condicoes = [],
        string $orderBy = '',
        string $limit = '',
        string $select = '*',
        string $wherePersonalizado = ''
    ): bool {
        try {
            $conn = obterConexao();

            // Se for uma query completa, use query()
            if (stripos($tabelaOuSQL, 'SELECT') === 0) {
                return $this->query($tabelaOuSQL, $condicoes);
            }

            $sql = "SELECT $select FROM $tabelaOuSQL";

            // WHERE automático
            if (!empty($condicoes)) {
                $where = [];
                foreach ($condicoes as $campo => $valor) {
                    $where[] = "$campo = :$campo";
                }
                $sql .= " WHERE " . implode(' AND ', $where);
            } elseif ($wherePersonalizado) {
                $sql .= " WHERE $wherePersonalizado";
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
     * Executa uma query SQL completa com binds
     */
    public function query(string $sql, array $params = []): bool
    {
        try {
            $conn = obterConexao();

            $this->stmt = $conn->prepare($sql);

            if ($this->stmt->execute($params)) {
                $this->resultado = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
                $this->rowCount = $this->stmt->rowCount();
                return true;
            } else {
                $this->erro = "Erro ao executar query.";
                return false;
            }

        } catch (\PDOException $e) {
            $this->erro = "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function getResult()
    {
        return $this->resultado;
    }

    public function getError()
    {
        return $this->erro;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
