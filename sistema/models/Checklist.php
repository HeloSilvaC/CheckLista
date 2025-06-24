<?php

namespace models;

use crud\Create;
use crud\Read;

/**
 * Checklist
 */
class Checklist
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
     * @param $titulo
     * @param $descricao
     * @return bool
     */
    public function create($titulo, $descricao)
    {
        $usuario_id = usuario_logado_id();

        $dados = [
            'titulo' => $titulo,
            'descricao' => $descricao,
            'idUsuario' => $usuario_id
        ];

        $create = new Create();
        if ($create->execute('checklist', $dados)) {
            $this->resultado = $create->getResult();
            return true;
        } else {
            $this->erro = $create->getError();
            return false;
        }
    }

    /**
     * @param array $criterios
     * @return bool
     */
    public function read(array $criterios)
    {
        $read = new Read();
        if ($read->execute('checklist', $criterios)) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
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
