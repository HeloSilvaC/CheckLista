<?php

namespace crud;

/**
 * Update
 */
class Update
{
    /**
     * Armazena o número de linhas afetadas pela operação de atualização.
     * @var int
     */
    private $resultado;

    /**
     * Armazena a mensagem de erro em caso de falha na operação.
     * @var string
     */
    private $erro;

    /**
     * Prepara e executa uma instrução SQL UPDATE na tabela especificada.
     *
     * @param string $tabela A tabela a ser atualizada.
     * @param array $dados Um array associativo com os dados a serem atualizados (SET).
     * @param array $condicoes Um array associativo para a cláusula WHERE.
     * @return bool Retorna true em caso de sucesso e false em caso de falha.
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
            $this->erro = "Erro de PDO: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retorna o número de linhas afetadas pela operação de atualização.
     * @return int|null
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
}
