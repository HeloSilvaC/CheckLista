<?php

namespace models;

use crud\Create;
use crud\Read;

/**
 * Compartilhamentos
 */
class Compartilhamentos
{
    /**
     * @var mixed Armazena o resultado da última operação bem-sucedida.
     */
    private $resultado;

    /**
     * @var string|null Armazena a mensagem de erro em caso de falha.
     */
    private $erro;

    /**
     * Compartilha uma checklist com outro usuário.
     * Realiza validações para garantir que o usuário logado é o dono da lista
     * e que ela ainda não foi compartilhada com o mesmo destinatário.
     *
     * @param int $id_checklist ID da checklist a ser compartilhada.
     * @param int $id_usuario_dono ID do usuário que está realizando a ação (dono).
     * @param int $id_usuario_compartilhar ID do usuário que receberá o acesso.
     * @return bool Retorna true em caso de sucesso.
     */
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
            $this->erro = "Esta lista já foi compartilhada com o usuário selecionado.";
            return false;
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

    /**
     * Busca e retorna uma lista de todos os usuários com quem uma checklist específica foi compartilhada.
     *
     * @param int $id_checklist O ID da checklist.
     * @return bool Retorna true se a consulta for bem-sucedida.
     */
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

    /**
     * Busca e retorna todas as checklists que foram compartilhadas com o usuário logado.
     *
     * @param int $id_usuario O ID do usuário logado.
     * @return bool Retorna true se a consulta for bem-sucedida.
     */
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

    /**
     * Retorna o resultado da última operação bem-sucedida.
     * @return mixed
     */
    public function getResult()
    {
        return $this->resultado;
    }

    /**
     * Retorna a mensagem de erro se a última operação falhou.
     * @return string|null
     */
    public function getError()
    {
        return $this->erro;
    }
}
