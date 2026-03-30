<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-[#0e0e0e]/80 backdrop-blur-xl border-t border-white/10 z-[100] px-6 py-3 flex justify-between items-center">
    <a href="admin.php" class="flex flex-col items-center gap-1 <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'text-primary' : 'text-gray-500'; ?>">
        <span class="material-symbols-outlined text-2xl">dashboard</span>
        <span class="text-[9px] font-bold uppercase">Home</span>
    </a>
    <a href="agenda.php" class="flex flex-col items-center gap-1 <?php echo basename($_SERVER['PHP_SELF']) == 'agenda.php' ? 'text-primary' : 'text-gray-500'; ?>">
        <span class="material-symbols-outlined text-2xl">calendar_month</span>
        <span class="text-[9px] font-bold uppercase">Agenda</span>
    </a>
    <a href="reserva.php" class="bg-primary text-black w-14 h-14 rounded-2xl flex items-center justify-center -mt-12 shadow-[0_0_20px_rgba(142,255,113,0.4)] border-4 border-[#0e0e0e]">
        <span class="material-symbols-outlined font-black text-3xl">add</span>
    </a>
    <a href="clientes.php" class="flex flex-col items-center gap-1 text-gray-500">
        <span class="material-symbols-outlined text-2xl">group</span>
        <span class="text-[9px] font-bold uppercase">Clientes</span>
    </a>
    <a href="logout.php" class="flex flex-col items-center gap-1 text-gray-500">
        <span class="material-symbols-outlined text-2xl">logout</span>
        <span class="text-[9px] font-bold uppercase">Sair</span>
    </a>
</nav>

<aside class="hidden lg:flex h-screen w-64 fixed left-0 top-0 border-r border-white/5 bg-[#0e0e0e] flex-col py-8 px-4 z-50">
    <div class="mb-10 px-4">
        <h1 class="text-2xl font-black tracking-tighter text-primary italic font-headline uppercase">SS GAIA</h1>
        <p class="font-headline tracking-tight text-xs font-bold uppercase text-gray-500 mt-1">Admin Control</p>
    </div>
    <nav class="flex-1 space-y-2 no-scrollbar overflow-y-auto">
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all font-bold font-headline text-sm uppercase <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'text-primary bg-[#131313] border-l-4 border-primary' : 'text-gray-500 hover:text-white hover:bg-[#262626]'; ?>" href="admin.php">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all font-headline text-sm uppercase <?php echo basename($_SERVER['PHP_SELF']) == 'agenda.php' ? 'text-primary bg-[#131313] border-l-4 border-primary' : 'text-gray-500 hover:text-white hover:bg-[#262626]'; ?>" href="agenda.php">
            <span class="material-symbols-outlined">calendar_month</span> Agenda
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all font-headline text-sm uppercase <?php echo basename($_SERVER['PHP_SELF']) == 'reserva.php' ? 'text-primary bg-[#131313] border-l-4 border-primary' : 'text-gray-500 hover:text-white hover:bg-[#262626]'; ?>" href="reserva.php">
            <span class="material-symbols-outlined">confirmation_number</span> Reservas
        </a>
    </nav>
    <div class="mt-auto px-4">
        <a href="reserva.php" class="w-full bg-primary text-black font-headline font-bold py-4 rounded-xl flex items-center justify-center gap-2 hover:opacity-90 shadow-[0_0_20px_rgba(142,255,113,0.3)]">
            <span class="material-symbols-outlined font-bold">add_circle</span> NOVO JOGO
        </a>
    </div>
</aside>