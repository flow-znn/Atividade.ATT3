<?php
include("conexao.php");


if($_POST['acao'] == 'editar'){
$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

if(!empty($senha)) {
    $sql = "UPDATE usuarios
            SET nome = '$nome',
                email = '$email',
                senha = '$senha'
            WHERE id = $id";
} else {
    $sql ="UPDATE usuarios
            SET nome = '$nome',
                email = '$email'
            WHERE id = $id";
}

if($conn->query($sql) === TRUE) {
    header("Location: dashboard.php?status=atualizado");
    exit;
} else {
    header("Location: dashboard.php?status=error");
    exit;
}
}


?>