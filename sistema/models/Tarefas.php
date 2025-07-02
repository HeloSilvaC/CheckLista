<?php

namespace models;

use crud\Create;
use crud\Read;
use crud\Update;
use crud\Delete;

/**
 * Tarefas
 */
class Tarefas
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
     * Cria uma nova tarefa
     *
     * @param $id_checklist
     * @param $descricao
     * @param int $ordem
     * @return bool
     */
    public function create($id_checklist, $descricao, int $ordem)
    {
        $id_usuario = usuario_logado_id();

        $dados = [
            'id_checklist' => $id_checklist,
            'descricao' => $descricao,
            'id_usuario' => $id_usuario,
            'concluida' => 0,
            'data_criacao' => date('Y-m-d H:i:s'),
            'ultima_atualizacao' => date('Y-m-d H:i:s'),
            'ordem' => $ordem,
            'restaurada' => 0,
            'deletada' => 0
        ];

        $create = new Create();
        if ($create->execute('tarefas', $dados)) {
            $this->resultado = $create->getResult();
            return true;
        } else {
            $this->erro = $create->getError();
            return false;
        }
    }

    /**
     * Lê as tarefas com base nos critérios fornecidos
     *
     * @param array $criterios
     * @return bool
     */
    public function read(array $criterios)
    {
        $orderBy = 'ordem ASC';
        $read = new Read();
        $criterios['deletada'] = 0;

        if ($read->execute('tarefas', $criterios, $orderBy)) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }

    /**
     * Calcula a próxima posição de ordem para uma nova tarefa em uma checklist.
     *
     * @param int $id_checklist O ID da checklist.
     * @return int A próxima posição na ordem.
     */
    public function getProximaOrdem(int $id_checklist): int
    {
        $read = new Read();
        $read->execute(
            'tarefas',
            ['id_checklist' => $id_checklist],
            '',
            '',
            'MAX(ordem) AS max_ordem'
        );

        if ($read->getResult() && $read->getResult()[0]['max_ordem'] !== null) {
            $maxOrdem = $read->getResult()[0]['max_ordem'];
            return (int)$maxOrdem + 1;
        }

        return 1;
    }



    /**
     * Atualiza uma tarefa existente
     *
     * @param $id_tarefa
     * @param array $dados
     * @return bool
     */
    public function update($id_tarefa, array $dados)
    {
        $update = new Update();

        $condicoes = ['id_tarefa' => $id_tarefa];

        $id_usuario = usuario_logado_id();
        $condicoes['id_usuario'] = $id_usuario;

        if ($update->execute('tarefas', $dados, $condicoes)) {
            $this->resultado = $update->getResult();
            return true;
        } else {
            $this->erro = $update->getError();
            return false;
        }
    }

    /**
     * Exclui uma tarefa (soft delete)
     *
     * @param $id_tarefa
     * @return bool
     */
    public function delete($id_tarefa)
    {
        $dados = ['deletada' => 1];
        return $this->update($id_tarefa, $dados);
    }

    /**
     * Restaura uma tarefa deletada
     *
     * @param $id_tarefa
     * @return bool
     */
    public function restore($id_tarefa)
    {
        $dados = ['deletada' => 0, 'restaurada' => 1];
        return $this->update($id_tarefa, $dados);
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
