document.addEventListener('DOMContentLoaded', () => {
    loadTheme();
    setupThemeToggle();

    loadAll();
})

async function loadAll() {
    try {
        const response = await fetch('../backend/categorias/index.php');

        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = '../login/index.html';
            }
            throw new Error('Falha ao carregar os dados.');
        }

        const data = await response.json();

        if (data.success) {
            setBox(data.categorias);
        } else {
            console.error(data.message);
        }
    } catch (error) {
        console.log('Erro no fetch inicial: ', error);
    }
}

function setBox(categorias) {
    const titleTarefaContainers = document.querySelectorAll('.title-tarefa');
    titleTarefaContainers.forEach(container => container.innerHTML = '');
    const listadeTarefas = document.querySelectorAll('.lista-de-tarefas');

    categorias.forEach((cat, index) => {
        const firstLetter = cat.nome.charAt(0).toUpperCase();
        const restLetters = cat.nome.slice(1);
        const capitalize = firstLetter + restLetters;
        const currentContainer = titleTarefaContainers[index];

        if (currentContainer) {
            const h1 = document.createElement('h1');
            h1.textContent = capitalize;

            currentContainer.appendChild(h1);
        }

        const currentListBox = listadeTarefas[index];

        if (currentListBox) {
            currentListBox.setAttribute('data-category-id', cat.id_categoria);
        }
    });
}

const THEME_KEY = 'user_theme';

/**
 @param {string} theme
*/

function lightMode(theme) {
    const buttonToggle = document.querySelector('.buttonToggle');
    const sideBar = document.querySelector('.sidebar');


    if (theme === 'light') {
        sideBar.classList.add('sidebar-light');
        if (buttonToggle) {
            buttonToggle.innerHTML = '<i class="fa-solid fa-toggle-off"></i> Dark Mode'
        }
    } else {
        sideBar.classList.remove('sidebar-light');
        if (buttonToggle) {
            buttonToggle.innerHTML = '<i class="fa-solid fa-toggle-off"></i> Light Mode'
        }
    }

    localStorage.setItem(THEME_KEY, theme);
}

function setupThemeToggle() {
    const buttonToggle = document.querySelector('.buttonToggle');

    if (buttonToggle) {
        buttonToggle.addEventListener('click', (e) => {
            e.preventDefault();

            const sideBar = document.querySelector('.sidebar');
            let newTheme;

            if (sideBar.classList.contains('sidebar-light')) {
                newTheme = 'dark';
            } else {
                newTheme = 'light';
            }

            lightMode(newTheme);
        })
    }
}

function loadTheme() {
    const savedTheme = localStorage.getItem(THEME_KEY);

    if (savedTheme) {
        lightMode(savedTheme);
    } else {
        lightMode('dark');
    }
}



