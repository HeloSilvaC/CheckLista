<?php

namespace crud;

/**
 * Create
 */
class Create
{
    /**
     * Armazena o ID do último registro inserido em caso de sucesso.
     * @var int|string
     */
    private $resultado;

    /**
     * Armazena a mensagem de erro em caso de falha na operação.
     * @var string
     */
    private $erro;

    /**
     * Prepara e executa uma instrução SQL INSERT na tabela especificada com os dados fornecidos.
     *
     * @param string $tabela O nome da tabela onde os dados serão inseridos.
     * @param array $dados Um array associativo onde as chaves são as colunas e os valores são os dados.
     * @return bool Retorna true em caso de sucesso e false em caso de falha.
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
            $this->erro = "Erro de PDO: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retorna o ID do registro inserido após uma execução bem-sucedida.
     * @return mixed
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