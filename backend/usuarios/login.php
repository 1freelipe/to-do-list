<?php
require_once '../config/db.php';

session_start();

header('Content-type: application/json');
$response = ['success' => false, 'message' => 'Método de requisição inválido.'];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(empty($_POST['email']) || empty($_POST['senha'])) {
        $response['message'] = 'Os campos e-mail e senha precisam ser preenchidos.';
        echo json_encode($response);
        exit();
    }

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        $stmt = $pdo->prepare('SELECT id, nome, senha FROM usuarios WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $nomeCompleto = $usuario['nome'];
            $partesNome = explode(' ', trim($nomeCompleto));
            $primeiroNome = current($partesNome);
            $ultimoNome = end($partesNome);

            $_SESSION['username'] = $primeiroNome . ' ' . $ultimoNome;

            $response['success'] = true;
            $response['redirect'] = '../index/index.php';
        } else {
            $response['message'] = 'Email ou senha incorretos.';
        }
    } catch(PDOException $e) {
        $response['message'] = 'Erro no servidor. Tente novamente mais tarde';
        error_log($e->getMessage());
    }
}

echo json_encode($response);

?>