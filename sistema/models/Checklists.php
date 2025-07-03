<?php

namespace models;

use crud\Create;
use crud\Read;
use crud\Update;

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
        $criterios['deletada'] = 0;

        $read = new Read();
        if ($read->execute('checklists', $criterios)) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }

    public function listarPorChecklistComDetalhes($id_usuario, $id_checklist): bool
    {
        $sql = "
SELECT 
    c.*,
    t.id_tarefa,
    t.descricao AS descricao_tarefa,
    t.concluida,
    t.ordem,
    t.deletada AS deletada_tarefa,
    t.data_criacao AS data_criacao_tarefa
FROM checklists c
LEFT JOIN tarefas t ON t.id_checklist = c.id_checklist AND t.deletada = 0
WHERE c.id_usuario = :id_usuario
  AND c.id_checklist = :id_checklist
  AND c.deletada = 0
ORDER BY c.data_criacao DESC, t.ordem ASC
";

        $read = new Read();
        if ($read->query($sql, [
            ':id_usuario' => $id_usuario,
            ':id_checklist' => $id_checklist
        ])) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }

    public function update($id_checklist, $titulo, $descricao): bool
    {
        $id_usuario = usuario_logado_id();

        $dados = [
            'titulo' => $titulo,
            'descricao' => $descricao,
            'ultima_atualizacao' => date('Y-m-d H:i:s'),
            'atualizado_por' => $id_usuario
        ];

        $condicoes = [
            'id_checklist' => $id_checklist,
            'id_usuario' => $id_usuario
        ];

        $update = new Update();
        if ($update->execute('checklists', $dados, $condicoes)) {
            $this->resultado = $update->getResult();
            return true;
        } else {
            $this->erro = $update->getError();
            return false;
        }
    }


    public function softDelete($id_checklist, $id_usuario): bool
    {
        $update = new Update();
        $dados = [
            'deletada' => 1,
            'data_exclusao' => date('Y-m-d H:i:s'),
            'atualizado_por' => $id_usuario,
            'ultima_atualizacao' => date('Y-m-d H:i:s')
        ];
        $condicoes = [
            'id_checklist' => $id_checklist,
            'id_usuario' => $id_usuario
        ];

        if ($update->execute('checklists', $dados, $condicoes)) {
            $this->resultado = $update->getResult();
            return true;
        } else {
            $this->erro = $update->getError();
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