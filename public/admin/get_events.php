<?php
require_once '../../config/db.php';

$stmt = $pdo->query("
    SELECT r.id, r.nome_cliente as title, 
    CONCAT(r.data_jogo, 'T', r.hora_inicio) as start,
    CONCAT(r.data_jogo, 'T', r.hora_fim) as end,
    c.nome as campo_nome,
    r.status
    FROM reservas r
    JOIN campos c ON r.campo_id = c.id
    WHERE r.status != 'cancelado'
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));