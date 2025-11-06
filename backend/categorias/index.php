<?php
require_once '../config/db.php';

session_start();

if(!isset($_SESSION['user_id'])){
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit();
}

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare('SELECT id_categoria, nome FROM categorias ORDER BY id_categoria ASC'); 
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['success' => true, 'categorias' => $categorias]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ainda não existem categorias cadastradas.']);
    }
}catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao se conectar com o servidor.' . $e->getMessage()]);
}

?>