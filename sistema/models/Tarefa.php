<?php

namespace models;

use crud\Create;
use crud\Read;
use crud\Update;

/**
 * Tarefa
 */
class Tarefa
{
    /**
     * @var mixed
     */
    private $resultado;

    /**
     * @var mixed
     */
    private $erro;

    /**
     * Cria uma nova tarefa
     *
     * @param array $dados
     * @return bool
     */
    public function create(array $dados)
    {
        $create = new Create();

        if ($create->execute('tarefa', $dados)) {
            $this->resultado = $create->getResult();
            return $this->lastInsertId();
        } else {
            $this->erro = $create->getError();
            return false;
        }
    }


    /**
     * Lê tarefas com base nos critérios passados
     *
     * @param array $criterios
     * @return bool
     */
    public function read(array $criterios, string $orderBy = '', string $limit = '')
    {
        $read = new Read();

        if ($read->execute('tarefa', $criterios, $orderBy, $limit)) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }


    public function update(array $where, array $dados)
    {
        $update = new Update();
        if ($update->execute('tarefa', $where, $dados)) {
            $this->resultado = $update->getResult();
            return true;
        } else {
            $this->erro = $update->getError();
            return false;
        }
    }


    /**
     * Retorna o resultado da última operação
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->resultado;
    }

    /**
     * Retorna o erro da última operação
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->erro;
    }

    /**
     * Retorna o último ID inserido
     *
     * @return mixed
     */
    public function lastInsertId()
    {
        return $this->resultado['lastInsertId'] ?? null;
    }
}
