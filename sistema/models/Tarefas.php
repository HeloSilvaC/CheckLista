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
     * @var mixed Armazena o resultado da última operação bem-sucedida.
     */
    private $resultado;
    /**
     * @var string|null Armazena a mensagem de erro em caso de falha.
     */
    private $erro;

    /**
     * Cria uma nova tarefa associada a uma checklist.
     *
     * @param int $id_checklist ID da checklist à qual a tarefa pertence.
     * @param string $descricao O texto da tarefa.
     * @param int $ordem A posição da tarefa na lista.
     * @return bool Retorna true em caso de sucesso.
     */
    public function create($id_checklist, $descricao, int $ordem): bool
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
     * Lê as tarefas com base nos critérios fornecidos, ordenadas por padrão.
     *
     * @param array $criterios Array associativo de condições para a busca.
     * @return bool Retorna true se a leitura for bem-sucedida.
     */
    public function read(array $criterios): bool
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
     * Atualiza os dados de uma tarefa existente.
     *
     * @param int $id_tarefa O ID da tarefa a ser atualizada.
     * @param array $dados Os novos dados a serem salvos.
     * @return bool Retorna true em caso de sucesso.
     */
    public function update($id_tarefa, array $dados): bool
    {
        $update = new Update();
        $condicoes = ['id_tarefa' => $id_tarefa];

        if ($update->execute('tarefas', $dados, $condicoes)) {
            $this->resultado = $update->getResult();
            return true;
        } else {
            $this->erro = $update->getError();
            return false;
        }
    }

    /**
     * Exclui uma tarefa (soft delete) reutilizando o método de update.
     *
     * @param int $id_tarefa O ID da tarefa a ser deletada.
     * @return bool Retorna true em caso de sucesso.
     */
    public function delete($id_tarefa): bool
    {
        $dados = ['deletada' => 1];
        return $this->update($id_tarefa, $dados);
    }

    /**
     * Restaura uma tarefa deletada (soft delete) reutilizando o método de update.
     *
     * @param int $id_tarefa O ID da tarefa a ser restaurada.
     * @return bool Retorna true em caso de sucesso.
     */
    public function restore($id_tarefa): bool
    {
        $dados = ['deletada' => 0, 'restaurada' => 1];
        return $this->update($id_tarefa, $dados);
    }

    /**
     * Realiza um "soft delete" em todas as tarefas de uma checklist específica.
     *
     * @param int $id_checklist O ID da checklist cujas tarefas serão deletadas.
     * @param int $id_usuario O ID do usuário (para verificação de permissão).
     * @return bool Retorna true em caso de sucesso.
     */
    public function softDeleteByChecklist($id_checklist, $id_usuario): bool
    {
        $update = new Update();
        $dados = ['deletada' => 1];
        $condicoes = [
            'id_checklist' => $id_checklist,
            'id_usuario' => $id_usuario
        ];

        if ($update->execute('tarefas', $dados, $condicoes)) {
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
