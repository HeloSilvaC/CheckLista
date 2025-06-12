</main>

<footer class="bg-dark text-white py-4 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">CheckLista</h5>
                <p class="text-white">Sistema simples e eficiente para criar, organizar e gerenciar anotações.</p>
                <div class="social-icons">
                    <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-github"></i></a>
                </div>
            </div>

            <div class="col-md-2 mb-3">
                <h5 class="fw-bold">Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= BASE_URL ?>" class="text-decoration-none text-white">Início</a></li>
                    <li><a href="<?= BASE_URL ?>paginas/notas/listar.php" class="text-decoration-none text-white">Notas</a></li>
                    <li><a href="<?= BASE_URL ?>paginas/notas/historico.php" class="text-decoration-none text-white">Histórico</a></li>
                    <li><a href="#" class="text-decoration-none text-white">Sobre</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-3">
                <h5 class="fw-bold">Ajuda</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-decoration-none text-white">FAQ</a></li>
                    <li><a href="#" class="text-decoration-none text-white">Contato</a></li>
                    <li><a href="#" class="text-decoration-none text-white">Privacidade</a></li>
                    <li><a href="#" class="text-decoration-none text-white">Termos</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-3">
                <h5 class="fw-bold">Desenvolvedores</h5>
                <p class="text-white">Projeto desenvolvido por Heloísa, Paola e Elisa.</p>
                <p class="text-white small">Versão <?= SISTEMA_VERSAO ?></p>
            </div>
        </div>

        <hr class="my-4 bg-secondary">

        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-white small">&copy; <?= date('Y') ?> CheckLista. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle com Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts personalizados -->
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>