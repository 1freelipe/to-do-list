<?php
require_once '../config/db.php';

session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['succcess' => false, 'message' => 'Usuário não logado.']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($_POST['categoria'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'O campo categoria precisa ser preenchido.']);
        exit();
    }

    $nome_categoria = $_POST['categoria'];

    try {
        $stmt = $pdo->prepare('INSERT INTO categorias (nome) VALUES (:nome)');
        $stmt->bindParam(':nome', $nome_categoria);
        $stmt->execute();
        
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Categoria cadastrada com sucesso.']);
    } catch(PDOException $e) {
        if($e->getCode() === '23000') {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Essa categoria já foi cadastrada. Por favor, escolha outra.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao se conectar com o servidor.' . $e->getMessage()]);
        }
    }
}
?>