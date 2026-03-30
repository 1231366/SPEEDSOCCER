<?php
// cancelar.php
require_once 'config/db.php'; // Carrega o env_loader.php e a ligação PDO

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);

if (!$id || !$token) {
    die("Parâmetros inválidos.");
}

// Validação de segurança usando o APP_SECRET do .env
if ($token === md5($id . $_ENV['APP_SECRET'])) {
    $stmt = $pdo->prepare("UPDATE reservas SET status = 'cancelado' WHERE id = ?");
    $stmt->execute([$id]);
    
    echo "<body style='background:#0e0e0e; color:white; font-family:sans-serif; text-align:center; padding-top:100px;'>";
    echo "<h1 style='color:#8eff71;'>Reserva Cancelada</h1>";
    echo "<p>O teu horário foi libertado com sucesso. Esperamos ver-te em breve!</p>";
    echo "<a href='index.php' style='color:#8eff71; text-decoration:none; font-weight:bold;'>Voltar ao Site</a>";
    echo "</body>";
} else {
    die("<body style='background:#0e0e0e; color:white; text-align:center; padding-top:100px;'><h1>Token inválido.</h1><p>Ação não permitida.</p></body>");
}