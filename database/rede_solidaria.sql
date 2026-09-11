SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 senha VARCHAR(255) NOT NULL,
 tipo ENUM('ong','solidario','admin') NOT NULL,
 status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo',
 data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ongs (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 usuario_id INT UNSIGNED NOT NULL UNIQUE,
 nome_fantasia VARCHAR(180) NOT NULL,
 cnpj VARCHAR(30) NULL,
 descricao TEXT NULL,
 telefone VARCHAR(30) NULL,
 cidade VARCHAR(120) NULL,
 endereco VARCHAR(255) NULL,
 FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS solidarios (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 usuario_id INT UNSIGNED NOT NULL UNIQUE,
 telefone VARCHAR(30) NULL,
 cidade VARCHAR(120) NULL,
 descricao TEXT NULL,
 FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS demandas (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 ong_id INT UNSIGNED NOT NULL,
 titulo VARCHAR(180) NOT NULL,
 descricao TEXT NOT NULL,
 categoria VARCHAR(100) NOT NULL,
 cidade VARCHAR(120) NOT NULL,
 prazo DATE NULL,
 status ENUM('aberta','encerrada') NOT NULL DEFAULT 'aberta',
 data_criacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(ong_id) REFERENCES ongs(id) ON DELETE CASCADE,
 INDEX(status), INDEX(cidade)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS participacoes (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 demanda_id INT UNSIGNED NOT NULL,
 solidario_id INT UNSIGNED NOT NULL,
 status ENUM('interessado','confirmado','concluido','cancelado') NOT NULL DEFAULT 'interessado',
 data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(demanda_id) REFERENCES demandas(id) ON DELETE CASCADE,
 FOREIGN KEY(solidario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
 UNIQUE KEY unica_participacao(demanda_id,solidario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- O administrador inicial deve ser criado após a instalação.
-- Use o script create-admin.php e apague-o depois.
