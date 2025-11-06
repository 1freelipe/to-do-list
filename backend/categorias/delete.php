<?php
require_once '../config/db.php';

session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit();
}

if(empty($_POST['id_categoria'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'ID da categoria não fornecido.']);
    exit();
}

try {
    $id_categoria = $_POST['id_categoria'];

    $stmt = $pdo->prepare('DELETE FROM categorias WHERE id_categoria = :id');
    $stmt->bindParam(':id', $id_categoria);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Categoria excluída com sucesso.']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Categoria não encontrada ou não cadastrada.']);
    }
}catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao se conectar com o servidor.' . $e->getMessage()]);
}
?>