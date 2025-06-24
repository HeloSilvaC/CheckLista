<?php

namespace crud;

/**
 * Create
 */
class Create
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
     * @return bool
     */
    public function execute(string $tabela, array $dados): bool
    {
        try {
            $conn = obterConexao();

            $colunas = implode(', ', array_keys($dados));
            $placeholders = ':' . implode(', :', array_keys($dados));

            $sql = "INSERT INTO $tabela ($colunas) VALUES ($placeholders)";
            $stmt = $conn->prepare($sql);

            foreach ($dados as $chave => $valor) {
                $stmt->bindValue(":$chave", $valor);
            }

            if ($stmt->execute()) {
                $this->resultado = $conn->lastInsertId();
                return true;
            } else {
                $this->erro = "Erro ao inserir no banco.";
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