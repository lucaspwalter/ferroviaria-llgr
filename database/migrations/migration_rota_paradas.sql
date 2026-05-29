ALTER TABLE `rotas` DROP COLUMN `paradas`;
ALTER TABLE `rotas` DROP COLUMN `numero_paradas`;

CREATE TABLE `rota_paradas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rota_id` int(11) NOT NULL,
  `estacao_id` int(11) DEFAULT NULL,
  `nome_parada` varchar(150) NOT NULL,
  `ordem` int(11) NOT NULL,
  `tempo_parada_minutos` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_rota_id` (`rota_id`),
  KEY `idx_ordem` (`ordem`),
  CONSTRAINT `fk_parada_rota` FOREIGN KEY (`rota_id`) REFERENCES `rotas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_parada_estacao` FOREIGN KEY (`estacao_id`) REFERENCES `estacoes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rota_paradas` (`rota_id`, `nome_parada`, `ordem`) VALUES
(3, 'Estação Araquari', 1),
(3, 'Estação Barra Velha', 2),
(3, 'Estação Itajaí', 3);
