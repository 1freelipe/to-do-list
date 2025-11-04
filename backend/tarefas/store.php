<?php
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['tarefa']) || empty($_POST['id_categoria'])) {
        echo json_encode(["success" => false, "message" => "Os campos: tarefa e categoria precisam ser preenchidos."]);
        exit;
    }

    $tarefa = $_POST['tarefa'];
    $id_categoria = $_POST['id_categoria'];
    
    try {
        $stmt = $pdo->prepare('INSERT INTO tarefas (descricao, id_categoria) VALUES(:descricao, :id_categoria)');
        $stmt->bindParam(':descricao', $tarefa);
        $stmt->bindParam(':id_categoria', $id_categoria);
        
        $stmt->execute();

        $newTaskId = $pdo->lastInsertId();
        echo json_encode(["success" => true, "message" => "Tarefa adicionada com sucesso.", "task" => ["id" => $newTaskId, "descricao" => $tarefa, "id_categoria" => $id_categoria]]);

    } catch(PDOException $e) {
        if($e->getCode() === '23000') {
            echo json_encode(["success" => false, "message" => "Essa tarefa já foi adicionada."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao se conectar com o servidor." . $e->getMessage()]);
        }
    }
}

?>