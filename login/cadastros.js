// Capturas para as animações
const btnSignUp = document.querySelector('.submit');
const btnSignIn = document.querySelector('.signIn');
const container = document.querySelector('.container');
const signUp = document.querySelector('.signUp');
const containerCadastro = document.querySelector('.cadastro');
const containerLogin = document.querySelector('.login');

// Animações dos slides
if (btnSignIn) {
    btnSignIn.addEventListener('click', (e) => {
        container.classList.remove('active');
        containerLogin.classList.add('active1');
        containerCadastro.classList.remove('active1');
    })
}

// Animações dos slides
if (signUp) {
    signUp.addEventListener('click', (e) => {
        container.classList.add('active');
        containerCadastro.classList.add('active1');
        containerLogin.classList.remove('active1');
    })
}

function formSignUp() {
    // Capturas do formulário de cadastro
    const formCadastro = document.querySelector('.form-cadastro');
    const formMessages = document.querySelector('.login .form-messages');
    const formCadastroMessage = document.querySelector('.form-cadastro-message');

    // Formulário de cadastro
    if (formCadastro) {
        formCadastro.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(formCadastro);

            fetch('../backend/usuarios/store.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        container.classList.add('active');
                        containerCadastro.classList.add('active1');
                        containerLogin.classList.remove('active1');
                        formMessages.classList.add('success');
                        formMessages.textContent = data.message;

                        formCadastroMessage.classList.remove('error');
                        formCadastroMessage.textContent = '';
                        formCadastro.reset();
                    } else {
                        formCadastroMessage.classList.add('error');
                        formCadastroMessage.textContent = data.message;
                    };
                }).catch(error => console.log(error));
        });
    };
};

formSignUp();

// Lógica de captura das informações de login
function formSignIn() {
    const formLogin = document.querySelector('.form-login');
    const formMessages = document.querySelector('.login .form-messages');

    if (formLogin) {
        formLogin.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(formLogin);

            fetch('../backend/usuarios/login.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                        formMessages.classList.remove('error');
                        formMessages.classList.remove('success');
                        formMessages.textContent = ''
                        formLogin.reset();
                    } else {
                        formMessages.classList.add('error');
                        formMessages.textContent = data.message;
                    }
                }).catch(error => {
                    console.error('Erro na requisição', error);
                })
        });
    }
}

formSignIn();