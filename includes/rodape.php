</main>

<footer class="bg-dark text-white py-4" style="margin-top: 100px;">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="fw-bold">CheckLista</h5>
                <p class="text-white-50">Sua ferramenta para organização pessoal e produtividade.</p>
            </div>

            <div class="col-md-2 mb-4 mb-md-0">
                <h5 class="fw-bold">Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>paginas/home.php" class="text-white text-decoration-none">Início</a></li>
                    <li><a href="<?php echo BASE_URL; ?>paginas/checklist/listar.php" class="text-white text-decoration-none">Listas</a>
                    </li>
                    <li><a href="<?php echo BASE_URL; ?>paginas/tarefas/listar.php"
                           class="text-white text-decoration-none">Tarefas</a></li>
                </ul>
            </div>

            <div class="col-md-2 mb-4 mb-md-0">
                <h5 class="fw-bold">Conta</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>paginas/perfil/visualizar.php" class="text-white text-decoration-none">Perfil</a></li>
                    <li><a href="<?php echo BASE_URL; ?>paginas/autenticacao/logout.php" class="text-white text-decoration-none">Sair</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5 class="fw-bold">Contato</h5>
                <p class="text-white-50">Siga-nos nas redes sociais.</p>
                <div class="mt-3">
                    <a href="#" class="text-white me-3 fs-4"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3 fs-4"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white me-3 fs-4"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>

        <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.2);">

        <div class="text-center text-white-50">
            <small>&copy; <?php echo date('Y'); ?> CheckLista. Todos os direitos reservados.</small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>