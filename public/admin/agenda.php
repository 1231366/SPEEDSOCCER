<?php
require_once '../../config/db.php';
?>
<!DOCTYPE html>
<html class="dark" lang="pt">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <title>Speed Soccer Gaia | Live Schedule</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;700;900&family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: { "primary": "#8eff71", "background": "#0e0e0e", "surface": "#131313" },
              fontFamily: { "headline": ["Space Grotesk"], "body": ["Inter"] }
            }
          }
        }
    </script>

    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #0e0e0e; color: #ffffff; overflow: hidden; }
        
        /* Custom FullCalendar Neon Styling */
        .fc { --fc-border-color: rgba(255,255,255,0.05); --fc-page-bg-color: #0e0e0e; }
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid rgba(255,255,255,0.05); }
        .fc .fc-toolbar-title { font-family: 'Space Grotesk'; font-weight: 900; text-transform: uppercase; font-style: italic; font-size: 1.25rem !important; }
        .fc .fc-button-primary { background: #1a1919; border: 1px solid #333; font-weight: bold; text-transform: uppercase; font-size: 0.7rem; }
        .fc .fc-button-primary:hover { background: #8eff71; color: #000; }
        .fc .fc-button-active { background: #8eff71 !important; color: #000 !important; border: none !important; }
        
        /* Eventos Premium */
        .fc-event { border: none !important; padding: 2px 5px; border-radius: 8px !important; cursor: grab; }
        .fc-v-event { background-color: rgba(142, 255, 113, 0.15) !important; border-left: 4px solid #8eff71 !important; }
        .fc-event-title { font-weight: 800; font-size: 11px; color: #fff; }
        .fc-timegrid-slot { height: 60px !important; }
        .fc-timegrid-now-indicator-line { border-color: #ff7351 !important; }
        
        /* Mobile Optimization */
        @media (max-width: 768px) {
            .fc-header-toolbar { display: flex; flex-direction: column; gap: 10px; }
            .fc .fc-toolbar-title { font-size: 1rem !important; }
        }
    </style>
</head>
<body class="antialiased font-body h-screen flex flex-col">

    <?php include '../../templates/sidebar.php'; ?>

    <header class="fixed top-0 right-0 w-full lg:w-[calc(100%-16rem)] h-16 z-40 bg-[#0e0e0e]/60 backdrop-blur-xl flex items-center justify-between px-6 border-b border-white/5">
        <div class="lg:hidden text-primary font-black italic text-xl tracking-tighter">SCHEDULE</div>
        <div class="hidden lg:flex items-center gap-4">
            <span class="text-[10px] font-black text-primary bg-primary/10 px-3 py-1 rounded-full animate-pulse tracking-widest">LIVE TELEMETRY</span>
        </div>
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded-full border-2 border-primary/30 flex items-center justify-center bg-primary/10">
                <span class="material-symbols-outlined text-primary text-sm">person</span>
            </div>
        </div>
    </header>

    <main class="ml-0 lg:ml-64 pt-16 flex-1 flex flex-col">
        <div id="calendar" class="p-4 lg:p-8 flex-1 overflow-hidden"></div>
    </main>

    <div id="edit-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md">
        <div class="bg-surface p-8 rounded-[2.5rem] border border-white/10 max-w-sm w-full shadow-2xl">
            <h3 class="font-headline text-2xl font-black text-white italic uppercase mb-6">Editar Reserva</h3>
            <div id="modal-details" class="space-y-4 text-sm text-gray-400 mb-8">
                </div>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="closeEditModal()" class="py-4 rounded-2xl bg-white/5 text-white font-bold uppercase text-[10px] tracking-widest">Fechar</button>
                <button onclick="deleteReserva()" class="py-4 rounded-2xl bg-red-500/20 text-red-500 font-bold uppercase text-[10px] tracking-widest border border-red-500/30">Eliminar</button>
            </div>
        </div>
    </div>

    <script>
        let currentEventId = null;

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'pt',
                slotMinTime: '08:00:00',
                slotMaxTime: '24:00:00',
                allDaySlot: false,
                editable: true, // Permite Drag & Drop
                selectable: true,
                nowIndicator: true,
                events: 'get_events.php', // Endpoint que vamos criar agora
                
                // Arrastar e Soltar (Atualiza na BD automaticamente)
                eventDrop: function(info) {
                    updateEventTime(info.event);
                },
                
                // Redimensionar (Mudar duração)
                eventResize: function(info) {
                    updateEventTime(info.event);
                },

                // Clique para editar
                eventClick: function(info) {
                    showEditModal(info.event);
                }
            });
            calendar.render();
        });

        function updateEventTime(event) {
            const start = event.startStr;
            const end = event.endStr;
            const id = event.id;

            fetch('update_reserva.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&start=${start}&end=${end}`
            }).then(res => res.json()).then(data => {
                if(!data.success) alert('Erro ao atualizar!');
            });
        }

        function showEditModal(event) {
            currentEventId = event.id;
            const modal = document.getElementById('edit-modal');
            const details = document.getElementById('modal-details');
            details.innerHTML = `
                <p><strong class="text-primary">Cliente:</strong> ${event.title}</p>
                <p><strong class="text-primary">Início:</strong> ${event.start.toLocaleString()}</p>
                <p><strong class="text-primary">Campo:</strong> ${event.extendedProps.campo_nome || 'N/A'}</p>
            `;
            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }
    </script>

</body>
</html>