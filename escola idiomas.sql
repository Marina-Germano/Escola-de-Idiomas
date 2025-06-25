CREATE DATABASE escola_idiomas;
USE escola_idiomas;

CREATE TABLE nivel(
	idnivel INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nivel_atual ENUM("iniciante", "Intermediário", "Avançado")
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
    situacao ENUM('ativo', 'trancado', 'cancelado') DEFAULT 'ativo' NOT NULL,
    
    FOREIGN KEY (idnivel) REFERENCES nivel (idnivel)
);

CREATE TABLE tipo_documento(
	idtipo_documento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo_documento ENUM('RG', 'CPF', 'Comprovante de Residência', 'Contrato Assinado') NOT NULL
    -- melhor separar os campos (rg, cpf...) qual tipo seria esse campo?
);

CREATE TABLE status(
	idstatus INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    status_documento ENUM('pendente', 'enviado', 'aprovado', 'inválido'),
    status_pagamento ENUM('pendente', 'pago', 'atrasado'),
    status_nivel ENUM('iniciante', 'intermediário', 'avançado') NOT NULL -- outra tabela
);

CREATE TABLE documento_aluno (
    iddocumento INT AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    idtipo_documento INT NOT NULL,
    idstatus INT NOT NULL,
    nome_arquivo VARCHAR(255), -- verificar necessidade do campo
    caminho_arquivo VARCHAR(255), -- onde o arquivo está salvo no servidor
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    observacoes TEXT,

    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno),
    FOREIGN KEY (idtipo_documento) REFERENCES tipo_documento (idtipo_documento),
    FOREIGN KEY (idstatus) REFERENCES status (idstatus)
);

CREATE TABLE forma_pagamento(
	idforma_pagamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    forma_pagamento ENUM("Dinheiro", "Boleto","Cartão de Crédito", "Cartão de Débito", "Pix")
);

CREATE TABLE pagamento(
	idpagamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idforma_pagamento INT NOT NULL,
    idaluno INT NOT NULL,
    valor DECIMAL(10,2),
    data_vencimento DATE,
    idstatus INT NOT NULL,
	data_pagamento DATE, -- quando foi realmente pago
	valor_pago DECIMAL(10,2), -- pode ser menor em casos de desconto
	observacoes TEXT,
    multa DECIMAL(10,2) DEFAULT 0.00,
    
    FOREIGN KEY (idaluno) REFERENCES aluno(idaluno),
    FOREIGN KEY (idpagamento) REFERENCES forma_pagamento(idpagamento),
    FOREIGN KEY (idstatus) REFERENCES status(idstatus)
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
    tipo ENUM('aluno', 'funcionario', 'professor', 'admin') NOT NULL,
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
    tipo ENUM('apostila', 'livro', 'exercicio', 'audio', 'video', 'link', 'outro') NOT NULL
);

CREATE TABLE idioma(
	ididioma INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idioma ENUM('Inglês', 'Espanhol', 'Francês', 'Outros') NOT NULL
);

CREATE TABLE material(
    idmaterial INT AUTO_INCREMENT PRIMARY KEY,
    idtipo_material INT NOT NULL,
    ididioma INT NOT NULL,
    idnivel INT NOT NULL,
    idstatus INT NOT NULL,
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
	FOREIGN KEY (idnivel) REFERENCES nivel (idnivel),
    FOREIGN KEY (idstatus) REFERENCES status (idstatus)
);

CREATE TABLE emprestimo_material(
    idemprestimo INT AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    idmaterial INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_prevista_devolucao DATE NOT NULL,
    data_devolvido DATE NOT NULL,
    status ENUM('ativo', 'devolvido', 'atrasado') DEFAULT 'ativo', -- em caso de mais status, nova tabela
    observacoes TEXT,
    valor_multa DECIMAL(10,2), -- verificar
    
    FOREIGN KEY (idaluno) REFERENCES cadastro_aluno(idaluno),
    FOREIGN KEY (idmaterial) REFERENCES material(idmaterial)
);

CREATE TABLE turma(
	idturma INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(200) NOT NULL,
    idioma VARCHAR(100), -- trazer chave estrangeira
	nivel ENUM('Iniciante', 'Intermediário', 'Avançado'), -- vem de outra tabela
    data_aula VARCHAR(100) NOT NULL,
	hora_aula VARCHAR(100) NOT NULL, -- ex: seg e qua 14h-15h
	capacidade_maxima INT NOT NULL,
    sala VARCHAR(100),
    idprofessor INT NOT NULL,
    
    FOREIGN KEY (idprofessor) REFERENCES professor(idprofessor)
);

CREATE TABLE aluno_turma(
    idaluno INT AUTO_INCREMENT PRIMARY KEY, -- matricula
    idaluno INT NOT NULL,
    idturma INT NOT NULL,
    data_matricula DATE,
    status ENUM('matriculado', 'concluído', 'trancado'), -- chave estrangeira

    FOREIGN KEY (idaluno) REFERENCES cadastro_aluno(idaluno),
    FOREIGN KEY (idturma) REFERENCES cadastro_turma(idturma)
);

CREATE TABLE calendario_aula(
	idaula INT NOT NULL AUTO_INCREMENT,
    data_aula DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    idprofessor INT NOT NULL,
    idturma INT NOT NULL, -- turma de alunos, n sei se vai ficar assim, tem q criar essa tabela
    idmaterial INT NOT NULL, -- materia da aula(ingles 1, espanhol 2...) 
    sala VARCHAR(100), 
    observacoes VARCHAR(300),
    status_aula ENUM("Agendada", "Realizada", "Cancelada", "Reagendada"), -- outra tabela
    link_reuniao VARCHAR(255),
	aula_extra BOOLEAN DEFAULT FALSE,

    -- recorrencia
    recorrente BOOLEAN DEFAULT FALSE,
	tipo_recorrencia ENUM('diaria', 'semanal', 'mensal') DEFAULT NULL, -- mandar pra tabela turma

    FOREIGN KEY (idprofessor) REFERENCES cadastro_professor(idprofessor),
    FOREIGN KEY (idturma) REFERENCES cadastro_turma(idturma)
    -- FOREIGN KEY (idconteudo) REFERENCES conteudo(idconteudo) -- ainda n existe
);

CREATE TABLE presenca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idaula INT NOT NULL,
    idaluno INT NOT NULL,
    presente BOOLEAN DEFAULT NULL,
    idprofessor INT, -- não obrigatorio
    observacao TEXT,
    FOREIGN KEY (idaula) REFERENCES calendario_aulas(idaula),
    FOREIGN KEY (idaluno) REFERENCES alunos(idaluno)
);

CREATE TABLE nota(
	idnota INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idaluno INT NOT NULL,
    idprofessor INT NOT NULL,
    idturma INT NOT NULL,
    nota DECIMAL(2,1),
    
    FOREIGN KEY (idaluno) REFERENCES aluno (idaluno),
    FOREIGN KEY (idprofessor) REFERENCES professor(idprofessor),
    FOREIGN KEY (idturma) REFERENCES turma(idturma)
);

CREATE TABLE contato(
	idusuario INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    motivo_contato ENUM('Atualização de informação', 'atualização forma de pagamento','Agendamento de aula/prova substitutiva', 'outros'),
    observacoes TEXT,
    
    FOREIGN KEY (idusuario) REFERENCES usuario (idusuario)
);