<?php
require_once '../config/db.php';
session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit();
}

if(empty($_POST['id_tarefa'])){
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'ID da tarefa não fornecido.']);
    exit();
}

try {
    $id_tarefa = $_POST['id_tarefa'];

    $stmt = $pdo->prepare('DELETE FROM tarefas WHERE id = :id');
    $stmt->bindParam(':id', $id_tarefa);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Tarefa removida com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tarefa não encontrada']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao se conectar com o servidor' . $e->getMessage()]);
}

?>