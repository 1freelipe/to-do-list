<?php
require_once '../config/db.php';

session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit();
}

if(empty($_POST['id_categoria'])){
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID da categoria não encontrado ou não fornecido.']);
    exit();
}

$id_categoria = $_POST['id_categoria'];
$nome = $_POST['categoria'];

try {
    $stmt = $pdo->prepare('UPDATE categorias SET nome = :nome WHERE id_categoria = :id');
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id_categoria);

    $stmt->execute();

    if($stmt->rowCount() > 0){
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Categoria alterada com sucesso.']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Categoria não encontrada.']);
    }
}catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao se conectar com o servidor: ' . $e->getMessage()]);
}

?>