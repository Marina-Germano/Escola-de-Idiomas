CREATE DATABASE escola_idiomas;
USE escola_idiomas;

CREATE TABLE usuario (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    telefone CHAR(11) NOT NULL,
    email VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,        -- senha criptografada (ex: bcrypt ou SHA-256)
    papel VARCHAR(255) NOT NULL, 
    ativo BOOLEAN DEFAULT TRUE,
    foto VARCHAR(255),
    
	tentativas_login INT DEFAULT 0,
	bloqueado BOOLEAN DEFAULT FALSE
);

CREATE TABLE aluno(
	idaluno INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- matricula
    idusuario INT NOT NULL,
    cep VARCHAR(8) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    complemento VARCHAR(100),
    responsavel VARCHAR(200),
    tel_responsavel VARCHAR(11),
    situacao ENUM('ativo', 'trancado', 'cancelado') DEFAULT 'ativo' NOT NULL,
    
	FOREIGN KEY (idusuario) REFERENCES usuario(idusuario)
);

CREATE TABLE funcionario(
	idfuncionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idusuario INT NOT NULL,
    cargo VARCHAR(100),
    especialidade VARCHAR(100),
    
    FOREIGN KEY (idusuario) REFERENCES usuario(idusuario)
);

CREATE TABLE tipo_documento(
	idtipo_documento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL
);

CREATE TABLE documento_aluno (
    iddocumento INT AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    idtipo_documento INT NOT NULL,
    caminho_arquivo VARCHAR(255), -- onde o arquivo está salvo no servidor
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    observacoes TEXT,
    status_documento ENUM('pendente', 'aprovado', 'invalido'), -- verificar com Diego

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

CREATE TABLE nivel(
	idnivel INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255)
);

CREATE TABLE idioma(
	ididioma INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL -- ingles, frances, espanhol
);

CREATE TABLE turma(
	idturma INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ididioma INT NOT NULL,
	idnivel INT NOT NULL,
    idfuncionario INT NOT NULL,
    descricao VARCHAR(100) NOT NULL, -- nome da turma
    dias_semana VARCHAR(255),
	hora_inicio TIME NOT NULL, -- ex: seg e qua 14h-15h
	capacidade_maxima INT NOT NULL,
    sala VARCHAR(100),
    imagem VARCHAR(255),
	tipo_recorrencia ENUM('diaria', 'semanal', 'mensal') DEFAULT NULL,
    
    FOREIGN KEY (idfuncionario) REFERENCES funcionario(idfuncionario),
    FOREIGN KEY (ididioma) REFERENCES idioma(ididioma),
    FOREIGN KEY (idnivel) REFERENCES nivel(idnivel)
);

CREATE TABLE tipo_material(
	idtipo_material INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL -- livro, apostila, pdf
);

CREATE TABLE material(
    idmaterial INT AUTO_INCREMENT PRIMARY KEY,
    idtipo_material INT NOT NULL,
    ididioma INT NOT NULL,
    idnivel INT NOT NULL,
    idturma INT NULL, -- organizar
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT NULL,
    quantidade INT NOT NULL,
    formato_arquivo VARCHAR(10) NULL,
    arquivo VARCHAR(255) NULL, -- para upload e dowloand
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    -- verificar como o aluno vai acessar esses materiais

    FOREIGN KEY (idtipo_material) REFERENCES tipo_material(idtipo_material),
    FOREIGN KEY (ididioma) REFERENCES idioma(ididioma),
	FOREIGN KEY (idnivel) REFERENCES nivel (idnivel),
    FOREIGN KEY (idturma) REFERENCES turma (idturma)
);

CREATE TABLE emprestimo_material(
    idemprestimo INT AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    idmaterial INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_prevista_devolucao DATE NOT NULL,
    data_devolvido DATE,
    status ENUM('emprestado', 'devolvido', 'atrasado') DEFAULT 'emprestado', 
    observacoes TEXT,
    valor_multa DECIMAL(10,2) DEFAULT 0.00,
    
    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno),
    FOREIGN KEY (idmaterial) REFERENCES material(idmaterial)
);

CREATE TABLE aluno_turma(
    idaluno_turma INT AUTO_INCREMENT PRIMARY KEY, -- matricula
    idaluno INT NOT NULL,
    idturma INT NOT NULL,
    data_matricula DATE DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno),
    FOREIGN KEY (idturma) REFERENCES turma(idturma),
    UNIQUE KEY (idaluno, idturma)

);

CREATE TABLE avaliacao(
	idavaliacao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idaluno_turma INT NOT NULL,
    idfuncionario INT NOT NULL,
    descricao VARCHAR(255) NOT NULL, -- prova de ingles, atividade verbo to be
    titulo VARCHAR(255),
    data_avaliacao DATE NOT NULL,
    nota DECIMAL(4,2),
    peso DECIMAL(3,2) DEFAULT 1.0,
    observacao TEXT,
    
    FOREIGN KEY (idaluno_turma) REFERENCES aluno_turma(idaluno_turma),
    FOREIGN KEY (idfuncionario) REFERENCES funcionario(idfuncionario)
);

CREATE TABLE calendario_aula(
	idaula INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_aula DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    idfuncionario INT NOT NULL,
    idturma INT NOT NULL,
    idmaterial INT NOT NULL, -- materia da aula(ingles 1, espanhol 2...) 
    sala VARCHAR(100), 
    observacoes VARCHAR(300),
    link_reuniao VARCHAR(255),
	aula_extra BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (idfuncionario) REFERENCES funcionario(idfuncionario),
    FOREIGN KEY (idturma) REFERENCES turma(idturma),
    FOREIGN KEY (idmaterial) REFERENCES material(idmaterial)
);

CREATE TABLE presenca (
    idpresenca INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idaluno_turma INT NOT NULL,
    idfuncionario INT NOT NULL,
    presente BOOLEAN NOT NULL DEFAULT FALSE,
    data DATE NOT NULL,
    
    FOREIGN KEY (idaluno_turma) REFERENCES aluno_turma(idaluno_turma),
    FOREIGN KEY (idfuncionario) REFERENCES funcionario(idfuncionario)
);

CREATE TABLE contato(
	idcontato INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idusuario INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone CHAR(11) NOT NULL,
	arquivo VARCHAR(255),
    motivo_contato VARCHAR(255) NOT NULL,
    mensagem VARCHAR(255) NOT NULL,
    
    FOREIGN KEY (idusuario) REFERENCES usuario (idusuario)
);

-- povoando as tabelas

-- Inserir usuários (1 aluno, 1 funcionário, 1 professor)
INSERT INTO usuario (nome, telefone, email, data_nascimento, cpf, foto, senha, papel)
VALUES 
('Ana Souza', '11999999999', 'ana@example.com', '2001-04-10', '12345678901', NULL, 'senha123', 'aluno'),
('Carlos Lima', '21988887777', 'carlos@example.com', '1980-11-23', '11111111111', NULL, 'admin', 'admin'),
('Fernanda Dias', '31977776666', 'fernanda@example.com', '1990-07-15', '34567890123', NULL, 'senha123', 'funcionario'),
('João Oliveira', '11944445555', 'joao.oliveira@example.com', '1985-03-20', '22233344455', NULL, 'senha123', 'funcionario'); -- professor

INSERT INTO aluno (idusuario, cep, rua, numero, bairro, complemento, responsavel, tel_responsavel)
VALUES 
(1, '12345678', 'Rua das Flores', '100', 'Jardim', 'Casa 1', 'Maria Souza', '11988888888');

INSERT INTO funcionario (idusuario, cargo, especialidade)
VALUES 
(3, 'Recepcionista', null),
(4, 'Professor', 'Inglês');


INSERT INTO tipo_documento (descricao) VALUES
('RG'),
('CPF'),
('Comprovante de Residência');

INSERT INTO documento_aluno (idaluno, idtipo_documento, caminho_arquivo, observacoes, status_documento)
VALUES 
(1, 1, '/uploads/documentos/rg_ana.pdf', 'Documento escaneado com boa qualidade.', 'aprovado'),
(1, 2, '/uploads/documentos/cpf_ana.pdf', 'Documento ilegível, enviar novamente.', 'invalido'),
(1, 3, '/uploads/documentos/comprovante_ana.pdf', NULL, 'pendente');

INSERT INTO forma_pagamento (forma_pagamento) VALUES
('Dinheiro'),
('Cartão de Crédito'),
('Pix');

INSERT INTO pagamento (idforma_pagamento, idaluno, valor, data_vencimento, status_pagamento, data_pagamento, valor_pago, observacoes, multa) 
VALUES
(1, 1, 350.00, '2025-07-05', 'pago', '2025-07-04', 350.00, 'Pagamento em dinheiro realizado antecipadamente.', 0.00);

INSERT INTO cartao (idaluno, nome_titular, bandeira, ultimos_digitos, numero_criptografado, validade_mes, validade_ano) 
VALUES
(1, 'Ana Souza', 'Elo', '9012', MD5('6362970000009012'), '03', '28');

INSERT INTO nivel (descricao) VALUES
('Básico'),
('Intermediário'),
('Avançado');

INSERT INTO idioma (descricao) VALUES
('Inglês'),
('Espanhol'),
('Francês');

INSERT INTO turma (ididioma, idnivel, idfuncionario, descricao, dias_semana, hora_inicio, capacidade_maxima, sala, imagem, tipo_recorrencia) 
VALUES
(1, 1, 2,'Turma de Inglês Básico - Tarde', 'Segunda e Quarta', '14:00:00', 20, 'Sala 101', '/imagens/ingles_basico.jpg', 'semanal');

INSERT INTO tipo_material (descricao) VALUES
('Livro'),
('Apostila'),
('PDF');


INSERT INTO material (idtipo_material, ididioma, idnivel, idturma, titulo, descricao, quantidade, formato_arquivo, arquivo) 
VALUES
(1, 1, 1, 1, 'Inglês Básico - Volume 1', 'Livro didático para iniciantes.', 10, 'pdf', '/materiais/ingles_basico_v1.pdf'),
(2, 1, 1, 1, 'Apostila de Verbos', 'Conteúdo complementar focado em verbos.', 20, 'pdf', '/materiais/apostila_verbos.pdf'),
(3, 1, 1, 1, 'Vocabulário Essencial', 'Material digital com vocabulário básico.', 30, 'pdf', '/materiais/vocabulario.pdf');

INSERT INTO emprestimo_material (idaluno, idmaterial, data_emprestimo, data_prevista_devolucao, data_devolvido, status, observacoes, valor_multa) 
VALUES
(1, 1, '2025-07-01', '2025-07-08', '2025-07-07', 'devolvido', 'Devolvido em dia.', 0.00),
(1, 2, '2025-07-01', '2025-07-08', NULL, 'emprestado', 'Em posse do aluno.', 0.00),
(1, 3, '2025-06-20', '2025-06-27', NULL, 'atrasado', 'Aluno ainda não devolveu.', 10.00);

INSERT INTO aluno_turma (idaluno, idturma) VALUES
(1, 1);

INSERT INTO avaliacao (idaluno_turma, idfuncionario, descricao, titulo, data_avaliacao, nota, peso, observacao) VALUES
(1, 2, 'Prova de inglês - Unidade 1', 'Prova 1', '2025-07-01', 8.5, 1.0, 'Bom desempenho.');

INSERT INTO calendario_aula (data_aula, hora_inicio, hora_fim, idfuncionario, idturma, idmaterial, sala, observacoes, link_reuniao, aula_extra) 
VALUES
('2025-07-01', '14:00:00', '15:00:00', 2, 1, 1, 'Sala 101', 'Aula introdutória', NULL, FALSE),
('2025-07-03', '14:00:00', '15:00:00', 2, 1, 1, 'Sala 101', 'Revisão dos verbos', NULL, FALSE),
('2025-07-05', '10:00:00', '11:30:00', 2, 1, 1, 'Sala 101', 'Aula extra para dúvidas', NULL, TRUE);

INSERT INTO presenca (idaluno_turma, idfuncionario, presente, data) VALUES
(1, 2, TRUE, CURDATE());

INSERT INTO contato (idusuario, nome, email, telefone, arquivo, motivo_contato, mensagem) VALUES
(1, 'Ana Souza', 'ana@example.com', '11999999999', NULL, 'Solicitação de material', 'Preciso da apostila do curso para estudar em casa.');

INSERT INTO material (idtipo_material, ididioma, idnivel, idturma, titulo, descricao, quantidade, formato_arquivo, arquivo) VALUES
-- 1. Pronomes Essenciais: Guia para Iniciantes (Livro)
(1, 1, 1, 1, 'Pronomes Essenciais: Guia para Iniciantes', 'Este livro é o ponto de partida ideal para entender os pronomes em inglês. Explora de forma clara e didática os pronomes pessoais, possessivos e demonstrativos, com muitos exemplos e exercícios simples para fixação. Essencial para construir frases básicas corretamente.', 1, 'pdf', '/materiais/pronomes_essenciais_basico.pdf'),

-- 2. Dominando os Pronomes: Casos e Usos Avançados (Apostila)
(2, 1, 2, 1, 'Dominando os Pronomes: Casos e Usos Avançados', 'Apostila completa para aprofundar o conhecimento sobre pronomes. Aborda pronomes reflexivos, recíprocos, relativos e indefinidos, com foco em seus usos e posições nas frases. Inclui exercícios desafiadores e dicas para evitar erros comuns.', 1, 'pdf', '/materiais/dominando_pronomes_intermediario.pdf', 2),

-- 3. Pronomes em Contexto: Conversação e Escrita (PDF)
(3, 1, 3, 1, 'Pronomes em Contexto: Conversação e Escrita', 'Material em PDF focado na aplicação prática dos pronomes em inglês, tanto na conversação quanto na escrita formal e informal. Contém exemplos de diálogos, trechos de textos e atividades para aprimorar a fluidez e a precisão no uso dos pronomes em diferentes situações.', 1, 'pdf', '/materiais/pronomes_contexto_avancado.pdf', 2),

-- 4. Quiz de Pronomes: Teste seus Conhecimentos (PDF)
(3, 1, 1, 1, 'Quiz de Pronomes: Teste seus Conhecimentos', 'Um PDF interativo com uma série de quizzes e jogos rápidos para testar o entendimento dos pronomes básicos. Ideal para revisão e autoavaliação, tornando o aprendizado divertido e eficaz.', 1, 'pdf', '/materiais/quiz_pronomes_basico.pdf', 2),

-- 5. Pronomes Indefinidos: Guia Completo (Apostila)
(2, 1, 2, 1, 'Pronomes Indefinidos: Guia Completo', 'Apostila dedicada exclusivamente aos pronomes indefinidos (e.g., *some*, *any*, *no*, *every* e seus compostos). Explica as regras de uso, concordância e as exceções, com exercícios práticos para dominar este tópico frequentemente confuso.', 1, 'pdf', '/materiais/pronomes_indefinidos_intermediario.pdf', 2),

-- 6. Pronomes Relativos: Conectando Ideias (Livro)
(1, 1, 3, 1, 'Pronomes Relativos: Conectando Idéias', 'Este livro detalha o uso dos pronomes relativos (*who*, *whom*, *whose*, *which*, *that*) para construir frases complexas e coesas. Aborda cláusulas relativas restritivas e não restritivas, com exemplos de textos acadêmicos e jornalísticos para prática avançada.', 1, 'pdf', '/materiais/pronomes_relativos_avancado.pdf', 2);


-- Eventos para Julho de 2025 (adicionando mais alguns além do que já existe)
INSERT INTO calendario_aula (data_aula, hora_inicio, hora_fim, idfuncionario, idturma, idmaterial, sala, observacoes, link_reuniao, aula_extra) VALUES
('2025-07-28', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Revisão de Present Perfect', NULL, FALSE),
('2025-07-30', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Introdução a Future Simple', NULL, FALSE);

-- Eventos para Agosto de 2025
INSERT INTO calendario_aula (data_aula, hora_inicio, hora_fim, idfuncionario, idturma, idmaterial, sala, observacoes, link_reuniao, aula_extra) VALUES
('2025-08-04', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Prática de conversação: Viagens', NULL, FALSE),
('2025-08-06', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Reading Comprehension: Artigos de jornal', NULL, FALSE),
('2025-08-11', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Grammar Focus: Conditionals', NULL, FALSE),
('2025-08-13', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Listening Practice: Notícias', NULL, FALSE),
('2025-08-18', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Revisão geral do mês', NULL, FALSE),
('2025-08-20', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Preparação para o teste', NULL, FALSE);

-- Eventos para Setembro de 2025
INSERT INTO calendario_aula (data_aula, hora_inicio, hora_fim, idfuncionario, idturma, idmaterial, sala, observacoes, link_reuniao, aula_extra) VALUES
('2025-09-01', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Início do Módulo 2', NULL, FALSE),
('2025-09-03', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Vocabulário: Compras', NULL, FALSE),
('2025-09-08', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Discussão: Meio ambiente', NULL, FALSE),
('2025-09-10', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Grammar Focus: Passive Voice', NULL, FALSE);

-- Eventos para Outubro de 2025
INSERT INTO calendario_aula (data_aula, hora_inicio, hora_fim, idfuncionario, idturma, idmaterial, sala, observacoes, link_reuniao, aula_extra) VALUES
('2025-10-06', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Debate: Tecnologia e sociedade', NULL, FALSE),
('2025-10-08', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Vocabulário: Saúde e bem-estar', NULL, FALSE),
('2025-10-13', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Grammar Focus: Reported Speech', NULL, FALSE),
('2025-10-15', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Apresentações orais dos alunos', NULL, FALSE);

-- Eventos para Novembro de 2025
INSERT INTO calendario_aula (data_aula, hora_inicio, hora_fim, idfuncionario, idturma, idmaterial, sala, observacoes, link_reuniao, aula_extra) VALUES
('2025-11-03', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Revisão de tempos verbais', NULL, FALSE),
('2025-11-05', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Simulação de entrevistas de emprego', NULL, FALSE),
('2025-11-10', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Vocabulário: Viagens e Turismo', NULL, FALSE),
('2025-11-12', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Cultura Americana vs Britânica', NULL, FALSE);

-- Eventos para Dezembro de 2025
INSERT INTO calendario_aula (data_aula, hora_inicio, hora_fim, idfuncionario, idturma, idmaterial, sala, observacoes, link_reuniao, aula_extra) VALUES
('2025-12-01', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Revisão final do ano', NULL, FALSE),
('2025-12-03', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Atividade de Natal em Inglês', NULL, FALSE),
('2025-12-08', '14:00:00', '15:30:00', 2, 1, 1, 'Sala 101', 'Encerramento do Semestre', NULL, FALSE),
('2025-12-10', '10:00:00', '12:00:00', 2, 1, 1, 'Sala 101', 'Festa de Confraternização (Aula Extra)', NULL, TRUE);

-- Inserir um pagamento pendente para a mensalidade de Agosto
INSERT INTO pagamento (idforma_pagamento, idaluno, valor, data_vencimento, status_pagamento, observacoes)    
VALUES
(1, 1, 350.00, '2025-08-05', 'pendente', 'Mensalidade de Agosto - Inglês');

-- Inserir um pagamento pendente para a mensalidade de Setembro
INSERT INTO pagamento (idforma_pagamento, idaluno, valor, data_vencimento, status_pagamento, observacoes)    
VALUES
(1, 1, 350.00, '2025-09-05', 'pendente', 'Mensalidade de Setembro - Inglês');

-- Inserir uma nova avaliação com nota baixa para a Ana Souza
INSERT INTO avaliacao (idaluno_turma, idfuncionario, descricao, titulo, data_avaliacao, nota, peso, observacao) VALUES
(1, 4, 'Atividade de Vocabulário - Unidade 2', 'Quiz Vocabulário', '2025-07-25', 4.0, 1.0, 'Precisa revisar o vocabulário da Unidade 2.');
