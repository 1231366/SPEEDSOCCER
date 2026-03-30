<?php
require_once 'config/db.php';

$id = $_GET['id'];
$token = $_GET['token'];

// Verificação simples de segurança
if ($token === md5($id . "secret")) {
    $stmt = $pdo->prepare("UPDATE reservas SET status = 'cancelado' WHERE id = ?");
    $stmt->execute([$id]);
    echo "<body style='background:#0e0e0e; color:white; font-family:sans-serif; text-align:center; padding-top:100px;'>";
    echo "<h1 style='color:#8eff71;'>Reserva Cancelada</h1>";
    echo "<p>O teu horário foi libertado com sucesso. Esperamos ver-te em breve!</p>";
    echo "<a href='index.php' style='color:#8eff71;'>Voltar ao Site</a>";
    echo "</body>";
} else {
    die("Token inválido.");
}