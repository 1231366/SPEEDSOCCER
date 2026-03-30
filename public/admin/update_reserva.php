<?php
require_once '../../config/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $start = new DateTime($_POST['start']);
    $end = new DateTime($_POST['end']);

    $data = $start->format('Y-m-d');
    $hora_inicio = $start->format('H:i:s');
    $hora_fim = $end->format('H:i:s');

    $stmt = $pdo->prepare("UPDATE reservas SET data_jogo = ?, hora_inicio = ?, hora_fim = ? WHERE id = ?");
    $success = $stmt->execute([$data, $hora_inicio, $hora_fim, $id]);

    echo json_encode(['success' => $success]);
}