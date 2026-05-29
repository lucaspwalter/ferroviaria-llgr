INSERT INTO `rotas` (`codigo`, `nome`, `origem`, `destino`, `distancia_km`, `tempo_estimado`, `status`, `preco_base`, `observacoes`)
VALUES
('ROTA-005', 'Linha Vale Norte', 'Estação Joinville Norte', 'Estação Jaraguá do Sul', 52.40, '00:58:00', 'ativa', 18.50, 'Serviço regional com integração para polos industriais.'),
('ROTA-006', 'Linha Serra Verde', 'Estação Joinville Central', 'Estação São Bento do Sul', 96.80, '01:55:00', 'ativa', 34.90, 'Trajeto de serra com paradas estratégicas no planalto norte.'),
('ROTA-007', 'Linha Portuária', 'Terminal Intermodal Joinville', 'Porto de São Francisco do Sul', 64.20, '01:12:00', 'ativa', 22.00, 'Linha voltada a conexão entre passageiros, cargas leves e região portuária.'),
('ROTA-008', 'Linha Metropolitana Sul', 'Estação Joinville Sul', 'Estação Itapoá', 78.60, '01:30:00', 'ativa', 29.90, 'Atendimento metropolitano para deslocamentos diários.'),
('ROTA-009', 'Linha Litoral Norte', 'Estação Barra Velha', 'Estação Balneário Camboriú', 69.10, '01:18:00', 'ativa', 27.50, 'Conexão turística e regional pelo litoral norte.'),
('ROTA-010', 'Linha Universitária', 'Estação Central de Joinville', 'Campus Norte', 24.70, '00:32:00', 'ativa', 9.90, 'Rota curta para estudantes e trabalhadores em horário comercial.'),
('ROTA-011', 'Linha Aeroporto', 'Estação Central de Joinville', 'Terminal Aeroporto', 18.30, '00:24:00', 'ativa', 8.50, 'Conexão rápida com o terminal aeroportuário.'),
('ROTA-012', 'Linha Oeste Industrial', 'Estação Glória', 'Distrito Industrial Oeste', 31.60, '00:42:00', 'ativa', 12.90, 'Atendimento aos turnos industriais e centros logísticos.')
ON DUPLICATE KEY UPDATE
    `nome` = VALUES(`nome`),
    `origem` = VALUES(`origem`),
    `destino` = VALUES(`destino`),
    `distancia_km` = VALUES(`distancia_km`),
    `tempo_estimado` = VALUES(`tempo_estimado`),
    `status` = VALUES(`status`),
    `preco_base` = VALUES(`preco_base`),
    `observacoes` = VALUES(`observacoes`);

INSERT INTO `rota_paradas` (`rota_id`, `nome_parada`, `ordem`)
SELECT r.id, p.nome_parada, p.ordem
FROM `rotas` r
JOIN (
    SELECT 'ROTA-005' AS codigo, 'Guaramirim' AS nome_parada, 1 AS ordem UNION ALL
    SELECT 'ROTA-005', 'Schroeder', 2 UNION ALL
    SELECT 'ROTA-006', 'Campo Alegre', 1 UNION ALL
    SELECT 'ROTA-006', 'Rio Negrinho', 2 UNION ALL
    SELECT 'ROTA-007', 'Araquari', 1 UNION ALL
    SELECT 'ROTA-007', 'Zona Portuária', 2 UNION ALL
    SELECT 'ROTA-008', 'Garuva', 1 UNION ALL
    SELECT 'ROTA-008', 'Itapoá Centro', 2 UNION ALL
    SELECT 'ROTA-009', 'Piçarras', 1 UNION ALL
    SELECT 'ROTA-009', 'Itajaí', 2 UNION ALL
    SELECT 'ROTA-010', 'Bucarein', 1 UNION ALL
    SELECT 'ROTA-010', 'Distrito Universitário', 2 UNION ALL
    SELECT 'ROTA-011', 'América', 1 UNION ALL
    SELECT 'ROTA-011', 'Boa Vista', 2 UNION ALL
    SELECT 'ROTA-012', 'Anita Garibaldi', 1 UNION ALL
    SELECT 'ROTA-012', 'Vila Nova', 2
) p ON p.codigo = r.codigo
WHERE NOT EXISTS (
    SELECT 1
    FROM `rota_paradas` rp
    WHERE rp.rota_id = r.id AND rp.ordem = p.ordem AND rp.nome_parada = p.nome_parada
);
