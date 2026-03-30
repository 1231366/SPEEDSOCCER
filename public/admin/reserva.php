<?php
require_once '../../config/db.php';

// 1. Buscar campos da BD para o Select
$campos_query = $pdo->query("SELECT * FROM campos");
$campos = $campos_query->fetchAll();

// 2. Lógica de Processamento da Reserva Manual
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $telemovel = filter_input(INPUT_POST, 'telemovel', FILTER_SANITIZE_SPECIAL_CHARS);
    $data = $_POST['data_jogo'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $campo_id = (int)$_POST['campo_id'];
    $status = $_POST['status_pagamento']; // 'pago' ou 'pendente'
    $valor = (float)$_POST['valor_total'];

    try {
        $sql = "INSERT INTO reservas (campo_id, nome_cliente, telemovel, data_jogo, hora_inicio, hora_fim, valor_total, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$campo_id, $nome, $telemovel, $data, $hora_inicio, $hora_fim, $valor, $status]);

        header("Location: admin.php?status=sucesso");
        exit;
    } catch (PDOException $e) {
        $erro = "Erro ao gravar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html class="dark" lang="pt">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <title>Admin | Manual Booking</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: { "primary": "#8eff71", "background": "#0e0e0e", "surface": "#131313", "tertiary": "#88f6ff" },
            fontFamily: { "headline": ["Space Grotesk"], "body": ["Inter"] }
          },
        },
      }
    </script>
    <style>
      .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
      body { background-color: #0e0e0e; color: #ffffff; }
      .glass-panel { background: rgba(38, 38, 38, 0.4); backdrop-filter: blur(16px); }
      input[type="date"], input[type="time"] { color-scheme: dark; }
    </style>
</head>
<body class="antialiased font-body pb-32 lg:pb-0">

    <?php include '../../templates/sidebar.php'; ?>

    <header class="hidden lg:flex fixed top-0 right-0 w-[calc(100%-16rem)] h-16 z-40 bg-[#0e0e0e]/60 backdrop-blur-xl border-b border-white/5 items-center justify-between px-8">
        <h2 class="font-headline font-black uppercase italic tracking-tighter text-white">Manual Terminal</h2>
        <div class="flex items-center gap-4">
            <span class="text-[10px] font-black text-primary border border-primary/20 px-2 py-1 rounded">MASTER ADMIN</span>
        </div>
    </header>

    <main class="ml-0 lg:ml-64 pt-20 lg:pt-24 px-4 lg:px-12 max-w-6xl mx-auto">
        
        <div class="mb-8 lg:mb-12">
            <h2 class="font-headline text-3xl lg:text-5xl font-black tracking-tighter uppercase italic">Manual <span class="text-primary">Booking</span></h2>
            <p class="text-gray-500 text-sm mt-2">Criar nova reserva direta no balcão.</p>
        </div>

        <?php if(isset($erro)): ?>
            <div class="bg-red-500/20 text-red-500 p-4 rounded-xl mb-6 border border-red-500/30 font-bold"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form action="reserva.php" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10">
            
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-surface p-6 lg:p-8 rounded-[2rem] border border-white/5 shadow-xl">
                    <h3 class="font-headline text-lg font-black uppercase italic mb-6 flex items-center gap-3 text-primary">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span> Cliente
                    </h3>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-1">Nome do Cliente</label>
                                <input name="nome" required class="w-full bg-white/5 border-b-2 border-white/10 focus:border-primary transition-all text-white py-3 px-1 border-t-0 border-x-0 focus:ring-0 text-sm font-bold" placeholder="ex: João Silva" type="text"/>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-1">Telemóvel</label>
                                <input name="telemovel" required class="w-full bg-white/5 border-b-2 border-white/10 focus:border-primary transition-all text-white py-3 px-1 border-t-0 border-x-0 focus:ring-0 text-sm font-bold" placeholder="912 345 678" type="tel"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-surface p-6 lg:p-8 rounded-[2rem] border border-white/5 shadow-xl">
                    <h3 class="font-headline text-lg font-black uppercase italic mb-6 flex items-center gap-3 text-tertiary">
                        <span class="w-1.5 h-6 bg-tertiary rounded-full"></span> Recinto & Tempo
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-1">Data</label>
                            <input name="data_jogo" required class="w-full bg-white/5 rounded-xl border-none text-sm font-bold p-4 focus:ring-2 focus:ring-primary" type="date" value="<?php echo date('Y-m-d'); ?>"/>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-1">Campo</label>
                            <select name="campo_id" class="w-full bg-white/5 rounded-xl border-none text-sm font-bold p-4 focus:ring-2 focus:ring-primary appearance-none">
                                <?php foreach($campos as $campo): ?>
                                    <option value="<?php echo $campo['id']; ?>"><?php echo $campo['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-1">Início</label>
                            <input name="hora_inicio" required class="w-full bg-white/5 rounded-xl border-none text-sm font-bold p-4 focus:ring-2 focus:ring-primary" type="time"/>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest ml-1">Fim</label>
                            <input name="hora_fim" required class="w-full bg-white/5 rounded-xl border-none text-sm font-bold p-4 focus:ring-2 focus:ring-primary" type="time"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-6">
                <div class="glass-panel p-8 rounded-[2.5rem] border border-white/10 shadow-2xl">
                    <h3 class="font-headline text-xl font-black uppercase italic mb-6 tracking-tighter">Resumo da Reserva</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-widest">Valor do Jogo</span>
                            <input type="number" name="valor_total" id="valor_total" value="70.00" step="0.01" class="bg-transparent border-none text-right font-headline font-black text-white p-0 focus:ring-0 w-24">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest block ml-1">Estado do Pagamento</label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_pagamento" value="pago" class="peer hidden" checked>
                                <div class="text-center py-4 rounded-2xl bg-white/5 border border-white/10 text-gray-500 font-black uppercase text-[10px] tracking-widest peer-checked:bg-primary peer-checked:text-black transition-all">Pago</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_pagamento" value="pendente" class="peer hidden">
                                <div class="text-center py-4 rounded-2xl bg-white/5 border border-white/10 text-gray-500 font-black uppercase text-[10px] tracking-widest peer-checked:bg-tertiary peer-checked:text-black transition-all">Pendente</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <button type="submit" class="w-full bg-primary text-black font-headline font-black text-lg py-5 rounded-2xl uppercase italic tracking-tighter flex items-center justify-center gap-3 shadow-[0_15px_30px_rgba(142,255,113,0.2)] hover:scale-[1.02] active:scale-95 transition-all">
                        Agendar Agora <span class="material-symbols-outlined font-black">bolt</span>
                    </button>
                    <a href="admin.php" class="w-full text-center text-gray-500 font-headline font-black py-4 uppercase text-xs tracking-widest hover:text-white transition-colors">Cancelar</a>
                </div>
            </div>
        </form>
    </main>

    <div class="fixed -bottom-48 -left-48 w-96 h-96 bg-primary/5 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed -top-48 -right-48 w-96 h-96 bg-tertiary/5 blur-[120px] rounded-full pointer-events-none -z-10"></div>

</body>
</html>