<?php
include("conexao.php");

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
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

$sqlUsuarios = "SELECT * FROM usuarios";
$resultUsuarios = mysqli_query($conn, $sqlUsuarios);
$totalUsuarios = mysqli_num_rows($resultUsuarios);

$sqlTarefas = "SELECT * FROM tarefas";
$resultTarefas = mysqli_query($conn, $sqlTarefas);
$totalTarefas = mysqli_num_rows($resultTarefas);

$sqlPendentes = "SELECT * FROM tarefas WHERE status = 'Pendente'";
$resultPendentes = mysqli_query($conn, $sqlPendentes);
$totalPendentes = mysqli_num_rows($resultPendentes);

$sqlConcluidas = "SELECT * FROM tarefas WHERE status = 'Concluido'";
$resultConcluidas = mysqli_query($conn, $sqlConcluidas);
$totalConcluidas = mysqli_num_rows($resultConcluidas);

$sqlUltimasTarefas = "SELECT * FROM tarefas ORDER BY id DESC LIMIT 5";
$resultUltimasTarefas = mysqli_query($conn, $sqlUltimasTarefas);
?>
<!DOCTYPE html>
<html class="light" lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>AdminPro - Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "background": "#f6faff",
                        "on-primary": "#ffffff",
                        "primary-container": "#171a4a",
                        "secondary-container": "#ffd259",
                        "secondary": "#765b00",
                        "on-secondary": "#ffffff",
                        "surface": "#f6faff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#ecf5fe",
                        "surface-container": "#e6eff8",
                        "surface-container-high": "#e0e9f2",
                        "on-surface": "#141d23",
                        "on-surface-variant": "#46464f",
                        "on-background": "#141d23",
                        "primary": "#000032",
                        "outline": "#777680",
                        "outline-variant": "#c7c5d0",
                        "error": "#ba1a1a"
                    },
                    fontFamily: {
                        "body-md": ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
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

<body class="bg-background font-body-md text-on-background">

    <!-- Sidebar -->
    <aside class="fixed h-screen w-[280px] left-0 top-0 hidden lg:flex flex-col bg-primary-container shadow-lg border-r border-outline-variant/20 z-50">
        <div class="flex flex-col h-full py-6">
            <div class="px-6 mb-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-secondary-container rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1;">shield</span>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-on-primary">AdminPro</h1>
                    <p class="text-xs text-on-primary/60 uppercase tracking-widest">Enterprise Suite</p>
                </div>
            </div>
            <nav class="flex-1 space-y-1">
                <a class="flex items-center gap-3 px-6 py-3 text-on-primary bg-white/10 border-l-4 border-secondary-container transition-all" href="dashboard2.php">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-3 text-on-primary/60 hover:bg-white/5 hover:text-on-primary transition-all" href="dashboard.php">
                    <span class="material-symbols-outlined">group</span>
                    <span>Users</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-3 text-on-primary/60 hover:bg-white/5 hover:text-on-primary transition-all" href="tarefas/listar.php">
                    <span class="material-symbols-outlined">task_alt</span>
                    <span>Tarefas</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-3 text-on-primary/60 hover:bg-white/5 hover:text-on-primary transition-all" href="#">
                    <span class="material-symbols-outlined">settings</span>
                    <span>Settings</span>
                </a>
            </nav>
            <div class="px-4 mt-auto">
                <div class="bg-white/5 rounded-xl p-4 mb-4">
                    <p class="text-xs text-on-primary/60 mb-2">System Status</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-secondary shadow-[0_0_8px_#edc14a]"></span>
                        <span class="text-sm font-medium text-on-primary">All Systems Operational</span>
                    </div>
                </div>
                <a href="logout.php"
                    class="w-full flex items-center gap-3 px-4 py-3 text-on-primary/60 hover:bg-white/5 hover:text-on-primary transition-all rounded-lg">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- TopBar -->
    <header class="fixed top-0 right-0 w-full lg:w-[calc(100%-280px)] h-16 bg-surface border-b border-surface-container-high shadow-sm z-40">
        <div class="flex justify-between items-center px-6 lg:px-8 h-full">
            <div>
                <h2 class="text-lg font-black text-primary">Dashboard</h2>
                <p class="text-xs text-on-surface-variant">Resumo geral do sistema</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-primary">Admin User</p>
                    <p class="text-xs text-on-surface-variant">System Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-full border-2 border-secondary-container bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-xl" style="font-variation-settings:'FILL' 1;">person</span>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-24 pb-12 px-6 lg:px-10 ml-0 lg:ml-[280px] min-h-screen">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-primary">Painel Administrativo</h1>
            <p class="text-sm text-on-surface-variant">Acompanhe usuários, tarefas e atalhos principais da TechSolutions.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
            <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/20 shadow-sm flex items-center justify-between group">
                <div>
                    <p class="text-sm font-medium text-on-surface-variant mb-1">Usuarios</p>
                    <h3 class="text-4xl font-black text-primary"><?php echo $totalUsuarios; ?></h3>
                    <p class="text-sm text-secondary font-bold mt-2">Registros cadastrados</p>
                </div>
                <div class="bg-surface-container p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings:'FILL' 1;">group</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/20 shadow-sm flex items-center justify-between group">
                <div>
                    <p class="text-sm font-medium text-on-surface-variant mb-1">Tarefas</p>
                    <h3 class="text-4xl font-black text-primary"><?php echo $totalTarefas; ?></h3>
                    <p class="text-sm text-secondary font-bold mt-2">Agendamentos criados</p>
                </div>
                <div class="bg-surface-container p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings:'FILL' 1;">task_alt</span>
                </div>
            </div>

            <div class="bg-secondary-container p-6 rounded-xl border border-secondary-container shadow-sm flex items-center justify-between group">
                <div>
                    <p class="text-sm font-medium text-secondary mb-1">Pendentes</p>
                    <h3 class="text-4xl font-black text-primary"><?php echo $totalPendentes; ?></h3>
                    <p class="text-sm text-secondary font-bold mt-2">Precisam de atenção</p>
                </div>
                <div class="bg-white/40 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-secondary text-3xl">schedule</span>
                </div>
            </div>

            <div class="bg-primary-container p-6 rounded-xl border border-primary shadow-lg flex items-center justify-between group">
                <div class="text-on-primary">
                    <p class="text-sm font-medium text-on-primary/60 mb-1">Concluidas</p>
                    <h3 class="text-4xl font-black"><?php echo $totalConcluidas; ?></h3>
                    <p class="text-xs mt-2 opacity-80">Tarefas finalizadas</p>
                </div>
                <div class="bg-white/10 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-on-primary text-3xl">verified</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-1 bg-surface-container-lowest rounded-2xl border border-outline-variant/20 shadow-sm p-6">
                <h2 class="text-xl font-bold text-primary mb-4">Acoes Rapidas</h2>
                <div class="space-y-3">
                    <button onclick="abrirModal('modalNovoUsuario')" class="w-full flex items-center justify-between gap-3 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold px-5 py-3 rounded-lg shadow-md active:scale-95 transition-all">
                        <span>Novo Usuario</span>
                        <span class="material-symbols-outlined">person_add</span>
                    </button>
                    <button onclick="abrirModal('modalNovaTarefa')" class="w-full flex items-center justify-between gap-3 bg-primary-container hover:bg-primary-container/90 text-on-primary font-bold px-5 py-3 rounded-lg shadow-md active:scale-95 transition-all">
                        <span>Nova Tarefa</span>
                        <span class="material-symbols-outlined">add_task</span>
                    </button>
                    <a href="dashboard.php" class="w-full flex items-center justify-between gap-3 bg-surface-container hover:bg-surface-container-high text-primary font-bold px-5 py-3 rounded-lg transition-all">
                        <span>Gerenciar Usuarios</span>
                        <span class="material-symbols-outlined">group</span>
                    </a>
                    <a href="tarefas/listar.php" class="w-full flex items-center justify-between gap-3 bg-surface-container hover:bg-surface-container-high text-primary font-bold px-5 py-3 rounded-lg transition-all">
                        <span>Gerenciar Tarefas</span>
                        <span class="material-symbols-outlined">task_alt</span>
                    </a>
                </div>
            </div>

            <div class="xl:col-span-2 bg-surface-container-lowest rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-outline-variant/10">
                    <h2 class="text-xl font-bold text-primary">Ultimas Tarefas</h2>
                    <p class="text-sm text-on-surface-variant">Agendamentos cadastrados recentemente.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Titulo</th>
                                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Data</th>
                                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            <?php while ($row = mysqli_fetch_assoc($resultUltimasTarefas)) { ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm font-bold text-primary"><?php echo $row['titulo']; ?></td>
                                    <td class="px-6 py-4 text-sm text-on-surface-variant"><?php echo $row['data_tarefa']; ?></td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full bg-surface-container text-primary font-bold">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- ===== MODAL NOVO USUARIO ===== -->
    <div class="modal-overlay fixed inset-0 z-[100] items-center justify-center p-4" id="modalNovoUsuario">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-outline-variant/30">
            <div class="bg-primary-container p-6 text-on-primary flex justify-between items-center">
                <h3 class="text-xl font-bold">Adicionar Usuario</h3>
                <button onclick="fecharModal('modalNovoUsuario')" class="hover:bg-white/10 p-1 rounded-full transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="cadastrar.php" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Nome Completo</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-outline focus:ring-2 focus:ring-secondary-container focus:border-primary outline-none transition-all"
                        name="nome" type="text" required />
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">E-mail</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-outline focus:ring-2 focus:ring-secondary-container focus:border-primary outline-none transition-all"
                        name="email" type="email" required />
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Senha</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-outline focus:ring-2 focus:ring-secondary-container focus:border-primary outline-none transition-all"
                        name="senha" type="password" required />
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="fecharModal('modalNovoUsuario')"
                        class="flex-1 px-4 py-2.5 bg-surface-container-high text-on-surface font-bold rounded-lg hover:bg-surface-container transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-secondary text-on-secondary font-bold rounded-lg shadow-md hover:opacity-90 active:scale-[0.98] transition-all">
                        Salvar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== MODAL NOVA TAREFA ===== -->
    <div class="modal-overlay fixed inset-0 z-[100] items-center justify-center p-4" id="modalNovaTarefa">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-outline-variant/30">
            <div class="bg-primary-container p-6 text-on-primary flex justify-between items-center">
                <h3 class="text-xl font-bold">Adicionar Tarefa</h3>
                <button onclick="fecharModal('modalNovaTarefa')" class="hover:bg-white/10 p-1 rounded-full transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="tarefas/cadastrar.php" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Titulo</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-outline focus:ring-2 focus:ring-secondary-container focus:border-primary outline-none transition-all"
                        name="titulo" type="text" required />
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Descricao</label>
                    <textarea class="w-full px-4 py-2 rounded-lg border border-outline focus:ring-2 focus:ring-secondary-container focus:border-primary outline-none transition-all"
                        name="descricao" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Data da tarefa</label>
                    <input class="w-full px-4 py-2 rounded-lg border border-outline focus:ring-2 focus:ring-secondary-container focus:border-primary outline-none transition-all"
                        name="data_tarefa" type="date" required />
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Status</label>
                    <select class="w-full px-4 py-2 rounded-lg border border-outline focus:ring-2 focus:ring-secondary-container focus:border-primary outline-none transition-all"
                        name="status" required>
                        <option value="Pendente">Pendente</option>
                        <option value="Em andamento">Em andamento</option>
                        <option value="Concluido">Concluido</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="fecharModal('modalNovaTarefa')"
                        class="flex-1 px-4 py-2.5 bg-surface-container-high text-on-surface font-bold rounded-lg hover:bg-surface-container transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-secondary text-on-secondary font-bold rounded-lg shadow-md hover:opacity-90 active:scale-[0.98] transition-all">
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
    </script>
</body>

</html>
