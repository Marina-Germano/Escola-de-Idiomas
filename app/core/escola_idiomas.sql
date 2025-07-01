CREATE DATABASE escola_idiomas;
USE escola_idiomas;

CREATE TABLE nivel(
	idnivel INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255)
);

CREATE TABLE aluno(
	idaluno INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- matricula
    idnivel INT NOT NULL,
    nome VARCHAR(200) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    cep VARCHAR(8) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    complemento VARCHAR(100),
    responsavel VARCHAR(200),
    tel_responsavel VARCHAR(11),
    datanascimento DATE NOT NULL, 
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(11) NOT NULL,
    situacao ENUM('ativo', 'trancado', 'cancelado') DEFAULT 'ativo' NOT NULL, -- só vai ter esses
    
    FOREIGN KEY (idnivel) REFERENCES nivel (idnivel)
);

CREATE TABLE tipo_documento(
	idtipo_documento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo_documento VARCHAR(255) NOT NULL
);


CREATE TABLE documento_aluno (
    iddocumento INT AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    idtipo_documento INT NOT NULL,
    nome_arquivo VARCHAR(255), -- verificar necessidade do campo
    caminho_arquivo VARCHAR(255), -- onde o arquivo está salvo no servidor
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    observacoes TEXT,
    status_documento ENUM('pendente', 'enviado', 'aprovado', 'inválido'), -- verificar com Diego

    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno),
    FOREIGN KEY (idtipo_documento) REFERENCES tipo_documento (idtipo_documento)
);

CREATE TABLE forma_pagamento(
	idforma_pagamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    forma_pagamento VARCHAR(255)
);

CREATE TABLE pagamento(
	idpagamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idforma_pagamento INT NOT NULL,
    idaluno INT NOT NULL,
    valor DECIMAL(10,2),
    data_vencimento DATE,
	status_pagamento ENUM('pendente', 'pago', 'atrasado'),    
	data_pagamento DATE, -- quando foi realmente pago
	valor_pago DECIMAL(10,2), -- pode ser menor em casos de desconto
	observacoes TEXT,
    multa DECIMAL(10,2) DEFAULT 0.00,
    
    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno),
    FOREIGN KEY (idforma_pagamento) REFERENCES forma_pagamento(idforma_pagamento)
);

CREATE TABLE cartao(
    idcartao INT AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    nome_titular VARCHAR(100) NOT NULL,
    bandeira VARCHAR(20) NOT NULL,
    ultimos_digitos CHAR(4) NOT NULL,
    numero_criptografado VARCHAR(256) NOT NULL,  -- criptografar no php MD5
    validade_mes CHAR(2) NOT NULL,
    validade_ano CHAR(2) NOT NULL,
    
    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno)
);

CREATE TABLE funcionario(
	idfuncionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    cpf VARCHAR(11) UNIQUE,
    datanascimento DATE, 
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(11) NOT NULL,
    cargo VARCHAR(100), -- ex: recepcionista, coordenador
	turno ENUM("Manhã", "Tarde", "Noite")
);

CREATE TABLE professor(
	idprofessor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    datanascimento DATE NOT NULL, 
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(11) NOT NULL,
    especialidade VARCHAR(100) NOT NULL -- INGLES, espanhol...
);

CREATE TABLE usuario (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(11) NOT NULL UNIQUE,         -- usado como login
    senha VARCHAR(8) NOT NULL,        -- senha criptografada (ex: bcrypt ou SHA-256)
    papel VARCHAR(255) NOT NULL, -- aluno, professor, funcionário, admin...
    idaluno INT,                        -- link para o cadastro correspondente
    idprofessor INT,
    idfuncionario INT,
    ativo BOOLEAN DEFAULT TRUE,
    
	-- bloquear o usuario depois de algumas tentativas (definidas no php)
	tentativas_login INT DEFAULT 0,
	bloqueado BOOLEAN DEFAULT FALSE,

    -- Restrições
    CONSTRAINT fk_usuario_aluno FOREIGN KEY (idaluno) REFERENCES aluno(idaluno), -- fazer para os demais
    CONSTRAINT fk_usuario_professor FOREIGN KEY (idprofessor) REFERENCES professor(idprofessor),
    CONSTRAINT fk_usuario_funcionario FOREIGN KEY (idfuncionario) REFERENCES funcionario(idfuncionario),
    UNIQUE (cpf)
);


CREATE TABLE tipo_material(
	idtipo_material INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(255) NOT NULL -- livro, apostila, pdf
);

CREATE TABLE idioma(
	ididioma INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idioma VARCHAR(255) NOT NULL -- ingles, frances, espanhol
);

CREATE TABLE material(
    idmaterial INT AUTO_INCREMENT PRIMARY KEY,
    idtipo_material INT NOT NULL,
    ididioma INT NOT NULL,
    idnivel INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    quantidade INT NOT NULL,
    formato_arquivo VARCHAR(10), -- verificar qual comando q limita o tipo de arquivo a ser usado
    link_download VARCHAR(255), -- verificar se o campo vai ser varchar mesmo
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    idprofessor INT,
    -- verificar como o aluno vai acessar esses materiais

	FOREIGN KEY (idprofessor) REFERENCES professor(idprofessor),
    FOREIGN KEY (idtipo_material) REFERENCES tipo_material(idtipo_material),
    FOREIGN KEY (ididioma) REFERENCES idioma(ididioma),
	FOREIGN KEY (idnivel) REFERENCES nivel (idnivel)
);

CREATE TABLE emprestimo_material(
    idemprestimo INT AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    idmaterial INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_prevista_devolucao DATE NOT NULL,
    data_devolvido DATE NOT NULL,
    status ENUM('Disponível','Indisponível', 'Devolvido', 'Atrasado') DEFAULT 'Disponível', 
    -- verificar com Diego se isso vai ficar assim ou vou fazer isso no php
    observacoes TEXT,
    valor_multa DECIMAL(10,2), -- verificar
    
    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno),
    FOREIGN KEY (idmaterial) REFERENCES material(idmaterial)
);

CREATE TABLE turma(
	idturma INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ididioma INT NOT NULL,
    data_aula VARCHAR(100) NOT NULL,
	hora_aula VARCHAR(100) NOT NULL, -- ex: seg e qua 14h-15h
	capacidade_maxima INT NOT NULL,
    sala VARCHAR(100),
    idprofessor INT NOT NULL,
	tipo_recorrencia ENUM('diaria', 'semanal', 'mensal') DEFAULT NULL,
    
    FOREIGN KEY (idprofessor) REFERENCES professor(idprofessor),
    FOREIGN KEY (ididioma) REFERENCES idioma(ididioma)
);

CREATE TABLE aluno_turma(
    idaluno_turma INT AUTO_INCREMENT PRIMARY KEY, -- matricula
    idaluno INT NOT NULL,
    idturma INT NOT NULL,
    data_matricula DATE,

    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno),
    FOREIGN KEY (idturma) REFERENCES turma(idturma)
);

CREATE TABLE avaliacao(
	idavaliacao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idaluno_turma INT NOT NULL,
    tipo_avaliacao VARCHAR(255) NOT NULL,
    titulo VARCHAR(255),
    data_avaliacao DATE NOT NULL,
    nota DECIMAL(5,2),
    peso DECIMAL(3,2) DEFAULT 1.0,
    observacao TEXT,
    
    FOREIGN KEY (idaluno_turma) REFERENCES aluno_turma(idaluno_turma)
);

CREATE TABLE calendario_aula(
	idaula INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_aula DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    idprofessor INT NOT NULL,
    idturma INT NOT NULL,
    idmaterial INT NOT NULL, -- materia da aula(ingles 1, espanhol 2...) 
    sala VARCHAR(100), 
    observacoes VARCHAR(300),
    link_reuniao VARCHAR(255),
	aula_extra BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (idprofessor) REFERENCES professor(idprofessor),
    FOREIGN KEY (idturma) REFERENCES turma(idturma)
);

CREATE TABLE presenca (
    idpresenca INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idaula INT NOT NULL,
    idaluno INT NOT NULL, -- trocar para aluno_turma
    presente BOOLEAN DEFAULT NULL,
    observacao TEXT,
    
    FOREIGN KEY (idaula) REFERENCES calendario_aula(idaula),
    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno)
);

CREATE TABLE nota(
	idnota INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    idturma INT NOT NULL,
    nota DECIMAL(3,1),
    
    FOREIGN KEY (idaluno) REFERENCES aluno (idaluno),
    FOREIGN KEY (idturma) REFERENCES turma(idturma)
);

CREATE TABLE contato(
	idusuario INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    motivo_contato VARCHAR(255),
    observacoes TEXT,
    
    FOREIGN KEY (idusuario) REFERENCES usuario (idusuario)
);

-- povoando as tabelas

INSERT INTO nivel (descricao) VALUES  -- nivel
('Básico'), 
('Intermediário'), 
('Avançado');

INSERT INTO aluno (idnivel, nome, cpf, cep, rua, numero, bairro, complemento, responsavel, tel_responsavel, datanascimento, email, telefone)
VALUES
(1, 'Ana Clara', '12345678901', '01001000', 'Rua das Flores', '123', 'Centro', 'Apto 1', 'Maria Clara', '11999999999', '2010-05-10', 'ana@email.com', '11988888888'),
(2, 'Bruno Souza', '23456789012', '02020020', 'Av. Brasil', '456', 'Jardim', NULL, NULL, NULL, '2008-09-15', 'bruno@email.com', '11977777777'),
(3, 'Carla Mendes', '34567890123', '03030030', 'Rua Verde', '789', 'Vila Nova', NULL, NULL, NULL, '2012-02-28', 'carla@email.com', '11966666666');

INSERT INTO tipo_documento (tipo_documento) VALUES 
('RG'),
('CPF'),
('Comprovante de Residência');

INSERT INTO documento_aluno (idaluno, idtipo_documento, nome_arquivo, caminho_arquivo, observacoes, status_documento)
VALUES
(1, 1, 'rg_ana.pdf', '/docs/ana/rg_ana.pdf', 'Documento legível', 'aprovado'),
(2, 2, 'cpf_bruno.pdf', '/docs/bruno/cpf_bruno.pdf', '', 'pendente'),
(3, 3, 'comprovante_carla.pdf', '/docs/carla/comprovante_carla.pdf', 'Arquivo corrompido', 'inválido');

INSERT INTO forma_pagamento (forma_pagamento) VALUES
('Cartão de Crédito'),
('Boleto'),
('Pix');

INSERT INTO pagamento (idforma_pagamento, idaluno, valor, data_vencimento, status_pagamento, data_pagamento, valor_pago, observacoes, multa)
VALUES
(1, 1, 300.00, '2025-06-10', 'pago', '2025-06-09', 300.00, 'Pagamento em dia', 0.00),
(2, 2, 320.00, '2025-06-10', 'atrasado', '2025-06-15', 320.00, 'Pagou após vencimento', 20.00),
(3, 3, 310.00, '2025-06-10', 'pendente', NULL, NULL, 'Aguardando pagamento', 0.00);

INSERT INTO cartao (idaluno, nome_titular, bandeira, ultimos_digitos, numero_criptografado, validade_mes, validade_ano)
VALUES
(1, 'Ana Clara', 'Visa', '1234', MD5('4111111111111234'), '12', '27'),
(2, 'Bruno Souza', 'Mastercard', '5678', MD5('5555555555555678'), '11', '26'),
(3, 'Carla Mendes', 'Elo', '9876', MD5('6363683683689876'), '10', '25');

INSERT INTO funcionario (nome, cpf, datanascimento, email, telefone, cargo, turno) VALUES
('Diego Rocha', '44444444444', '1980-01-01', 'diego@escola.com', '11944444444', 'Coordenador', 'Manhã'),
('Luciana Reis', '55555555555', '1992-06-10', 'luciana@escola.com', '11955555555', 'Recepcionista', 'Tarde'),
('Mateus Silva', '66666666666', '1995-09-20', 'mateus@escola.com', '11966666666', 'Financeiro', 'Noite');

INSERT INTO professor (nome, cpf, datanascimento, email, telefone, especialidade) VALUES
('João Pereira', '11111111111', '1985-04-12', 'joao@escola.com', '11911111111', 'Inglês'),
('Fernanda Lima', '22222222222', '1990-08-23', 'fernanda@escola.com', '11922222222', 'Espanhol'),
('Carlos Silva', '33333333333', '1982-12-05', 'carlos@escola.com', '11933333333', 'Francês');

INSERT INTO usuario (cpf, senha, papel, idaluno, ativo) VALUES
('12345678901', 'senha123', 'aluno', 1, TRUE),
('23456789012', 'senha123', 'aluno', 2, TRUE),
('44444444444', 'senha123', 'funcionario', NULL, TRUE);

INSERT INTO tipo_material (tipo) VALUES
('Livro'),
('PDF'),
('Apostila');

INSERT INTO idioma (idioma) VALUES 
('Inglês'),
('Espanhol'),
('Francês');

INSERT INTO material (idtipo_material, ididioma, idnivel, titulo, descricao, quantidade, formato_arquivo, link_download, idprofessor)
VALUES
(1, 1, 1, 'Inglês Básico 1', 'Livro do aluno', 10, 'pdf', 'https://exemplo.com/livro1.pdf', 1),
(2, 2, 2, 'Espanhol Intermediário', 'Material de apoio', 5, 'pdf', 'https://exemplo.com/spanish.pdf', 2),
(3, 3, 3, 'Francês Avançado', 'Apostila complementar', 3, 'pdf', 'https://exemplo.com/frances.pdf', 3);

INSERT INTO emprestimo_material (idaluno, idmaterial, data_emprestimo, data_prevista_devolucao, data_devolvido, status, observacoes, valor_multa)
VALUES
(1, 1, '2025-06-01', '2025-06-10', '2025-06-09', 'Devolvido', 'Devolvido dentro do prazo', 0.00),
(2, 2, '2025-06-05', '2025-06-12', '2025-06-15', 'Atrasado', 'Atrasou devolução', 10.00),
(3, 3, '2025-06-08', '2025-06-18', '2025-06-18', 'Devolvido', '', 0.00);

INSERT INTO turma (ididioma, data_aula, hora_aula, capacidade_maxima, sala, idprofessor, tipo_recorrencia) VALUES
(1, 'Seg e Qua', '14h às 15h', 10, 'Sala 1', 1, 'semanal'),
(2, 'Ter e Qui', '16h às 17h', 8, 'Sala 2', 2, 'semanal'),
(3, 'Sábado', '10h às 12h', 6, 'Sala 3', 3, 'semanal');

INSERT INTO aluno_turma (idaluno, idturma, data_matricula) VALUES
(1, 1, '2025-01-10'),
(2, 1, '2025-01-12'),
(3, 2, '2025-01-15');

INSERT INTO avaliacao (idaluno_turma, tipo_avaliacao, titulo, data_avaliacao, nota, peso, observacao) VALUES
(1, 'Prova Escrita', 'Teste de Verbos', '2025-03-01', 8.5, 1.0, 'Bom desempenho'),
(2, 'Atividade Oral', 'Apresentação sobre Viagem', '2025-03-03', 7.0, 1.0, 'Faltou vocabulário'),
(3, 'Redação', 'Minha Rotina', '2025-03-05', 9.0, 1.2, 'Excelente estrutura e vocabulário');

INSERT INTO calendario_aula (data_aula, hora_inicio, hora_fim, idprofessor, idturma, idmaterial, sala, observacoes, status_aula, link_reuniao, aula_extra, recorrente)
VALUES
('2025-06-10', '14:00:00', '15:00:00', 1, 1, 1, 'Sala 1', 'Revisão de verbos', 'Agendada', 'https://meet.exemplo.com/aula1', FALSE, TRUE),
('2025-06-12', '14:00:00', '15:00:00', 1, 1, 1, 'Sala 1', 'Vocabulário: comida', 'Agendada', 'https://meet.exemplo.com/aula2', FALSE, TRUE),
('2025-06-15', '16:00:00', '17:00:00', 2, 2, 2, 'Sala 2', 'Expressões idiomáticas', 'Reagendada', 'https://meet.exemplo.com/aula3', TRUE, FALSE);

INSERT INTO presenca (idaula, idaluno, presente, observacao)
VALUES
(1, 1, TRUE, 'Participou bem'),
(1, 2, FALSE, 'Faltou'),
(2, 1, TRUE, NULL);

INSERT INTO nota (idaluno, idturma, nota) VALUES
(1, 1, 8.5),
(2, 1, 7.0),
(3, 2, 9.2);

INSERT INTO contato (idusuario, nome, email, motivo_contato, observacoes)
VALUES
(1, 'Ana Clara', 'ana@email.com', 'Dúvida sobre material', 'Queria saber onde acessar o PDF da última aula.'),
(2, 'Bruno Souza', 'bruno@email.com', 'Pagamento', 'Quero renegociar meu boleto atrasado.'),
(3, 'Diego Rocha', 'diego@escola.com', 'Sugestão', 'Sugiro criar uma área do aluno com histórico de presença.');
