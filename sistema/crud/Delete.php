<?php

namespace crud;

/**
 * Delete
 */
class Delete
{
    /**
     * Armazena o número de linhas afetadas pela operação de exclusão.
     * @var int
     */
    private $resultado;

    /**
     * Armazena a mensagem de erro em caso de falha na operação.
     * @var string
     */
    private $erro;

    /**
     * Prepara e executa uma instrução SQL DELETE na tabela especificada,
     * com base em um array de condições.
     *
     * @param string $tabela O nome da tabela de onde os registros serão removidos.
     * @param array $condicoes Um array associativo para a cláusula WHERE (ex: ['id' => 1, 'status' => 'inativo']).
     * @return bool Retorna true em caso de sucesso e false em caso de falha.
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
            $this->erro = "Erro de PDO: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retorna o número de linhas afetadas pela operação de exclusão.
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
