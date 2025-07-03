<?php

namespace models;

use crud\Create;
use crud\Read;

class Compartilhamentos
{
    private $resultado;

    private $erro;

    public function compartilhar($id_checklist, $id_usuario_dono, $id_usuario_compartilhar)
    {
        $readChecklist = new Read();
        if (!$readChecklist->execute('checklists', ['id_checklist' => $id_checklist, 'deletada' => 0])) {
            $this->erro = "Checklist não encontrada.";
            return false;
        }

        $checklist = $readChecklist->getResult()[0];
        if ($checklist['id_usuario'] != $id_usuario_dono) {
            $this->erro = "Permissão negada. Você não é o proprietário desta checklist.";
            return false;
        }

        $readShare = new Read();
        $readShare->execute('checklists_compartilhados', [
            'id_checklist' => $id_checklist,
            'id_usuario' => $id_usuario_compartilhar
        ]);

        if ($readShare->getRowCount() > 0) {
            $this->resultado = $readShare->getResult();
            return true;
        }

        $dados = [
            'id_checklist' => $id_checklist,
            'id_usuario' => $id_usuario_compartilhar
        ];

        $create = new Create();
        if ($create->execute('checklists_compartilhados', $dados)) {
            $this->resultado = $create->getResult();
            return true;
        } else {
            $this->erro = $create->getError();
            return false;
        }
    }

    public function listarUsuariosCompartilhados($id_checklist)
    {
        $sql = "
            SELECT u.nome, u.email 
            FROM checklists_compartilhados cc 
            JOIN usuarios u ON cc.id_usuario = u.id_usuario 
            WHERE cc.id_checklist = :id_checklist
        ";

        $read = new Read();
        if ($read->query($sql, [':id_checklist' => $id_checklist])) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }

    public function listarChecklistsCompartilhadasComigo($id_usuario)
    {
        $sql = "
            SELECT 
                c.id_checklist, 
                c.titulo,
                c.data_criacao,
                u.nome as dono_checklist
            FROM checklists_compartilhados cc
            JOIN checklists c ON cc.id_checklist = c.id_checklist
            JOIN usuarios u ON c.id_usuario = u.id_usuario
            WHERE cc.id_usuario = :id_usuario AND c.deletada = 0
            ORDER BY cc.data_compartilhamento DESC
        ";

        $read = new Read();
        if ($read->query($sql, [':id_usuario' => $id_usuario])) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }

    public function getResult()
    {
        return $this->resultado;
    }

    public function getError()
    {
        return $this->erro;
    }
}