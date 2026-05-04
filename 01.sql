USE Projeto;

-- Tabela independente
CREATE TABLE Estoque (
    id int auto_increment PRIMARY KEY,
    nome varchar(100) not null,
    categoria varchar(50) not NULL,
    localizacao varchar(100) not null,
    quantidade int unsigned not null default 0,
    descricao text null, -- corrigido de descicao
    status enum('Estoque Baixo', 'Disponivel', 'Esgotado') not null default 'Disponivel',
    criado_em timestamp default current_timestamp,
    atualizado_em timestamp default current_timestamp on update CURRENT_TIMESTAMP -- Corrigido
);

-- Tabela 'Pai' criada ANTES das que dependem dela
CREATE TABLE Usuarios (
    id int auto_increment PRIMARY KEY,
    nome varchar(150) not null,
    email varchar(150) unique not null,
    cpf varchar(11) unique not null,
    telefone varchar(20) null,
    data_nascimento date null,
    senha_hash varchar(255) not null,
    ativo boolean not null default TRUE, -- Ajustado para TRUE sem aspas
    perfil_acesso enum('cliente', 'admin', 'atendente', 'gerente', 'mautenção', 'financeiro') not null default 'cliente', -- Corrigido de enu para enum
    criado_em timestamp default current_timestamp,
    atualizado_em timestamp default current_timestamp on update CURRENT_TIMESTAMP -- Corrigido
);


insert into Usuarios (nome, email, cpf, telefone, data_nascimento, senha_hash, perfil_acesso) values ('Admin', 'Admin@senaisp.com.br', '00000000000', '(00) 00000-0000', '1990-01-01', 'AdminSenaiSP', 'Admin');



-- Tabela dependente de Usuarios
CREATE TABLE Chamados (
    id int auto_increment PRIMARY KEY,
    titulo varchar(100) not null,
    descricao text not null,
    local varchar(100) not null,
    status enum('Aberto', 'Em Analise', 'Fechado') not null default 'Aberto',
    prioridade enum('Baixa', 'Média', 'Alta', 'Urgente') default 'Baixa',
    id_atendente INT NULL,
    id_usuario INT NOT NULL,
    criado_em timestamp default current_timestamp,
    atualizado_em timestamp default current_timestamp on update CURRENT_TIMESTAMP, -- Corrigido
    FOREIGN KEY (id_atendente) REFERENCES Usuarios(id),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);

-- Tabela dependente de Usuarios
CREATE TABLE Tarefas (
    id int auto_increment PRIMARY KEY,
    titulo varchar(100) not null,
    descricao text not null,
    localizacao varchar(100) not null,
    prioridade enum('Baixa', 'Média', 'Alta', 'Urgente') default 'Baixa',
    data_inicio datetime not null,
    data_final datetime not null,
    status enum('Pendente', 'Em Andamento', 'Concluido') not null default 'Pendente',
    id_responsavel INT NOT NULL,
    criado_em timestamp default current_timestamp,
    atualizado_em timestamp default current_timestamp on update CURRENT_TIMESTAMP, -- Corrigido
    FOREIGN KEY (id_responsavel) REFERENCES Usuarios(id)
);