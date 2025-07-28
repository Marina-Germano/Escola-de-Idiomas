// Variáveis e Funções de UI Geral (mantidas como estão)
const toggleBtn = document.getElementById('toggle-btn');
const body = document.body;
let darkMode = localStorage.getItem('dark-mode');

const enableDarkMode = () => {
    if (toggleBtn) toggleBtn.classList.replace('fa-sun', 'fa-moon');
    body.classList.add('dark');
    localStorage.setItem('dark-mode', 'enabled');
};

const disableDarkMode = () => {
    if (toggleBtn) toggleBtn.classList.replace('fa-moon', 'fa-sun');
    body.classList.remove('dark');
    localStorage.setItem('dark-mode', 'disabled');
};

if (darkMode === 'enabled') {
    enableDarkMode();
}

if (toggleBtn) {
    toggleBtn.onclick = () => {
        darkMode = localStorage.getItem('dark-mode');
        if (darkMode === 'disabled') {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
    };
}

const profile = document.querySelector('.header .flex .profile');
const search = document.querySelector('.header .flex .search-form');
const sideBar = document.querySelector('.side-bar');

if (document.querySelector('#user-btn')) {
    document.querySelector('#user-btn').onclick = () => {
        if (profile) profile.classList.toggle('active');
        if (search) search.classList.remove('active');
    };
}

if (document.querySelector('#search-btn')) {
    document.querySelector('#search-btn').onclick = () => {
        if (search) search.classList.toggle('active');
        if (profile) profile.classList.remove('active');
    };
}

if (document.querySelector('#menu-btn')) {
    document.querySelector('#menu-btn').onclick = () => {
        if (sideBar) sideBar.classList.toggle('active');
        body.classList.toggle('active');
    };
}

if (document.querySelector('#close-btn')) {
    document.querySelector('#close-btn').onclick = () => {
        if (sideBar) sideBar.classList.remove('active');
        body.classList.remove('active');
    };
}

window.onscroll = () => {
    if (profile) profile.classList.remove('active');
    if (search) search.classList.remove('active');

    if (window.innerWidth < 1200 && sideBar) {
        sideBar.classList.remove('active');
        body.classList.remove('active');
    }
};

// Variáveis e Funções do Calendário
// 'nav' controla a navegação entre os meses (0 = mês atual, 1 = próximo mês, -1 = mês anterior)
let nav = 0; 
// 'clicked' armazena a data do dia clicado no calendário, se houver um modal de evento
let clicked = null; 
// 'allEvents' armazena todos os eventos que são buscados do servidor PHP
let allEvents = []; 

// Referências aos elementos do DOM para o modal de eventos (se você tiver um)
const newEventModal = document.getElementById('newEventModal');
const deleteEventModal = document.getElementById('deleteEventModal');
const backDrop = document.getElementById('modalBackDrop');

// Referências aos elementos principais do calendário no HTML
const calendar = document.getElementById('calendar');
const monthDisplay = document.getElementById('monthDisplay');
// Nomes dos dias da semana para calcular o preenchimento do calendário
const weekdays = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];

// Função para abrir o modal de evento quando um dia é clicado
function openModal(date) {
    clicked = date; // Salva a data clicada
    // Tenta encontrar um evento para a data clicada
    const eventDay = allEvents.find((event) => event.date === clicked);

    // Verifica se os elementos do modal existem para evitar erros
    if (!newEventModal || !backDrop) {
        return;
    }

    // Se houver evento para o dia, exibe o título no modal
    if (eventDay) {
        const eventText = document.getElementById('eventText');
        if (eventText) eventText.innerText = eventDay.title;
        newEventModal.style.display = 'block';
    } else {
        // Se não houver evento, apenas retorna (ou pode abrir um modal para adicionar evento)
        return;
    }
    backDrop.style.display = 'block'; // Exibe o fundo escuro do modal
}

// Função para fechar o modal de evento
function closeModal() {
    // Esconde os modais e o fundo escuro
    if (newEventModal) newEventModal.style.display = 'none';
    if (deleteEventModal) deleteEventModal.style.display = 'none';
    if (backDrop) backDrop.style.display = 'none';

    clicked = null; // Limpa a data clicada
    loadCalendar(); // Recarrega o calendário para garantir que esteja atualizado
}

// Função principal para renderizar os dias do calendário e os eventos
function renderCalendarDays(month, year) {
    // Verifica se o elemento do calendário existe
    if (!calendar) {
        console.error("Erro: Elemento 'calendar' não encontrado. O calendário não pode ser renderizado.");
        return;
    }

    // Cria um objeto Date para o primeiro dia do mês atual
    const dt = new Date(year, month, 1);
    // Calcula o número total de dias no mês
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    // Obtém o primeiro dia do mês para calcular o preenchimento
    const firstDayOfMonth = new Date(year, month, 1);

    // Obtém o nome do dia da semana do primeiro dia do mês (ex: "domingo")
    const dateString = firstDayOfMonth.toLocaleDateString('pt-br', { weekday: 'long' });
    // Calcula quantos "dias de preenchimento" (dias do mês anterior) são necessários
    const paddingDays = weekdays.indexOf(dateString.split(', ')[0]);

    // Atualiza o texto do display do mês no HTML
    if (monthDisplay) {
        monthDisplay.innerText = `${dt.toLocaleDateString('pt-br', { month: 'long' })}, ${year}`;
    }

    // Limpa o conteúdo atual do calendário
    calendar.innerHTML = '';
    // Define o estilo de grid para o calendário
    calendar.style.display = 'grid';
    calendar.style.gridTemplateColumns = 'repeat(7, 1fr)';
    calendar.style.gap = '0.5rem';

    // Loop para criar os quadrados dos dias (incluindo os dias de preenchimento)
    for (let i = 1; i <= paddingDays + daysInMonth; i++) {
        const daySquare = document.createElement('div');
        daySquare.classList.add('day'); // Adiciona a classe CSS 'day'
        // Aplica estilos inline básicos (idealmente, use CSS externo)
        daySquare.style.padding = '1rem';
        daySquare.style.border = '1px solid #ddd';
        daySquare.style.minHeight = '5rem';
        daySquare.style.textAlign = 'center';
        daySquare.style.display = 'flex';
        daySquare.style.flexDirection = 'column';
        daySquare.style.alignItems = 'center';
        daySquare.style.justifyContent = 'flex-start';

        const dayNumber = i - paddingDays; // Calcula o número real do dia do mês
        // Formata a data para 'YYYY-MM-DD' para comparação com os eventos
        const currentMonthPadded = String(month + 1).padStart(2, '0');
        const dayNumberPadded = String(dayNumber).padStart(2, '0');
        const dateFormatted = `${year}-${currentMonthPadded}-${dayNumberPadded}`;

        // Se for um dia real do mês (não um dia de preenchimento)
        if (i > paddingDays) {
            const dayNumberSpan = document.createElement('span');
            dayNumberSpan.innerText = dayNumber; // Exibe o número do dia
            daySquare.appendChild(dayNumberSpan);

            // Destaca o dia atual
            const today = new Date();
            const todayFormatted = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            if (dateFormatted === todayFormatted && nav === 0) {
                daySquare.style.backgroundColor = '#d1e7dd';
                daySquare.style.fontWeight = 'bold';
            }

            // Filtra os eventos para encontrar os que acontecem neste dia específico
            const eventsForThisDay = allEvents.filter(event => event.date === dateFormatted);

            // Se houver eventos para este dia, cria e adiciona os elementos de evento
            if (eventsForThisDay.length > 0) {
                eventsForThisDay.forEach(event => {
                    const eventDiv = document.createElement('div');
                    eventDiv.classList.add('event'); // Adiciona classe CSS 'event'
                    eventDiv.innerText = event.title; // Exibe o título do evento
                    // Estilos inline para o evento (idealmente, use CSS externo)
                    eventDiv.style.marginTop = '0.5rem';
                    eventDiv.style.background = '#84cc16';
                    eventDiv.style.color = '#fff';
                    eventDiv.style.padding = '0.25rem';
                    eventDiv.style.borderRadius = '0.25rem';
                    eventDiv.style.fontSize = '0.8em';
                    eventDiv.style.width = 'fit-content';
                    eventDiv.style.cursor = 'pointer';
                    eventDiv.style.wordBreak = 'break-word';
                    // Adiciona um listener de clique para abrir o modal do evento
                    eventDiv.onclick = (e) => {
                        e.stopPropagation(); // Impede que o clique no evento ative o clique no dia
                        openModal(dateFormatted);
                    };
                    daySquare.appendChild(eventDiv); // Adiciona o evento ao quadrado do dia
                });
            } else {
                // Se não houver eventos, o clique no dia abre o modal (para adicionar, talvez)
                daySquare.addEventListener('click', () => openModal(dateFormatted));
            }
        } else {
            // Estilos para os dias de preenchimento (vazios)
            daySquare.classList.add('padding');
            daySquare.style.backgroundColor = '#f0f0f0';
        }
        calendar.appendChild(daySquare); // Adiciona o quadrado do dia ao calendário
    }
}

// Função assíncrona para buscar os eventos do servidor PHP
async function fetchEvents(month, year) {
    try {
        // Constrói a URL para a requisição AJAX
        const apiUrl = `${window.location.origin}/escola-de-idiomas/conexus_sistema/app/controllers/calendarioController.php?ajax=1&year=${year}&month=${month + 1}`;
        console.log("URL de requisição:", apiUrl); // MUITO IMPORTANTE para depuração!

        // Faz a requisição HTTP
        const response = await fetch(apiUrl);

        // Verifica se a resposta HTTP foi bem-sucedida (status 200 OK)
        if (!response.ok) {
            // Se não for OK, tenta ler a mensagem de erro do servidor
            const errorText = await response.text();
            console.error(`Erro HTTP! Status: ${response.status} - ${response.statusText}. Resposta do servidor:`, errorText);
            // Lança um erro para ser capturado pelo bloco catch
            throw new Error(`Erro HTTP! Status: ${response.status} - ${response.statusText}`);
        }

        // Converte a resposta para JSON
        const data = await response.json();
        // Armazena os eventos na variável global 'allEvents' (ou um array vazio se não houver eventos)
        allEvents = data.events || [];
        // Renderiza o calendário com os eventos recém-buscados
        renderCalendarDays(month, year);
    } catch (error) {
        // Captura e loga qualquer erro que ocorra durante o fetch ou processamento
        console.error("Erro fatal ao carregar eventos:", error);
        allEvents = []; // Garante que os eventos sejam limpos em caso de erro
        renderCalendarDays(month, year); // Renderiza o calendário mesmo com erro (sem eventos)
    }
}

// Função para carregar o calendário (chamada inicial e ao navegar entre meses)
function loadCalendar() {
    const dt = new Date(); // Cria um objeto Date para o mês/ano atual

    // Ajusta o mês e ano se for a carga inicial e as variáveis PHP existirem
    if (nav === 0 && typeof phpCurrentYear !== 'undefined' && typeof phpCurrentMonth !== 'undefined') {
        dt.setFullYear(phpCurrentYear);
        dt.setMonth(phpCurrentMonth);
    } else {
        // Ajusta o mês com base na navegação (nav)
        dt.setMonth(dt.getMonth() + nav);
    }

    const month = dt.getMonth(); // Mês atual (0-11)
    const year = dt.getFullYear(); // Ano atual

    // Atualiza o display do mês no HTML
    if (monthDisplay) {
        monthDisplay.innerText = `${dt.toLocaleDateString('pt-br', { month: 'long' })}, ${year}`;
    }

    // Busca os eventos para o mês e ano atuais
    fetchEvents(month, year);
}

// Função para inicializar os listeners dos botões de navegação
function initButtons() {
    const backButton = document.getElementById('backButton');
    const nextButton = document.getElementById('nextButton');
    const cancelButton = document.getElementById('cancelButton'); // Botão de cancelar do modal
    const closeButtonModal = document.getElementById('closeButtonModal'); // Botão de fechar do modal

    // Adiciona listeners para os botões de navegação (Voltar/Próximo)
    if (backButton) {
        backButton.addEventListener('click', () => {
            nav--; // Decrementa o contador de navegação
            loadCalendar(); // Recarrega o calendário
        });
    } else {
        console.error("Erro: Botão 'Voltar' (id='backButton') não encontrado no DOM. Verifique seu HTML.");
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            nav++; // Incrementa o contador de navegação
            loadCalendar(); // Recarrega o calendário
        });
    } else {
        console.error("Erro: Botão 'Próximo' (id='nextButton') não encontrado no DOM. Verifique seu HTML.");
    }

    // Adiciona listeners para os botões de fechar/cancelar do modal
    if (cancelButton) cancelButton.addEventListener('click', closeModal);
    if (closeButtonModal) closeButtonModal.addEventListener('click', closeModal);
}

// Garante que o JavaScript só execute depois que o HTML estiver completamente carregado
document.addEventListener('DOMContentLoaded', () => {
    initButtons(); // Inicializa os botões
    loadCalendar(); // Carrega o calendário pela primeira vez
});
