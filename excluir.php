<?php
include("../conexao.php");

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];

$sql = "DELETE FROM tarefas
        WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: listar.php?status=excluido");
    exit;
} else {
    header("Location: listar.php?status=error");
    exit;
}

?>
