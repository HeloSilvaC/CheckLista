#tabela usuario
CREATE TABLE usuarios (
                          idUsuario int(11) NOT NULL,
                          nome varchar(255) NOT NULL,
                          email varchar(255) NOT NULL,
                          senha varchar(255) NOT NULL,
                          PRIMARY KEY (idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

#tabela checklist
CREATE TABLE checklist (
                           idChecklist int(11) NOT NULL AUTO_INCREMENT,
                           titulo varchar(255) NOT NULL,
                           descricao varchar(255) DEFAULT NULL,
                           idUsuario int(11) NOT NULL,
                           PRIMARY KEY (idChecklist),
                           KEY fk_idUsuario_idx (idUsuario),
                           CONSTRAINT fk_idUsuario FOREIGN KEY (idUsuario) REFERENCES usuarios (idusuario) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci


    #tabela tarefas
CREATE TABLE tarefas (
                         idtarefa int(11) NOT NULL AUTO_INCREMENT,
                         idChecklist int(11) NOT NULL,
                         idUsuario int(11) NOT NULL,
                         descricao varchar(255) NOT NULL,
                         concluida tinyint(4) NOT NULL DEFAULT 0,
                         dataConclusao datetime DEFAULT NULL,
                         dataCriacao datetime NOT NULL,
                         restaurada tinyint(4) DEFAULT 0,
                         deletada tinyint(4) DEFAULT 0,
                         ultimaAtualizacao datetime DEFAULT NULL,
                         PRIMARY KEY (idtarefa),
                         KEY fk_checklist_idx (idChecklist),
                         KEY fk_usuario_idx (idUsuario),
                         CONSTRAINT fk_checklist FOREIGN KEY (idChecklist) REFERENCES checklist (idChecklist) ON DELETE NO ACTION ON UPDATE NO ACTION,
                         CONSTRAINT fk_usuario FOREIGN KEY (idUsuario) REFERENCES usuarios (idusuario) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci