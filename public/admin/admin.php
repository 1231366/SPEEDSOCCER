<?php
// 1. Diagnóstico de Erros (Fundamental para dev)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Caminho corrigido com base na tua estrutura real (public/admin/)
// Precisamos de ../../ para chegar à raiz do projeto
try {
    require_once '../../config/db.php'; 
} catch (Exception $e) {
    die("Erro Crítico: Não foi possível encontrar o ficheiro config/db.php. Verifica se o caminho está correto.");
}

try {
    // 3. Query de Estatísticas (Mês Atual)
    $stats_query = $pdo->query("
        SELECT 
            SUM(CASE WHEN status = 'pago' THEN valor_total ELSE 0 END) as receita_mensal,
            COUNT(id) as total_reservas
        FROM reservas 
        WHERE status != 'cancelado' 
        AND MONTH(data_jogo) = MONTH(CURRENT_DATE()) 
        AND YEAR(data_jogo) = YEAR(CURRENT_DATE())
    ");
    $stats = $stats_query->fetch();

    $receita = $stats['receita_mensal'] ?? 0;
    $num_reservas = $stats['total_reservas'] ?? 0;

    // 4. Reservas de Hoje
    $hoje_query = $pdo->query("SELECT COUNT(*) FROM reservas WHERE data_jogo = CURDATE() AND status != 'cancelado'");
    $hoje_reservas = $hoje_query->fetchColumn() ?? 0;

    // 5. Lista de Próximos Jogos (Limitado a 8)
    $jogos_query = $pdo->query("
        SELECT r.*, c.nome as nome_campo 
        FROM reservas r 
        LEFT JOIN campos c ON r.campo_id = c.id 
        WHERE (r.data_jogo > CURDATE() OR (r.data_jogo = CURDATE() AND r.hora_inicio >= CURTIME()))
        AND r.status != 'cancelado'
        ORDER BY r.data_jogo ASC, r.hora_inicio ASC 
        LIMIT 8
    ");
    $proximos_jogos = $jogos_query->fetchAll();

    // 6. Ocupação (Baseada em 28 slots diários)
    $ocupacao_hoje = ($hoje_reservas > 0) ? round(($hoje_reservas / 28) * 100) : 0;

} catch (PDOException $e) {
    die("Erro na Base de Dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html class="dark" lang="pt">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <title>Speed Soccer Gaia | Admin Control</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                "primary": "#8eff71", "background": "#0e0e0e", "surface-container-low": "#131313", "outline-variant": "#494847"
              },
              fontFamily: { "headline": ["Space Grotesk"], "body": ["Inter"] }
            }
          }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-panel { background: rgba(38, 38, 38, 0.4); backdrop-filter: blur(16px); }
        body { background-color: #0e0e0e; color: #ffffff; }
    </style>
</head>
<body class="antialiased">

    <?php include '../../templates/sidebar.php'; ?>

    <header class="fixed top-0 right-0 w-full lg:w-[calc(100%-16rem)] h-16 z-40 bg-[#0e0e0e]/60 backdrop-blur-xl flex items-center justify-between px-6 lg:px-8 border-b border-white/5">
        <div class="lg:hidden text-primary font-black italic text-xl tracking-tighter">SS GAIA</div>
        <div class="hidden lg:flex items-center bg-surface-container-low px-4 py-1.5 rounded-full border border-white/5 w-96">
            <span class="material-symbols-outlined text-gray-500 text-xl">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-sm w-full text-white" placeholder="Pesquisar..." type="text"/>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-white uppercase">Master Admin</p>
                <p class="text-[10px] text-primary uppercase font-bold tracking-widest">Sessão Ativa</p>
            </div>
            <div class="w-9 h-9 rounded-full border-2 border-primary/30 flex items-center justify-center bg-primary/10">
                <span class="material-symbols-outlined text-primary text-xl font-bold">person</span>
            </div>
        </div>
    </header>

    <main class="ml-0 lg:ml-64 pt-24 px-4 lg:px-8 pb-32 lg:pb-12 min-h-screen">
        <section class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-4xl lg:text-6xl font-black font-headline tracking-tighter text-white uppercase italic leading-none">
                    Tactical <span class="text-primary">Overview</span>
                </h2>
                <p class="text-gray-400 text-sm mt-2">Dados do centro em tempo real.</p>
            </div>
            <div class="bg-surface-container-low px-4 py-2 rounded-lg border border-white/5 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-sm font-bold">calendar_today</span>
                <span class="text-[10px] font-headline font-bold uppercase tracking-tight"><?php echo date('d M, Y'); ?></span>
            </div>
        </section>

        <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-surface-container-low p-6 rounded-3xl border-l-4 border-primary border border-white/5">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Receita Mensal</p>
                <h3 class="text-xl lg:text-3xl font-black font-headline">€<?php echo number_format($receita, 0, ',', '.'); ?></h3>
            </div>
            <div class="bg-surface-container-low p-6 rounded-3xl border-l-4 border-tertiary border border-white/5">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Reservas Total</p>
                <h3 class="text-xl lg:text-3xl font-black font-headline"><?php echo $num_reservas; ?></h3>
            </div>
            <div class="bg-surface-container-low p-6 rounded-3xl border-l-4 border-primary border border-white/5">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Hoje</p>
                <h3 class="text-xl lg:text-3xl font-black font-headline"><?php echo $hoje_reservas; ?> <span class="text-sm">jogos</span></h3>
            </div>
            <div class="bg-surface-container-low p-6 rounded-3xl border-l-4 border-white border border-white/5">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Ocupação</p>
                <h3 class="text-xl lg:text-3xl font-black font-headline"><?php echo $ocupacao_hoje; ?>%</h3>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 glass-panel rounded-[2.5rem] overflow-hidden border border-white/5">
                <div class="p-8 flex items-center justify-between border-b border-white/5">
                    <h4 class="font-headline font-black uppercase italic tracking-tighter text-xl text-white">Timeline de Jogos</h4>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white/5 text-[10px] font-black uppercase text-gray-500">
                            <tr>
                                <th class="px-8 py-5 tracking-widest">Cliente</th>
                                <th class="px-8 py-5 tracking-widest">Horário</th>
                                <th class="px-8 py-5 tracking-widest">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach($proximos_jogos as $jogo): ?>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-8 py-5 font-bold text-sm text-white"><?php echo htmlspecialchars($jogo['nome_cliente']); ?></td>
                                <td class="px-8 py-5">
                                    <p class="text-xs font-black text-primary uppercase italic"><?php echo $jogo['nome_campo'] ?? 'Campo'; ?></p>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase"><?php echo date('H:i', strtotime($jogo['hora_inicio'])); ?> - <?php echo ($jogo['data_jogo'] == date('Y-m-d')) ? 'Hoje' : date('d/m', strtotime($jogo['data_jogo'])); ?></p>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-2 py-1 rounded text-[9px] font-black uppercase <?php echo $jogo['status'] == 'pago' ? 'bg-primary text-black' : 'bg-white/10 text-gray-400'; ?>">
                                        <?php echo $jogo['status']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($proximos_jogos)): ?>
                                <tr><td colspan="3" class="px-8 py-20 text-center text-gray-500 italic uppercase text-xs tracking-widest">Sem marcações ativas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="glass-panel rounded-[2.5rem] border border-white/5 p-8">
                <h4 class="font-headline font-black uppercase italic tracking-tighter text-xl mb-8">Performance</h4>
                <div class="flex items-end justify-between h-40 gap-3 mb-8">
                    <div class="flex-1 bg-primary/20 rounded-2xl" style="height: 40%"></div>
                    <div class="flex-1 bg-primary rounded-2xl shadow-[0_0_20px_#8eff71]" style="height: 90%"></div>
                    <div class="flex-1 bg-primary/50 rounded-2xl" style="height: 60%"></div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 rounded-2xl bg-white/5">
                        <span class="text-[10px] font-black uppercase text-gray-500 tracking-widest">Sistema</span>
                        <span class="text-primary font-black">OPTIMIZED</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="hidden lg:flex fixed bottom-8 right-8 items-center gap-3">
        <div class="bg-surface-container-high px-6 py-3 rounded-full border border-white/10 flex items-center gap-3 shadow-2xl">
            <div class="w-2 h-2 bg-primary rounded-full animate-ping"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-white italic">Telemetry Live</span>
        </div>
    </div>

</body>
</html>