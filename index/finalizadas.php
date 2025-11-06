<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header('Location: ../login/index.html');
    exit();
}

$nome_usuario = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="finalizadas.css">
    <title>Lista de Tarefas - Finalizadas</title>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="sideTitle">
                <h1 class="todo">TO-DO-LIST</h1>
            </div>
            <ul>
                <li class="user"><a href="#"><i class="fa-solid fa-circle-user"></i>Olá,
                        <?php echo $nome_usuario ?>
                    </a></li>
                <li><a href="index.php"><i class="fa-solid fa-list-check"></i>Minhas Tarefas</a></li>
                <li><a href="../categoria/categoria.php"><i class="fa-solid fa-layer-group"></i>Cadastrar Categoria</a>
                </li>
                <li><a href="finalizadas.html"><i class="fa-notdog fa-solid fa-check"></i>Tarefas Finalizadas</a></li>

            </ul>
            <div class="divLogout">
                <ul class="listLogout">
                    <li><a class="buttonToggle"><i class="fa-solid fa-toggle-off"></i>Light Mode</a></li>
                    <li><a href="../backend/usuarios/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="tarefas-finalizadas">
                <h1 class="helloUser">Aqui estão todas as suas tarefas finalizadas</h1>
                <div class="content-tarefas">
                    <div class="tarefas-fin">
                        <div class="tarefas-familia">
                            <div class="title-tarefa"></div>
                        </div>
                        <div class="tarefas-trabalho">
                            <div class="title-tarefa"></div>
                        </div>
                        <div class="tarefas-lazer">
                            <div class="title-tarefa"></div>
                        </div>
                        <div class="tarefas-estudos">
                            <div class="title-tarefa"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="finalizadas.js"></script>
</body>

</html>