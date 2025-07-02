-- -------------------------------------------------------------
-- Tabela de Usuários
-- -------------------------------------------------------------
CREATE TABLE usuarios (
                          id_usuario INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do usuário',
                          nome VARCHAR(255) NOT NULL COMMENT 'Nome completo do usuário',
                          email VARCHAR(255) NOT NULL COMMENT 'Email do usuário (único)',
                          senha VARCHAR(255) NOT NULL COMMENT 'Senha criptografada do usuário',
                          PRIMARY KEY (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Tabela que armazena os dados dos usuários do sistema';

-- -------------------------------------------------------------
-- Tabela de Checklists
-- -------------------------------------------------------------
CREATE TABLE checklists (
                            id_checklist INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único da checklist',
                            titulo VARCHAR(255) NOT NULL COMMENT 'Título da checklist',
                            descricao VARCHAR(255) DEFAULT NULL COMMENT 'Descrição opcional da checklist',
                            id_usuario INT(11) NOT NULL COMMENT 'Usuário dono principal da checklist',
                            criado_por INT(11) DEFAULT NULL COMMENT 'ID do usuário que criou a checklist',
                            atualizado_por INT(11) DEFAULT NULL COMMENT 'ID do último usuário que atualizou a checklist',
                            data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de criação da checklist',
                            ultima_atualizacao DATETIME DEFAULT NULL COMMENT 'Última atualização feita na checklist',
                            deletada TINYINT(1) DEFAULT 0 COMMENT '1 = checklist marcada como excluída',
                            restaurada TINYINT(1) DEFAULT 0 COMMENT '1 = checklist foi restaurada',
                            data_exclusao DATETIME DEFAULT NULL COMMENT 'Data da exclusão (soft delete)',
                            PRIMARY KEY (id_checklist),
                            KEY fk_checklists_usuarios_idx (id_usuario),
                            CONSTRAINT fk_checklists_usuarios FOREIGN KEY (id_usuario)
                                REFERENCES usuarios (id_usuario)
                                ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Tabela que armazena listas de tarefas com suporte a histórico e compartilhamento';

-- -------------------------------------------------------------
-- Tabela de Tarefas
-- -------------------------------------------------------------
CREATE TABLE tarefas (
                         id_tarefa INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único da tarefa',
                         id_checklist INT(11) NOT NULL COMMENT 'Checklists à qual a tarefa pertence',
                         id_usuario INT(11) NOT NULL COMMENT 'Usuário que criou a tarefa',
                         descricao VARCHAR(255) NOT NULL COMMENT 'Descrição da tarefa',
                         concluida TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = pendente, 1 = concluída',
                         data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de criação da tarefa',
                         data_conclusao DATETIME DEFAULT NULL COMMENT 'Data em que a tarefa foi concluída',
                         ultima_atualizacao DATETIME DEFAULT NULL COMMENT 'Data da última modificação da tarefa',
                         ordem INT(11) DEFAULT 0 COMMENT 'Ordem da tarefa na lista (para reordenação)',
                         restaurada TINYINT(1) DEFAULT 0 COMMENT '1 = tarefa foi restaurada do histórico',
                         deletada TINYINT(1) DEFAULT 0 COMMENT '1 = tarefa marcada como deletada (soft delete)',
                         PRIMARY KEY (id_tarefa),
                         KEY fk_tarefas_checklists_idx (id_checklist),
                         KEY fk_tarefas_usuarios_idx (id_usuario),
                         CONSTRAINT fk_tarefas_checklists FOREIGN KEY (id_checklist)
                             REFERENCES checklists (id_checklist)
                             ON DELETE CASCADE ON UPDATE CASCADE,
                         CONSTRAINT fk_tarefas_usuarios FOREIGN KEY (id_usuario)
                             REFERENCES usuarios (id_usuario)
                             ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Tabela que armazena as tarefas individuais de cada checklist';

-- -------------------------------------------------------------
-- Tabela de Compartilhamento de Checklists
-- -------------------------------------------------------------
CREATE TABLE checklists_compartilhados (
                                           id INT(11) NOT NULL AUTO_INCREMENT,
                                           id_checklist INT(11) NOT NULL COMMENT 'Checklists compartilhada',
                                           id_usuario INT(11) NOT NULL COMMENT 'Usuário com quem a checklist foi compartilhada',
                                           data_compartilhamento DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data em que foi compartilhada',
                                           PRIMARY KEY (id),
                                           KEY fk_compartilhado_checklist_idx (id_checklist),
                                           KEY fk_compartilhado_usuario_idx (id_usuario),
                                           CONSTRAINT fk_compartilhado_checklist FOREIGN KEY (id_checklist)
                                               REFERENCES checklists (id_checklist)
                                               ON DELETE CASCADE ON UPDATE CASCADE,
                                           CONSTRAINT fk_compartilhado_usuario FOREIGN KEY (id_usuario)
                                               REFERENCES usuarios (id_usuario)
                                               ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Tabela que armazena os usuários com quem cada checklist foi compartilhada';