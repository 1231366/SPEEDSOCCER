<?php
require_once 'config/db.php';
$data = $_GET['data'] ?? date('Y-m-d');
$campo_id = $_GET['campo_id'] ?? 1;

$stmt = $pdo->prepare("SELECT hora_inicio FROM reservas WHERE data_jogo = ? AND campo_id = ? AND status != 'cancelado'");
$stmt->execute([$data, $campo_id]);
$ocupados = $stmt->fetchAll(PDO::FETCH_COLUMN);
$ocupados_formatados = array_map(function($h) { return substr($h, 0, 5); }, $ocupados);

$horarios = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];

foreach ($horarios as $h) {
    $esta_ocupado = in_array($h, $ocupados_formatados);
    $class = $esta_ocupado 
        ? 'bg-white/5 opacity-20 cursor-not-allowed' 
        : 'bg-surface-container-low border border-primary/20 text-primary peer-checked:bg-primary peer-checked:text-black hover:border-primary';
    
    echo "
    <label class='relative cursor-pointer'>
        <input type='radio' name='hora_inicio' value='$h' class='peer absolute opacity-0' ".($esta_ocupado ? 'disabled' : '')." required>
        <div class='py-3 px-2 text-center rounded-xl border transition-all $class'>
            <span class='text-sm font-black font-headline'>$h</span>
        </div>
    </label>";
}