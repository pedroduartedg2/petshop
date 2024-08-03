<?php
include_once "header.html";
include_once "authGuard.php";
include_once "./config/conexao.php";
?>

<div class="flex">
    <nav class="bg-purple-900 w-72 h-[calc(100vh-71.75px)] flex flex-col justify-between text-purple-200">
        <div class="flex flex-col gap-6 px-4 py-8">
            <h1 class="font-bold text-lg">Dashboard</h1>
            <ul class="flex flex-col gap-2">
                <?php
                echo '                       
                <a href="#" id="menu-item-agendamentos" class="menu-item" onclick="loadContent(\'agendamentos.php\'); return false;">
                    <li class="flex flex-row gap-2 items-center w-full py-2 px-4 rounded-md bg-purple-950">
                        <i class="fa-solid fa-calendar" style="font-size: 1rem;"></i>
                        <p>Agendamentos</p>
                    </li>
                </a>';

                if (isset($_SESSION["user"])) {
                    if ($_SESSION["user"]["type"] === "cliente") {
                        echo '
                            <a href="#" id="menu-item-pets" class="menu-item" onclick="loadContent(\'meus_pets.php\'); return false;">
                                <li class="flex flex-row gap-2 items-center w-full py-2 px-4 rounded-md">
                                    <i class="fa-solid fa-dog" style="font-size: 1rem;"></i>
                                    <p>Meus pets</p>
                                </li>
                            </a>
                        ';
                    } else if ($_SESSION["user"]["type"] === "funcionario") {
                        echo '
                        <a href="#" id="menu-item-clientes" class="menu-item" onclick="loadContent(\'clientes.php\'); return false;">
                            <li class="flex flex-row gap-2 items-center w-full py-2 px-4 rounded-md">
                                <i class="fa-solid fa-user-group" style="font-size: 1rem;"></i>
                                <p>Clientes</p>
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
    <div id="conteudo" class="p-4 w-full">

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadContent('agendamentos.php');
    });

    let currentActive = null;

    function loadContent(page) {
        fetch(page)
            .then(response => response.text())
            .then(data => {
                document.getElementById('conteudo').innerHTML = data;

                // Remover classe bg-purple-950 de todos os itens do menu
                document.querySelectorAll('.menu-item li').forEach(item => {
                    item.classList.remove('bg-purple-950');
                });

                // Adicionar classe bg-purple-950 ao item clicado
                const clickedItem = document.querySelector(`a[onclick*="${page}"]`);
                console.log(page)
                console.log(clickedItem)
                if (clickedItem) {
                    clickedItem.querySelector('li').classList.add('bg-purple-950');
                    currentActive = clickedItem.querySelector('li');
                }
            })
            .catch(error => console.error('Error loading content:', error));
    }
</script>