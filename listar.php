<?php
include("../conexao.php");

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

$sqlCriarTabela = "CREATE TABLE IF NOT EXISTS tarefas (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(100),
descricao TEXT,
data_tarefa DATE,
status VARCHAR(50)
)";

mysqli_query($conn, $sqlCriarTabela);

$sql = "SELECT * FROM tarefas ORDER BY data_tarefa ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html class="light" lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tarefas</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        .task-row:hover {
            background-color: rgba(230, 239, 248, 0.5);
        }

        .modal-overlay {
            display: none;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-overlay.active {
            display: flex;
        }
    </style>
</head>

<body class="bg-[#f6faff] font-[Inter] text-[#141d23]">

    <!-- Sidebar -->
    <aside class="fixed h-screen w-[280px] left-0 top-0 hidden lg:flex flex-col bg-[#171a4a] shadow-lg border-r border-[#c7c5d0]/20 z-50">
        <div class="flex flex-col h-full py-6">
            <div class="px-6 mb-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-[#ffd259] rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#745a00]" style="font-variation-settings:'FILL' 1;">shield</span>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-white">AdminPro</h1>
                    <p class="text-xs text-white/60 uppercase tracking-widest">Enterprise Suite</p>
                </div>
            </div>
            <nav class="flex-1 space-y-1">
                <a class="flex items-center gap-3 px-6 py-3 text-white/60 hover:bg-white/5 hover:text-white transition-all" href="../dashboard2.php">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-3 text-white/60 hover:bg-white/5 hover:text-white transition-all" href="../dashboard.php">
                    <span class="material-symbols-outlined">group</span>
                    <span>Users</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-3 text-white bg-white/10 border-l-4 border-[#ffd259] transition-all" href="listar.php">
                    <span class="material-symbols-outlined">task_alt</span>
                    <span>Tarefas</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-3 text-white/60 hover:bg-white/5 hover:text-white transition-all" href="#">
                    <span class="material-symbols-outlined">settings</span>
                    <span>Settings</span>
                </a>
            </nav>
            <div class="px-4 mt-auto">
                <div class="bg-white/5 rounded-xl p-4 mb-4">
                    <p class="text-xs text-white/60 mb-2">System Status</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#765b00] shadow-[0_0_8px_#edc14a]"></span>
                        <span class="text-sm font-medium text-white">All Systems Operational</span>
                    </div>
                </div>
                <a href="../logout.php"
                    class="w-full flex items-center gap-3 px-4 py-3 text-white/60 hover:bg-white/5 hover:text-white transition-all rounded-lg">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- TopBar -->
    <header class="fixed top-0 right-0 w-full lg:w-[calc(100%-280px)] h-16 bg-[#f6faff] border-b border-[#dbe4ed] shadow-sm z-40">
        <div class="flex justify-between items-center px-6 lg:px-8 h-full">
            <div class="flex items-center gap-4 flex-1">
                <div class="relative w-full max-w-md group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#46464f]">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-[#ecf5fe] border border-[#c7c5d0]/30 rounded-lg focus:ring-2 focus:ring-[#ffd259] outline-none transition-all text-sm"
                        id="taskSearch" placeholder="Buscar tarefas..." type="text" />
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4 border-r border-[#c7c5d0]/30 pr-6">
                    <button class="relative p-2 text-[#46464f] hover:bg-[#e0e9f2] rounded-full transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-[#ba1a1a] rounded-full"></span>
                    </button>
                    <button class="p-2 text-[#46464f] hover:bg-[#e0e9f2] rounded-full transition-colors">
                        <span class="material-symbols-outlined">help</span>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-[#000032]">Admin User</p>
                        <p class="text-xs text-[#46464f]">System Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full border-2 border-[#ffd259] bg-[#171a4a] flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xl" style="font-variation-settings:'FILL' 1;">person</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-24 pb-12 px-6 lg:px-10 ml-0 lg:ml-[280px] min-h-screen">
        <div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-[#000032]">Gerenciamento de Tarefas</h1>
                    <p class="text-sm text-[#46464f]">Visualize e gerencie os agendamentos internos da empresa.</p>
                </div>
                <button onclick="abrirModalNovo()" class="flex items-center gap-2 bg-[#765b00] hover:bg-[#765b00]/90 text-white font-bold px-6 py-2.5 rounded-lg shadow-md active:scale-95 transition-all">
                    <span class="material-symbols-outlined">add</span>
                    Nova Tarefa
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-[#c7c5d0]/20 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-[#ecf5fe]">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-[#46464f] uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-xs font-bold text-[#46464f] uppercase tracking-wider">Titulo</th>
                                <th class="px-6 py-4 text-xs font-bold text-[#46464f] uppercase tracking-wider">Descricao</th>
                                <th class="px-6 py-4 text-xs font-bold text-[#46464f] uppercase tracking-wider">Data</th>
                                <th class="px-6 py-4 text-xs font-bold text-[#46464f] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-[#46464f] uppercase tracking-wider">Acoes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#c7c5d0]/10">
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr class="task-row transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-[#46464f]">#<?php echo $row['id']; ?></td>
                                    <td class="px-6 py-4 text-sm font-bold text-[#000032]"><?php echo $row['titulo']; ?></td>
                                    <td class="px-6 py-4 text-sm text-[#46464f]"><?php echo $row['descricao']; ?></td>
                                    <td class="px-6 py-4 text-sm text-[#46464f]"><?php echo $row['data_tarefa']; ?></td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full bg-[#e6eff8] text-[#000032] font-bold">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="editar.php?id=<?php echo $row['id']; ?>" class="p-2 text-[#000032] hover:bg-[#e6eff8] rounded-lg transition-colors">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <a href="excluir.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Deseja excluir esta tarefa?')" class="p-2 text-[#ba1a1a] hover:bg-[#ffdad6]/20 rounded-lg transition-colors">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- ===== MODAL NOVA TAREFA ===== -->
    <div class="modal-overlay fixed inset-0 z-[100] items-center justify-center p-4" id="modalNovaTarefa">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-[#c7c5d0]/30">
            <div class="bg-[#171a4a] p-6 text-white flex justify-between items-center">
                <h3 class="text-xl font-bold">Adicionar Tarefa</h3>
                <button onclick="fecharModal('modalNovaTarefa')" class="hover:bg-white/10 p-1 rounded-full transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="cadastrar.php" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-[#46464f] mb-1">Titulo</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-[#777680] focus:ring-2 focus:ring-[#ffd259] focus:border-[#000032] outline-none transition-all"
                        name="titulo" type="text" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#46464f] mb-1">Descricao</label>
                    <textarea class="w-full px-4 py-2 rounded-lg border border-[#777680] focus:ring-2 focus:ring-[#ffd259] focus:border-[#000032] outline-none transition-all"
                        name="descricao" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#46464f] mb-1">Data da tarefa</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-[#777680] focus:ring-2 focus:ring-[#ffd259] focus:border-[#000032] outline-none transition-all"
                        name="data_tarefa" type="date" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#46464f] mb-1">Status</label>
                    <select class="w-full px-4 py-2 rounded-lg border border-[#777680] focus:ring-2 focus:ring-[#ffd259] focus:border-[#000032] outline-none transition-all"
                        name="status" required>
                        <option value="Pendente">Pendente</option>
                        <option value="Em andamento">Em andamento</option>
                        <option value="Concluido">Concluido</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="fecharModal('modalNovaTarefa')"
                        class="flex-1 px-4 py-2.5 bg-[#e0e9f2] text-[#141d23] font-bold rounded-lg hover:bg-[#dbe4ed] transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-[#765b00] text-white font-bold rounded-lg shadow-md hover:opacity-90 active:scale-[0.98] transition-all">
                        Salvar Tarefa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function fecharModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    fecharModal(this.id);
                }
            });
        });

        function abrirModalNovo() {
            abrirModal('modalNovaTarefa');
        }

        document.getElementById('taskSearch').addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            const linhas = document.querySelectorAll('tbody tr');

            linhas.forEach(function(linha) {
                const exibir = linha.innerText.toLowerCase().includes(termo);
                linha.style.display = exibir ? '' : 'none';
            });
        });
    </script>
</body>

</html>
