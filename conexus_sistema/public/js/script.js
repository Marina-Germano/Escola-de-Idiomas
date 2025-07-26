// Variáveis e Funções de UI Geral
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
let nav = 0;
let clicked = null;
let allEvents = [];

const newEventModal = document.getElementById('newEventModal');
const deleteEventModal = document.getElementById('deleteEventModal');
const backDrop = document.getElementById('modalBackDrop');
const calendar = document.getElementById('calendar');
const monthDisplay = document.getElementById('monthDisplay');
const weekdays = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];

function openModal(date) {
    clicked = date;
    const eventDay = allEvents.find((event) => event.date === clicked);

    if (!newEventModal || !backDrop) {
        return;
    }

    if (eventDay) {
        const eventText = document.getElementById('eventText');
        if (eventText) eventText.innerText = eventDay.title;
        newEventModal.style.display = 'block';
    } else {
        return;
    }
    backDrop.style.display = 'block';
}

function closeModal() {
    if (newEventModal) newEventModal.style.display = 'none';
    if (deleteEventModal) deleteEventModal.style.display = 'none';
    if (backDrop) backDrop.style.display = 'none';

    clicked = null;
    loadCalendar();
}

function renderCalendarDays(month, year) {
    if (!calendar) {
        console.error("Erro: Elemento 'calendar' não encontrado. O calendário não pode ser renderizado.");
        return;
    }

    const dt = new Date(year, month, 1);
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDayOfMonth = new Date(year, month, 1);

    const dateString = firstDayOfMonth.toLocaleDateString('pt-br', { weekday: 'long' });
    const paddingDays = weekdays.indexOf(dateString.split(', ')[0]);

    if (monthDisplay) {
        monthDisplay.innerText = `${dt.toLocaleDateString('pt-br', { month: 'long' })}, ${year}`;
    }

    calendar.innerHTML = '';
    calendar.style.display = 'grid';
    calendar.style.gridTemplateColumns = 'repeat(7, 1fr)';
    calendar.style.gap = '0.5rem';

    for (let i = 1; i <= paddingDays + daysInMonth; i++) {
        const daySquare = document.createElement('div');
        daySquare.classList.add('day');
        daySquare.style.padding = '1rem';
        daySquare.style.border = '1px solid #ddd';
        daySquare.style.minHeight = '5rem';
        daySquare.style.textAlign = 'center';
        daySquare.style.display = 'flex';
        daySquare.style.flexDirection = 'column';
        daySquare.style.alignItems = 'center';
        daySquare.style.justifyContent = 'flex-start';

        const dayNumber = i - paddingDays;
        const currentMonthPadded = String(month + 1).padStart(2, '0');
        const dayNumberPadded = String(dayNumber).padStart(2, '0');
        const dateFormatted = `${year}-${currentMonthPadded}-${dayNumberPadded}`;

        if (i > paddingDays) {
            const dayNumberSpan = document.createElement('span');
            dayNumberSpan.innerText = dayNumber;
            daySquare.appendChild(dayNumberSpan);

            const today = new Date();
            const todayFormatted = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

            if (dateFormatted === todayFormatted && nav === 0) {
                daySquare.style.backgroundColor = '#d1e7dd';
                daySquare.style.fontWeight = 'bold';
            }

            const eventsForThisDay = allEvents.filter(event => event.date === dateFormatted);

            if (eventsForThisDay.length > 0) {
                eventsForThisDay.forEach(event => {
                    const eventDiv = document.createElement('div');
                    eventDiv.classList.add('event');
                    eventDiv.innerText = event.title;
                    eventDiv.style.marginTop = '0.5rem';
                    eventDiv.style.background = '#84cc16';
                    eventDiv.style.color = '#fff';
                    eventDiv.style.padding = '0.25rem';
                    eventDiv.style.borderRadius = '0.25rem';
                    eventDiv.style.fontSize = '0.8em';
                    eventDiv.style.width = 'fit-content';
                    eventDiv.style.cursor = 'pointer';
                    eventDiv.style.wordBreak = 'break-word';
                    eventDiv.onclick = (e) => {
                        e.stopPropagation();
                        openModal(dateFormatted);
                    };
                    daySquare.appendChild(eventDiv);
                });
            } else {
                daySquare.addEventListener('click', () => openModal(dateFormatted));
            }
        } else {
            daySquare.classList.add('padding');
            daySquare.style.backgroundColor = '#f0f0f0';
        }
        calendar.appendChild(daySquare);
    }
}

async function fetchEvents(month, year) {
    try {
        const apiUrl = `${window.location.origin}/Escola-de-Idiomas-1/conexus_sistema/app/controllers/calendarioController.php?ajax=1&year=${year}&month=${month + 1}`;
        const response = await fetch(apiUrl);

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Erro HTTP! Status: ${response.status} - ${response.statusText}. Resposta do servidor: ${errorText}`);
        }

        const data = await response.json();
        allEvents = data.events || [];
        renderCalendarDays(month, year);
    } catch (error) {
        console.error("Erro fatal ao carregar eventos:", error);
        allEvents = [];
        renderCalendarDays(month, year);
    }
}

function loadCalendar() {
    const dt = new Date();

    if (nav === 0 && typeof phpCurrentYear !== 'undefined' && typeof phpCurrentMonth !== 'undefined') {
        dt.setFullYear(phpCurrentYear);
        dt.setMonth(phpCurrentMonth);
    } else {
        dt.setMonth(dt.getMonth() + nav);
    }

    const month = dt.getMonth();
    const year = dt.getFullYear();

    if (monthDisplay) {
        monthDisplay.innerText = `${dt.toLocaleDateString('pt-br', { month: 'long' })}, ${year}`;
    }

    fetchEvents(month, year);
}

function initButtons() {
    const backButton = document.getElementById('backButton');
    const nextButton = document.getElementById('nextButton');
    const cancelButton = document.getElementById('cancelButton');
    const closeButtonModal = document.getElementById('closeButtonModal');

    if (backButton) {
        backButton.addEventListener('click', () => {
            nav--;
            loadCalendar();
        });
    } else {
        console.error("Erro: Botão 'Voltar' (id='backButton') não encontrado no DOM. Verifique seu HTML.");
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            nav++;
            loadCalendar();
        });
    } else {
        console.error("Erro: Botão 'Próximo' (id='nextButton') não encontrado no DOM. Verifique seu HTML.");
    }

    if (cancelButton) cancelButton.addEventListener('click', closeModal);
    if (closeButtonModal) closeButtonModal.addEventListener('click', closeModal);
}

document.addEventListener('DOMContentLoaded', () => {
    initButtons();
    loadCalendar();
});