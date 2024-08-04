<?php
include_once "header.html";
include_once "authGuard.php";
include_once "./config/conexao.php";
?>

<div class="flex overflow-hidden h-screen">
    <nav class="bg-purple-900 w-80  flex flex-col justify-between text-purple-200">
        <div class="flex flex-col gap-6 px-4 py-8">
            <img src="public/logo-white.png" width="70">
            <h1 class="font-bold text-lg">Dashboard</h1>
            <ul class="flex flex-col gap-2">
                <?php
                echo '                       
                <a href="#" id="menu-item-agendamentos" class="menu-item" onclick="loadContent(\'agendamentos.php\'); return false;">
                    <li class="flex flex-row gap-2 items-center w-full py-2 px-4 rounded-md">
                        <i class="fa-solid fa-calendar" style="font-size: 1rem;"></i>
                        <p>Agendamentos</p>
                    </li>
                </a>
                <a href="#" id="menu-item-pets" class="menu-item" onclick="loadContent(\'animais.php\'); return false;">
                    <li class="flex flex-row gap-2 items-center w-full py-2 px-4 rounded-md">
                        <i class="fa-solid fa-dog" style="font-size: 1rem;"></i>
                        <p>Animais</p>
                    </li>
                </a>
                ';

                if ($_SESSION["user"]["type"] === "funcionario") {
                    echo '
                        <a href="#" id="menu-item-clientes" class="menu-item" onclick="loadContent(\'clientes.php\'); return false;">
                            <li class="flex flex-row gap-2 items-center w-full py-2 px-4 rounded-md">
                                <i class="fa-solid fa-user-group" style="font-size: 1rem;"></i>
                                <p>Clientes</p>
                            </li>
                        </a>
                        <a href="#" id="menu-item-funcionarios" class="menu-item" onclick="loadContent(\'funcionarios.php\'); return false;">
                            <li class="flex flex-row gap-2 items-center w-full py-2 px-4 rounded-md">
                                <i class="fa-solid fa-user-tie" style="font-size: 1rem;"></i>
                                <p>Funcionários</p>
                            </li>
                        </a>
                        <a href="#" id="menu-item-servicos" class="menu-item" onclick="loadContent(\'servicos.php\'); return false;">
                            <li class="flex flex-row gap-2 items-center w-full py-2 px-4 rounded-md">
                                <i class="fa-solid fa-list" style="font-size: 1rem;"></i>
                                <p>Serviços</p>
                            </li>
                        </a>
                        ';
                }

                ?>
            </ul>
        </div>
        <div class="bg-purple-950 p-4 flex items-center justify-between">
            <div class="flex flex-row items-center gap-2">
                <i class="fa-solid fa-user bg-purple-700 p-2.5 rounded-full"></i>
                <?php
                if (isset($_SESSION["user"])) {
                    $nomeCompleto = $_SESSION["user"]["nome"];
                    $partesNome = explode(" ", $nomeCompleto);
                    $primeiroNome = ucfirst(strtolower($partesNome[0]));

                    echo "<p>Olá, <span class='font-semibold'>" . $primeiroNome . "</span></p>";
                } else {
                    echo "<p>Usuário não está logado.</p>";
                }
                ?>
            </div>
            <a href="logout.php" class="bg-purple-700 py-2 px-4 rounded-md">Sair</a>
        </div>
    </nav>
    <div id="conteudo" class="p-10 w-full overflow-auto">

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Obter a última página da sessão
        fetch('get_last_page.php')
            .then(response => response.json())
            .then(data => {
                const lastPage = data.last_page || 'agendamentos.php'; // Fallback para 'agendamentos.php' se não houver valor
                loadContent(lastPage);
            })
            .catch(error => console.error('Error fetching last page:', error));
    });

    let currentActive = null;

    function loadContent(page) {
        fetch(page)
            .then(response => response.text())
            .then(data => {
                document.getElementById('conteudo').innerHTML = data;

                // Atualize a sessão no PHP
                fetch('update_session.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'page': page
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            console.log('Sessão atualizada com sucesso');
                        } else {
                            console.log('Falha ao atualizar a sessão');
                        }
                    })
                    .catch(error => console.error('Error updating session:', error));

                // Remover classe bg-purple-950 de todos os itens do menu
                document.querySelectorAll('.menu-item li').forEach(item => {
                    item.classList.remove('bg-purple-950');
                });

                // Adicionar classe bg-purple-950 ao item clicado
                const clickedItem = document.querySelector(`a[onclick*="${page}"]`);
                if (clickedItem) {
                    clickedItem.querySelector('li').classList.add('bg-purple-950');
                    currentActive = clickedItem.querySelector('li');
                }
            })
            .catch(error => console.error('Error loading content:', error));
    }
</script>