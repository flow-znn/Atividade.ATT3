<?php
include("conexao.php");

$id = $_GET['id'];

$sql = "DELETE FROM usuarios
        WHERE id =$id";

if($conn->query($sql) === TRUE) {
    header("Location: dashboard.php?status=excluido");
    exit;
} else{
    header("Location: dashboard.php?status=error");
    exit;
}

?>