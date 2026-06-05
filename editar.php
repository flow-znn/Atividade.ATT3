<?php
include("../conexao.php");

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

if ($_POST) {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_tarefa = $_POST['data_tarefa'];
    $status = $_POST['status'];

    $sql = "UPDATE tarefas
            SET titulo = '$titulo',
                descricao = '$descricao',
                data_tarefa = '$data_tarefa',
                status = '$status'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: listar.php?status=atualizado");
        exit;
    } else {
        header("Location: listar.php?status=error");
        exit;
    }
}

$id = $_GET['id'];
$sql = "SELECT * FROM tarefas WHERE id = $id";
$result = mysqli_query($conn, $sql);
$tarefa = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Editar Tarefa</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
</head>

<body class="bg-[#f6faff] font-[Inter] text-[#141d23]">
    <main class="min-h-screen px-6 py-10">
        <div class="max-w-xl mx-auto bg-white rounded-2xl border border-[#c7c5d0]/40 shadow-sm overflow-hidden">
            <div class="bg-[#171a4a] p-6 text-white">
                <h1 class="text-2xl font-bold">Editar Tarefa</h1>
            </div>
            <form method="POST" action="editar.php" class="p-6 space-y-4">
                <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                <div>
                    <label class="block text-sm font-bold text-[#46464f] mb-1">Titulo</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-[#777680]" name="titulo" type="text" required value="<?php echo $tarefa['titulo']; ?>">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#46464f] mb-1">Descricao</label>
                    <textarea class="w-full px-4 py-2 rounded-lg border border-[#777680]" name="descricao" required><?php echo $tarefa['descricao']; ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#46464f] mb-1">Data da tarefa</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-[#777680]" name="data_tarefa" type="date" required value="<?php echo $tarefa['data_tarefa']; ?>">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#46464f] mb-1">Status</label>
                    <select class="w-full px-4 py-2 rounded-lg border border-[#777680]" name="status" required>
                        <option value="Pendente" <?php if ($tarefa['status'] == 'Pendente') echo 'selected'; ?>>Pendente</option>
                        <option value="Em andamento" <?php if ($tarefa['status'] == 'Em andamento') echo 'selected'; ?>>Em andamento</option>
                        <option value="Concluido" <?php if ($tarefa['status'] == 'Concluido') echo 'selected'; ?>>Concluido</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <a href="listar.php" class="flex-1 px-4 py-2.5 bg-[#e0e9f2] text-center font-bold rounded-lg">Cancelar</a>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-[#765b00] text-white font-bold rounded-lg">Salvar</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
