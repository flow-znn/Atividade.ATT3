<?php
session_start();

include("conexao.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios 
        WHERE email='$email' 
        AND senha='$senha'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $_SESSION['usuario'] = $email;
    header("Location: dashboard.php");
} else {
    echo "Email ou senha inválidos";
}
?>