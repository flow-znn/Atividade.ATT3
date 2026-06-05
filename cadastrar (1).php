<?php
include("conexao.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "INSERT INTO usuarios(nome,email,senha)
        VALUES('$nome','$email','$senha')";

        if($conn->query($sql)=== TRUE) {
            header("Location: dashboard.php?status=sucesso");
            exit;
        } else {
            header("Location: dashboard.php?status=error");
            exit;
        }

?>