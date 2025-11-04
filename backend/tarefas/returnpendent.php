<?php
require_once '../config/db.php';

session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit();
}

if(empty($_POST['id_tarefa'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID da tarefa não fornecido.']);
}

$id_tarefa = $_POST['id_tarefa'];
$novo_status = 'pendente';

try {
    $stmt = $pdo->prepare('UPDATE tarefas SET status = :status WHERE id = :id');
    $stmt->bindParam(':status', $novo_status);
    $stmt->bindParam(':id', $id_tarefa);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Tarefa pendente novamente.']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID da tarefa não encontrado.']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao se conectar com o servidor: ' . $e->getMessage()]);
}


?>