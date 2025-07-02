<?php

namespace models;

use crud\Create;
use crud\Read;

/**
 * Checklists
 */
class Checklists
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
        $id_usuario = usuario_logado_id();

        $dados = [
            'titulo' => $titulo,
            'descricao' => $descricao,
            'id_usuario' => $id_usuario
        ];

        $create = new Create();
        if ($create->execute('checklists', $dados)) {
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
        if ($read->execute('checklists', $criterios)) {
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
