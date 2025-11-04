<?php
require_once '../config/db.php';

session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit();
}

try {
    $stmt = $pdo->prepare('SELECT id, descricao, id_categoria, status FROM tarefas WHERE status = "concluido"');
    $stmt->execute();
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        http_response_code(200);
        echo json_encode(['success' => true, 'tarefas' => $tarefas]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ainda não já tarefas concluídas.']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao se conectar com o servidor: ' . $e->getMessage()]);
}

?>