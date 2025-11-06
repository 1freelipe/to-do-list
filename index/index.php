<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/index.html');
    exit();
}

$nome_usuario = htmlspecialchars($_SESSION['username']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
    <title>Lista de Tarefas</title>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="sideTitle">
                <h1 class="todo">TO-DO-LIST</h1>
            </div>
            <ul>
                <li class="user"><a href="#"><i class="fa-solid fa-circle-user"></i>Olá, <?php echo $nome_usuario ?></a></li>
                <li><a href="index.php"><i class="fa-solid fa-list-check"></i>Minhas Tarefas</a></li>
                <li><a href="../categoria/categoria.php"><i class="fa-solid fa-layer-group"></i>Cadastrar Categoria</a></li>
                <li><a href="finalizadas.php"><i class="fa-notdog fa-solid fa-check"></i>Tarefas Finalizadas</a></li>

            </ul>
            <div class="divLogout">
                <ul class="listLogout">
                    <li><a class="buttonToggle"><i class="fa-solid fa-toggle-off"></i>Light Mode</a></li>
                    <li><a href="../backend/usuarios/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Sair</a></li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="divTitle">
                <h1 class="helloUser">O que você precisa fazer hoje?</h1>
            </div>

            <div class="divInputTarefas">
                <form action="../backend/tarefas/store.php" class="form-tarefas" method="POST">
                    <input type="hidden" id="input_tarefa_editando" value="" name="id_tarefa">
                    <div class="divContent">
                        <input type="text" name="tarefa" placeholder="Adicionar Tarefa" class="input-tarefas">
                        <select name="id_categoria" id="" class="select-categoria">
                            <option value="">Carregando categorias...</option>
                        </select>
                        <img src="../img/photostyle.png" alt="" class="photostyle">
                    </div>

                    <div class="divButton">
                        <button type="submit" class="BtnSubmit">Adicionar</button>
                    </div>
                    <div class="messages"></div>
                </form>
            </div>

            <div class="divTarefas">
                <div class="divTarefasTop">
                    <div class="divTarefasF">
                        <div class="title-tarefa">
                        </div>
                        <div class="lista-de-tarefas"></div>
                    </div>
                    <div class="divTarefasT">
                        <div class="title-tarefa">
                        </div>
                        <div class="lista-de-tarefas"></div>
                    </div>
                </div>
                <div class="divTarefasBottom">
                    <div class="divTarefasL">
                        <div class="title-tarefa"></div>
                        <div class="lista-de-tarefas"></div>
                    </div>
                    <div class="divTarefasE">
                        <div class="title-tarefa">
                        </div>
                        <div class="lista-de-tarefas"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="tarefas.js"></script>
</body>

</html>