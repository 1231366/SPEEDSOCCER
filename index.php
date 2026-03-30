<?php require_once 'config/db.php'; ?>
<!DOCTYPE html>
<html class="dark" lang="pt"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Speed Soccer | Elite 5-a-side Gaia</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#8eff71", "background": "#0e0e0e", "surface-low": "#131313", "outline-variant": "#494847"
            },
            fontFamily: { "headline": ["Space Grotesk"], "body": ["Inter"] },
          },
        },
      }
    </script>
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .glass-panel { background: rgba(38, 38, 38, 0.4); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
    .hero-gradient { background: linear-gradient(to bottom, rgba(14, 14, 14, 0.2) 0%, rgba(14, 14, 14, 1) 100%); }
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
    
    /* Stepper Mobile */
    @media (max-width: 1023px) {
        .step-hidden { display: none; }
        .step-active { display: block; animation: fadeIn 0.3s ease-in-out; }
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
</head>
<body class="bg-background text-white font-body selection:bg-primary selection:text-black">

<?php if(isset($_GET['status'])): ?>
<div id="status-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/80 backdrop-blur-sm">
    <div class="glass-panel max-w-md w-full p-8 rounded-[2.5rem] border border-white/10 text-center relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors"><span class="material-symbols-outlined">close</span></button>
        <span class="material-symbols-outlined text-primary text-6xl mb-4"><?php echo $_GET['status'] == 'sucesso' ? 'check_circle' : 'error'; ?></span>
        <h3 class="font-headline text-3xl font-black uppercase italic tracking-tighter"><?php echo $_GET['status'] == 'sucesso' ? 'Confirmado!' : 'Erro'; ?></h3>
        <p class="text-gray-400 mt-4 leading-relaxed">
            <?php echo $_GET['status'] == 'sucesso' ? 'Enviámos um e-mail com os detalhes do teu jogo.' : 'O horário foi ocupado ou houve um erro técnico.'; ?>
        </p>
        <button onclick="closeModal()" class="mt-8 w-full bg-primary text-black font-headline font-black py-4 rounded-2xl uppercase text-xs tracking-widest">Fechar</button>
    </div>
</div>
<?php endif; ?>

<nav class="fixed top-0 left-0 right-0 z-50 bg-background/60 backdrop-blur-xl border-b border-white/5">
    <div class="flex justify-between items-center px-8 py-5 max-w-7xl mx-auto">
        <div class="text-2xl font-black italic text-primary tracking-tighter font-headline uppercase">SPEED SOCCER</div>
        <div class="hidden md:flex items-center space-x-10 font-headline font-bold text-[10px] uppercase tracking-[0.2em] text-gray-400">
            <a class="text-primary" href="#campos">Campos</a>
            <a class="hover:text-white transition-colors" href="#reservar">Reservas</a>
            <a class="hover:text-white transition-colors" href="#aniversarios">Festas</a>
            <a class="hover:text-white transition-colors" href="#escolinha">Escolinha</a>
        </div>
        <button onclick="document.getElementById('reservar').scrollIntoView({behavior: 'smooth'})" class="bg-primary text-black font-headline font-black px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-widest hover:shadow-[0_0_20px_rgba(142,255,113,0.4)] transition-all">Reservar</button>
    </div>
</nav>

<main>
    <section class="relative h-screen w-full flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover brightness-[0.35] scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCtKDbKNU0dAEdPYFybWtHkPrSJwRHJok8kynBxXQ5E71RQOcPbWco3ZC2sP2WWFqmTp8MYMM5Ugu0XwTQJY6w1sbwyZCb62kfq-gSg4ufnn5WirloAAq13hpzP74aVsNEPkwbBhJOr41lQGZ7ishh5fYz8iqcMWYgok-3zC8XXTDOQtwxBLEOfGWdaeyH4vKQs6GoGhqBpn2NX0oLd9zNvUSyYy0obIEwsnPEJt52l6qHvM655JIKixFXpvFMfD3hcYrfhr9JNa4Ip"/>
            <div class="absolute inset-0 hero-gradient"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-8 w-full text-center lg:text-left">
            <span class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-1.5 rounded-full mb-8">
                <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                <span class="text-primary text-[10px] font-black tracking-[0.3em] uppercase">Elite Gaia Experience</span>
            </span>
            <h1 class="font-headline text-6xl md:text-9xl font-black leading-[0.85] tracking-tighter text-white mb-10 italic uppercase">JOGA SEMPRE,<br><span class="text-primary">CHOVA OU SOL</span></h1>
            <button onclick="document.getElementById('reservar').scrollIntoView({behavior: 'smooth'})" class="bg-primary text-black font-headline font-black px-12 py-5 rounded-2xl text-lg uppercase italic shadow-xl hover:scale-105 transition-transform">RESERVAR AGORA</button>
    </section>

    <section class="py-32 px-8 max-w-7xl mx-auto scroll-mt-20" id="campos">
        <h2 class="font-headline text-5xl font-black tracking-tighter uppercase italic mb-16">Os Nossos <span class="text-primary">Campos</span></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="group relative aspect-[16/10] overflow-hidden rounded-[2.5rem] bg-surface-low border border-white/5">
                <img class="w-full h-full object-cover opacity-50 group-hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBNW0WbNL71QomeQ9qGk8KXq85jOB1Ii59tYezGB33iJSeC2zhVsbuTapxIu5G3FmDJjDHVLmRqtlB4EQEP9eJfTU2dOoxNCsE2Sx2xvZOSVEylU04-rnSdTPD72gbhYAy4j2UiSJ5d2uWupCJze9uuUkb4CDWA2EbSXm87tzffiLMSn3HAUZuY63CcI_FQZ5n6YCMLf146cu8DXtZrr1Peys2niipPeLD6Da_HDkWsHtutSpqyHvFJik-c3KgFPvHtJcaOAam9C6IB"/>
                <div class="absolute bottom-0 left-0 p-10 w-full">
                    <h3 class="font-headline text-4xl font-black text-white italic">SPEED FIELD 1</h3>
                    <p class="text-primary font-bold mt-2">Relva Monofilamento 50mm</p>
                </div>
            </div>
            <div class="group relative aspect-[16/10] overflow-hidden rounded-[2.5rem] bg-surface-low border border-white/5">
                <img class="w-full h-full object-cover opacity-50 group-hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDyDQN-ZSLGyjuwNupzr8uFBqkjRguCx7vmtLK0GvQv-6pII2qHTzjwENeA2YVmt2NEt2lJIIEOZ4T4Xjik1yiEFRf9L4D4QVKCV5pcxnE-dvBeM1mwFHd0_1WqE43jE6xMcofqqbNS25e05hR5zKxOAU448ufD282jGnDPl3FLA1a54JXBxwgG8GGa7-LlytsC3pUJXblbwuICI5WBMuZ5U3gzI-W4M1_1SEi9jHVegRStwu9WlD_JlQ0p9y6Bg8WbTvk_CcvAtiKK"/>
                <div class="absolute bottom-0 left-0 p-10 w-full">
                    <h3 class="font-headline text-4xl font-black text-white italic">SPEED FIELD 2</h3>
                    <p class="text-primary font-bold mt-2">Fibrilada High-Density</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-32 px-8 max-w-7xl mx-auto scroll-mt-24" id="reservar">
        <div class="mb-12">
            <h2 class="font-headline text-5xl font-black italic uppercase tracking-tighter">Fast <span class="text-primary">Booking</span></h2>
            <p class="text-gray-500 mt-2">Reserva direta e instantânea.</p>
        </div>

        <form action="fazer_reserva.php" method="POST" id="booking-form" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <input type="hidden" name="campo_id" id="campo_id_input" value="1">

            <div id="step-1" class="step-active lg:col-span-8 lg:order-2">
                <div class="glass-panel p-6 md:p-10 rounded-[3rem] border border-white/10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex bg-white/5 p-1 rounded-xl border border-white/10">
                            <button type="button" onclick="setCampo(1, this)" class="campo-btn px-6 py-2 rounded-lg font-headline font-black text-[10px] uppercase bg-primary text-black">Campo 1</button>
                            <button type="button" onclick="setCampo(2, this)" class="campo-btn px-6 py-2 rounded-lg font-headline font-black text-[10px] uppercase text-gray-500">Campo 2</button>
                        </div>
                        <span class="text-[10px] font-black text-primary uppercase animate-pulse tracking-widest">Live Sync</span>
                    </div>
                    <div class="mb-8">
                        <label class="text-[10px] font-black uppercase text-gray-500 mb-3 block tracking-widest">1. Seleciona a Data</label>
                        <input type="date" name="data_jogo" id="data_input" value="<?php echo date('Y-m-d'); ?>" class="w-full lg:w-fit bg-white/5 border-none rounded-2xl p-4 text-white font-bold focus:ring-2 focus:ring-primary">
                    </div>
                    <label class="text-[10px] font-black uppercase text-gray-500 mb-4 block tracking-widest">2. Seleciona o Horário</label>
                    <div id="slots-container" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3 mb-10"></div>
                    
                    <button type="button" onclick="goToStep(2)" class="lg:hidden w-full bg-primary text-black font-headline font-black py-5 rounded-2xl uppercase italic text-lg shadow-lg">Próximo <span class="material-symbols-outlined align-middle">arrow_forward</span></button>
                </div>
            </div>

            <div id="step-2" class="step-hidden lg:step-active lg:col-span-4 lg:order-1">
                <div class="glass-panel p-8 rounded-[2.5rem] border border-white/5 space-y-6">
                    <div class="lg:hidden mb-4 flex items-center gap-2" onclick="goToStep(1)">
                        <span class="material-symbols-outlined text-primary">arrow_back</span>
                        <span class="text-[10px] font-black uppercase text-primary">Voltar aos horários</span>
                    </div>
                    <div class="space-y-4">
                        <input type="text" name="nome" required placeholder="Nome Completo" class="w-full bg-white/5 border-none rounded-2xl p-4 text-white font-bold focus:ring-2 focus:ring-primary">
                        <input type="email" name="email" required placeholder="E-mail" class="w-full bg-white/5 border-none rounded-2xl p-4 text-white font-bold focus:ring-2 focus:ring-primary">
                        <input type="tel" name="telemovel" required placeholder="Telemóvel" class="w-full bg-white/5 border-none rounded-2xl p-4 text-white font-bold focus:ring-2 focus:ring-primary">
                    </div>
                    <button type="submit" class="w-full bg-primary text-black font-headline font-black py-6 rounded-2xl uppercase italic tracking-tighter text-xl shadow-[0_20px_40px_rgba(142,255,113,0.2)] hover:scale-[1.02] transition-all">Confirmar Jogo <span class="material-symbols-outlined align-middle font-black ml-2">bolt</span></button>
                </div>
            </div>
        </form>
    </section>

    <section class="py-32 px-8 max-w-7xl mx-auto scroll-mt-20" id="aniversarios">
        <div class="glass-panel p-12 md:p-20 rounded-[4rem] border border-white/10 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="material-symbols-outlined text-6xl text-primary mb-6">celebration</span>
                <h2 class="font-headline text-5xl md:text-6xl font-black text-white italic uppercase tracking-tighter mb-6">Festa de Craques</h2>
                <p class="text-gray-400 text-lg leading-relaxed mb-10">A melhor festa de aniversário da tua vida. Futebol, lanche completo e muita energia num pavilhão de elite.</p>
                <button class="bg-white text-black font-headline font-black px-10 py-5 rounded-2xl hover:bg-primary transition-all uppercase italic tracking-widest">Pedir Orçamento</button>
            </div>
            <div class="rounded-[3rem] overflow-hidden aspect-square border border-white/10 shadow-2xl">
                <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9W7wGf76E1EXzLj73hMu7NVzMMUgLxnlNEAvVmrFrLagsUyqHlk2ja_nL57xRFrfDpcCnTNb3OOB8JQGuNYLafJ87XQCAh4OrU0AENikR-q1s7TC-jSxy0WxEbIpaZSfYF3480XEMTQXlUhIknzfqv53nIi6vWI_gliz-TXfun0ldcAL6-nlvsa3balN65k78Oj3tuWohrKyXbotv6--DBX6uuLl2KxQxJNwJkjidid6OST1K-ckh3S0NmyU8J9U6hz_2NSMY4IeU"/>
            </div>
        </div>
    </section>

    <section class="py-32 px-8 max-w-7xl mx-auto scroll-mt-20" id="escolinha">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative rounded-[2.5rem] overflow-hidden aspect-[4/3] shadow-2xl">
                <img class="w-full h-full object-cover grayscale-[20%]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9W7wGf76E1EXzLj73hMu7NVzMMUgLxnlNEAvVmrFrLagsUyqHlk2ja_nL57xRFrfDpcCnTNb3OOB8JQGuNYLafJ87XQCAh4OrU0AENikR-q1s7TC-jSxy0WxEbIpaZSfYF3480XEMTQXlUhIknzfqv53nIi6vWI_gliz-TXfun0ldcAL6-nlvsa3balN65k78Oj3tuWohrKyXbotv6--DBX6uuLl2KxQxJNwJkjidid6OST1K-ckh3S0NmyU8J9U6hz_2NSMY4IeU"/>
                <div class="absolute inset-0 bg-primary/10 mix-blend-overlay"></div>
            </div>
            <div>
                <h2 class="font-headline text-5xl font-black text-white mb-6 uppercase italic tracking-tighter leading-none">Escolinha de <span class="text-primary">Futebol</span></h2>
                <p class="text-gray-400 text-lg leading-relaxed mb-8">Onde os futuros craques são forjados. Treinos técnicos focados em agilidade, visão de jogo e espírito de equipa para jovens dos 5 aos 14 anos.</p>
                <a class="text-primary font-headline font-black text-lg underline underline-offset-[12px] decoration-2 hover:decoration-4 transition-all" href="#">SABER MAIS SOBRE A ESCOLA</a>
            </div>
        </div>
    </section>

    <footer class="bg-black border-t border-white/5 pt-24 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-24">
            <div class="lg:col-span-1">
                <div class="text-3xl font-black italic text-primary tracking-tighter mb-8 uppercase">SPEED SOCCER</div>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">Performance e tecnologia em Vila Nova de Gaia. O teu pavilhão de eleição para futebol 5 indoor.</p>
                <div class="flex gap-4">
                    <a href="https://www.facebook.com/p/Speed-Soccer-100066530225454/" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-primary hover:text-black transition-all">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" class="w-5 h-5" alt="Facebook">
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-headline font-black text-white text-[10px] uppercase tracking-[0.3em] mb-8 text-primary">Localização</h4>
                <div class="space-y-4">
                    <p class="text-gray-400 text-sm leading-relaxed">Rua dos Campos Alegres 237,<br>4415-722 Olival,<br>Vila Nova de Gaia</p>
                    <a href="https://www.google.com/maps/search/?api=1&query=Speed+Soccer+Olival+Gaia" target="_blank" class="text-white text-[10px] font-black flex items-center gap-2 group tracking-widest">
                        VER NO MAPA <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_outward</span>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-headline font-black text-white text-[10px] uppercase tracking-[0.3em] mb-8 text-primary">Contactos</h4>
                <div class="space-y-3">
                    <p class="text-white font-black text-xl italic tracking-tighter">+351 912 345 678</p>
                    <p class="text-gray-500 text-sm font-medium">geral@speedsoccer.pt</p>
                </div>
            </div>
            <div>
                <h4 class="font-headline font-black text-white text-[10px] uppercase tracking-[0.3em] mb-8 text-primary">Navegação</h4>
                <ul class="space-y-3 text-sm font-bold text-gray-500 uppercase tracking-widest text-[10px]">
                    <li><a href="#campos" class="hover:text-primary transition-colors">Campos</a></li>
                    <li><a href="#reservar" class="hover:text-primary transition-colors">Reservas</a></li>
                    <li><a href="#aniversarios" class="hover:text-primary transition-colors">Festas</a></li>
                    <li><a href="#escolinha" class="hover:text-primary transition-colors">Escolinha</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-8 py-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-gray-600 text-[10px] font-black uppercase tracking-widest">© <span id="current-year"></span> Speed Soccer Gaia. Built for Speed.</p>
            <div class="flex gap-8 text-[9px] font-black uppercase tracking-widest text-gray-600">
                <a href="#" class="hover:text-primary transition-colors">Termos de Uso</a>
                <a href="#" class="hover:text-primary transition-colors">Privacidade</a>
            </div>
        </div>
    </footer>
</main>

<script>
    function goToStep(step) {
        if (window.innerWidth >= 1024) return;
        const s1 = document.getElementById('step-1');
        const s2 = document.getElementById('step-2');
        if(step === 2) {
            if(!document.querySelector('input[name="hora_inicio"]:checked')) {
                alert("Por favor, seleciona um horário disponível.");
                return;
            }
            s1.classList.replace('step-active', 'step-hidden');
            s2.classList.replace('step-hidden', 'step-active');
        } else {
            s1.classList.replace('step-hidden', 'step-active');
            s2.classList.replace('step-active', 'step-hidden');
        }
        window.scrollTo({ top: document.getElementById('reservar').offsetTop - 100, behavior: 'smooth' });
    }

    function closeModal() {
        const modal = document.getElementById('status-modal');
        modal.remove();
        const url = new URL(window.location);
        url.searchParams.delete('status');
        window.history.replaceState({}, '', url);
    }

    document.getElementById('current-year').textContent = new Date().getFullYear();

    function updateSlots() {
        const data = document.getElementById('data_input').value;
        const campo = document.getElementById('campo_id_input').value;
        const container = document.getElementById('slots-container');
        container.style.opacity = '0.3';
        fetch(`get_disponibilidade.php?data=${data}&campo_id=${campo}`)
            .then(res => res.text())
            .then(html => { container.innerHTML = html; container.style.opacity = '1'; });
    }

    function setCampo(id, btn) {
        document.getElementById('campo_id_input').value = id;
        document.querySelectorAll('.campo-btn').forEach(b => {
            b.classList.remove('bg-primary', 'text-black');
            b.classList.add('text-gray-500');
        });
        btn.classList.add('bg-primary', 'text-black');
        btn.classList.remove('text-gray-500');
        updateSlots();
    }

    document.getElementById('data_input').addEventListener('change', updateSlots);
    window.onload = updateSlots;
</script>
</body></html>