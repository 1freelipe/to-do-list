<?php
require_once "../config/db.php";
session_start();

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit();
}

header('Content-Type: application/json');

try {
    $user_id = $_SESSION['user_id'];

    $stmt_cat = $pdo->prepare('SELECT id_categoria, nome FROM categorias ORDER BY id_categoria ASC');
    $stmt_cat->execute();
    $categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

    $stmt_tar = $pdo->prepare('SELECT id, descricao, id_categoria, status FROM tarefas WHERE status = "pendente"');
    $stmt_tar->execute();
    $tarefas = $stmt_tar->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'categorias' => $categorias, 'tarefas' => $tarefas]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor' . $e->getMessage()]);
}
?>