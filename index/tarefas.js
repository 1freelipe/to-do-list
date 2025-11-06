document.addEventListener('DOMContentLoaded', () => {
    loadTheme();
    setupThemeToggle();

    loadAll();
    addTarefa();
    initListeners();

})

async function loadAll() {
    try {
        const response = await fetch('../backend/tarefas/view.php');

        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = '../login/index.html';
            }
            throw new Error('Falha ao carregar os dados.');
        }

        const data = await response.json();

        if (data.success) {
            preencherSelect(data.categorias);
            distributeTasks(data.tarefas);
        } else {
            console.error(data.message);
        }
    } catch (error) {
        console.log('Erro no fetch inicial: ', error);
    }
}

function preencherSelect(categorias) {
    const select = document.querySelector('.select-categoria');
    select.innerHTML = '<option value="">Categoria</option>';
    const titleTarefaContainers = document.querySelectorAll('.title-tarefa');
    titleTarefaContainers.forEach(container => container.innerHTML = '');
    const listadeTarefas = document.querySelectorAll('.lista-de-tarefas');

    categorias.forEach((cat, index) => {
        const option = document.createElement('option');
        option.value = cat.id_categoria;
        const firstLetter = cat.nome.charAt(0).toUpperCase();
        const restLetters = cat.nome.slice(1);
        const capitalize = firstLetter + restLetters;
        option.textContent = capitalize;
        select.appendChild(option);

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

function distributeTasks(tarefas) {
    tarefas.forEach(task => {
        const container = document.querySelector(`.lista-de-tarefas[data-category-id="${task.id_categoria}"]`);

        if (container) {
            const taskElement = createE(task);

            const rowContainer = document.createElement('div');
            rowContainer.classList.add('row-container');

            const buttonWrapper = document.createElement('div');
            buttonWrapper.classList.add('task-buttons');

            const edit = document.createElement('button');
            edit.type = 'button';
            edit.classList.add('btnEdit');
            edit.innerHTML = '<i class="fa-solid fa-pencil"></i>';

            const check = document.createElement('button');
            check.type = 'button';
            check.classList.add('btnCheck');
            check.innerHTML = '<i class="fa-solid fa-circle-check"></i>';

            const deleteBtn = document.createElement('button');
            deleteBtn.type = '';
            deleteBtn.classList.add('btnDelete');
            deleteBtn.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';

            buttonWrapper.appendChild(edit);
            buttonWrapper.appendChild(check);
            buttonWrapper.appendChild(deleteBtn);

            rowContainer.appendChild(taskElement)
            rowContainer.appendChild(buttonWrapper);

            container.appendChild(rowContainer);
        }
    })
}

function createE(task) {
    const taskElement = document.createElement('div');
    taskElement.setAttribute('data-task-id', task.id_tarefa || task.id);
    taskElement.classList.add('tarefa-item');

    const textSpan = document.createElement('span');
    textSpan.textContent = task.descricao;

    taskElement.appendChild(textSpan);
    return taskElement;
}

function initListeners() {
    const mainContainer = document.querySelector('.main-content');
    const messages = document.querySelector('.messages');

    mainContainer.addEventListener('click', (e) => {
        const deleteButton = e.target.closest('.btnDelete');

        if (deleteButton) {

            if (!confirm('Tem certeza que deseja excluir essa tarefa?'))
                return;
            const taskRow = deleteButton.closest('.row-container');
            const taskItem = taskRow.querySelector('.tarefa-item');
            const taskId = taskItem.dataset.taskId;

            const formData = new FormData();
            formData.append('id_tarefa', taskId);

            fetch('../backend/tarefas/delete.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    messages.textContent = data.message;

                    if (data.success) {
                        taskRow.style.transition = 'opacity 0.5s ease';
                        taskRow.style.opacity = '0';

                        setTimeout(() => {
                            taskRow.remove();
                        }, 500)
                    }

                    setTimeout(() => {
                        messages.textContent = '';
                    }, 2500)
                })
                .catch(error => {
                    console.log(error);
                })
            return;
        }

        const editButton = e.target.closest('.btnEdit');
        if (editButton) {
            const taskRow = editButton.closest('.row-container');
            const taskItem = taskRow.querySelector('.tarefa-item');
            const taskId = taskItem.dataset.taskId;
            const textSpan = taskItem.querySelector('span');
            const currentText = textSpan.textContent;

            const mainFormInput = document.querySelector('.input-tarefas');
            const mainFormHiddenInput = document.getElementById('input_tarefa_editando');
            const mainFormSubmitBtn = document.querySelector('.BtnSubmit');

            mainFormHiddenInput.value = taskId;
            mainFormInput.value = currentText;
            mainFormSubmitBtn.textContent = 'Salvar Edição';

            mainFormInput.focus();

            window.scrollTo(0, 0);
            return;
        }

        const checkButton = e.target.closest('.btnCheck');
        if (checkButton) {
            const taskRow = checkButton.closest('.row-container');
            const taskItem = taskRow.querySelector('.tarefa-item');
            const taskId = taskItem.dataset.taskId;

            const formData = new FormData();
            formData.append('id_tarefa', taskId);

            fetch('../backend/tarefas/complete.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messages.textContent = data.message;
                        taskRow.style.transition = 'opacity 0.5s ease';
                        taskRow.style.opacity = '0';

                        setTimeout(() => {
                            taskRow.remove();
                        }, 500);

                        setTimeout(() => {
                            messages.textContent = '';
                        }, 2500);
                    }
                }).catch(error => console.log(error));
            return;
        }
    })
}


function addTarefa() {
    const messages = document.querySelector('.messages');
    const formTarefas = document.querySelector('.form-tarefas');
    const mainFormHiddenInput = document.getElementById('input_tarefa_editando');
    const selectCategory = document.querySelector('.select-categoria');
    const submitButton = document.querySelector('.BtnSubmit');

    if (formTarefas) {
        formTarefas.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(formTarefas);
            const editingId = mainFormHiddenInput.value;

            let url;
            let successCallback;

            if (editingId) {
                url = '../backend/tarefas/edit.php';
                formData.append('id_tarefa', editingId);

                successCallback = (data) => {
                    const newText = formData.get('tarefa');
                    const taskItem = document.querySelector(`.tarefa-item[data-task-id="${editingId}"]`);
                    const textSpan = taskItem.querySelector('span');

                    messages.textContent = data.message;
                    setTimeout(() => {
                        messages.textContent = '';
                    }, 2500)

                    textSpan.textContent = newText;
                    selectCategory.value = formData.get('id_categoria');
                };
            } else {
                url = '../backend/tarefas/store.php';
                successCallback = (data) => {
                    const newTask = data.task;
                    const taskListContainer = document.querySelector(`.lista-de-tarefas[data-category-id="${newTask.id_categoria}"]`)

                    if (taskListContainer) {
                        distributeTasks([data.task]);
                    }

                    messages.textContent = data.message;

                    setTimeout(() => {
                        messages.textContent = '';
                    }, 2500)
                }
            }

            fetch(url, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successCallback(data);

                        formTarefas.reset();
                        mainFormHiddenInput.value = '';
                        submitButton.textContent = 'Adicionar';
                    } else {
                        messages.textContent = data.message;

                        setTimeout(() => {
                            messages.textContent = '';
                        }, 2500)
                    }
                }).catch(error => console.log(error));
        });
    };
};

const THEME_KEY = 'user_theme';

/*
 @param {string} theme
*/

function lightMode(theme) {
    const buttonToggle = document.querySelector('.buttonToggle');
    const sideBar = document.querySelector('.sidebar');
    const btnSubmit = document.querySelector('.BtnSubmit');


    if (theme === 'light') {
        sideBar.classList.add('sidebar-light');
        btnSubmit.classList.add('btnSubmit-light');
        if (buttonToggle) {
            buttonToggle.innerHTML = '<i class="fa-solid fa-toggle-off"></i> Dark Mode'
        }
    } else {
        sideBar.classList.remove('sidebar-light');
        btnSubmit.classList.remove('btnSubmit-light');
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



