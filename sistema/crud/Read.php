<?php

namespace crud;

/**
 * Read
 */
class Read
{
    /**
     * Armazena o resultado da consulta (um array de registros).
     * @var array|null
     */
    private $resultado;

    /**
     * Armazena a mensagem de erro em caso de falha na operação.
     * @var string|null
     */
    private $erro;

    /**
     * Armazena o número de linhas retornadas pela consulta.
     * @var int
     */
    private $rowCount = 0;

    /**
     * Executa uma consulta SELECT. Pode funcionar como um construtor de query simples
     * ou delegar para o método query() se uma instrução SQL completa for passada.
     *
     * @param string $tabelaOuSQL O nome da tabela ou uma query SQL completa.
     * @param array $condicoes Array associativo para a cláusula WHERE (ex: ['id_usuario' => 5]).
     * @param string $orderBy Cláusula ORDER BY (ex: "data_criacao DESC").
     * @param string $limit Cláusula LIMIT (ex: "10" ou "5, 10").
     * @param string $select Colunas a serem selecionadas (ex: "id, nome, email").
     * @param string $wherePersonalizado Uma string para uma cláusula WHERE mais complexa.
     * @return bool Retorna true em caso de sucesso e false em caso de falha.
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

            // Verifica se o primeiro argumento já é uma query SQL completa
            if (stripos(trim($tabelaOuSQL), 'SELECT') === 0) {
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
            $this->erro = "Erro de PDO: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Executa uma instrução SQL completa e complexa, com suporte a parâmetros.
     * Ideal para queries com JOINs, sub-consultas, etc.
     *
     * @param string $sql A instrução SQL completa a ser executada.
     * @param array $params Um array de parâmetros para vincular à query.
     * @return bool Retorna true em caso de sucesso e false em caso de falha.
     */
    public function query(string $sql, array $params = []): bool
    {
        try {
            $conn = obterConexao();
            $stmt = $conn->prepare($sql);

            if ($stmt->execute($params)) {
                $this->resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $this->rowCount = $stmt->rowCount();
                return true;
            } else {
                $this->erro = "Erro ao executar query.";
                return false;
            }

        } catch (\PDOException $e) {
            $this->erro = "Erro de PDO: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retorna o resultado da consulta como um array de dados.
     * @return array|null
     */
    public function getResult()
    {
        return $this->resultado;
    }

    /**
     * Retorna a mensagem de erro se a operação falhar.
     * @return string|null
     */
    public function getError()
    {
        return $this->erro;
    }

    /**
     * Retorna o número de linhas encontradas pela consulta.
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}