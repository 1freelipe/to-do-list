<?php 
require_once '../config/db.php';

header('Content-Type: application.json');

$response = ['success' => false, 'message' => 'Ocorreu um erro desconhecido.'];

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
        $response['message'] = 'Por favor, preencha todos os campos.';
        echo json_encode($response);
        exit();
    };

    if($_POST['senha'] < 6) {
        $response['message'] = 'A senha precisa ter mais de 6 caracteres.';
        echo json_encode($response);
        exit();
    }

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = $_POST['senha'];
    $senha_hash = password_hash($password, PASSWORD_ARGON2ID);

    try {
        $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)');

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash);

        $stmt->execute();

        $response['success'] = true;
        $response['message'] = 'Cadastro realizado com sucesso';

    } catch(PDOException $e) {
        if($e->getCode() == 23000) {
            $response['message'] = 'Esse e-mail já está sendo utilizado. Tente outro.';
        } else {
            $response['message'] = 'Erro ao se conectar com o banco de dados. Tente novamente mais tarde.';
        }
    }
}

echo json_encode($response);
?>