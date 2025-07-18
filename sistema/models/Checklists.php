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
     * @var mixed Armazena o resultado da última operação bem-sucedida.
     */
    private $resultado;
    /**
     * @var string|null Armazena a mensagem de erro em caso de falha.
     */
    private $erro;

    /**
     * Cria uma nova checklist no banco de dados, associada ao usuário logado.
     *
     * @param string $titulo O título da nova checklist.
     * @param string $descricao A descrição da nova checklist.
     * @return bool Retorna true em caso de sucesso.
     */
    public function create($titulo, $descricao): bool
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
     * Lê checklists do banco de dados com base em um conjunto de critérios.
     * Garante que apenas registros não deletados sejam retornados.
     *
     * @param array $criterios Array associativo de condições para a busca.
     * @return bool Retorna true se a leitura for bem-sucedida.
     */
    public function read(array $criterios): bool
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

    /**
     * Lista os detalhes completos de uma checklist, incluindo suas tarefas e dono.
     * Verifica se o usuário logado é o dono ou se a lista foi compartilhada com ele.
     *
     * @param int $id_usuario ID do usuário logado.
     * @param int $id_checklist ID da checklist a ser buscada.
     * @return bool Retorna true se a consulta for bem-sucedida.
     */
    public function listarPorChecklistComDetalhes($id_usuario, $id_checklist): bool
    {
        $sql = "
        SELECT 
            c.*,
            t.id_tarefa,
            t.descricao AS descricao_tarefa,
            t.concluida,
            t.ordem,
            u.nome AS nome_dono
        FROM checklists c
        JOIN usuarios u ON c.id_usuario = u.id_usuario 
        LEFT JOIN checklists_compartilhados cc ON c.id_checklist = cc.id_checklist AND cc.id_usuario = ?
        LEFT JOIN tarefas t ON t.id_checklist = c.id_checklist AND t.deletada = 0
        WHERE 
            c.id_checklist = ?
            AND c.deletada = 0
            AND (c.id_usuario = ? OR cc.id IS NOT NULL)
        ORDER BY c.data_criacao DESC, t.ordem ASC
    ";

        $read = new Read();
        $params = [$id_usuario, $id_checklist, $id_usuario];

        if ($read->query($sql, $params)) {
            $this->resultado = $read->getResult();
            return true;
        } else {
            $this->erro = $read->getError();
            return false;
        }
    }

    /**
     * Atualiza os dados de uma checklist existente.
     * Apenas o dono da checklist pode atualizá-la.
     *
     * @param int $id_checklist ID da checklist a ser atualizada.
     * @param string $titulo O novo título.
     * @param string $descricao A nova descrição.
     * @return bool Retorna true em caso de sucesso.
     */
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

    /**
     * Realiza um "soft delete" em uma checklist, marcando-a como deletada.
     * Apenas o dono da checklist pode realizar esta ação.
     *
     * @param int $id_checklist ID da checklist a ser deletada.
     * @param int $id_usuario ID do usuário logado (para verificação de permissão).
     * @return bool Retorna true em caso de sucesso.
     */
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
