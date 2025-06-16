</main>

<!-- Rodapé -->
<footer class="bg-dark text-white py-4 mt-2">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="fw-bold">CheckLista</h5>
                <p class="text-muted">Sua ferramenta para organização pessoal e produtividade.</p>
            </div>

            <div class="col-md-2 mb-4 mb-md-0">
                <h5 class="fw-bold">Links</h5>
                <ul class="list-unstyled">
                    <li><a href="dashboard.php" class="text-white text-decoration-none">Início</a></li>
                    <li><a href="listas.php" class="text-white text-decoration-none">Listas</a></li>
                    <li><a href="tarefas.php" class="text-white text-decoration-none">Tarefas</a></li>
                </ul>
            </div>

            <div class="col-md-2 mb-4 mb-md-0">
                <h5 class="fw-bold">Conta</h5>
                <ul class="list-unstyled">
                    <li><a href="perfil.php" class="text-white text-decoration-none">Perfil</a></li>
                    <li><a href="configuracoes.php" class="text-white text-decoration-none">Configurações</a></li>
                    <li><a href="logout.php" class="text-white text-decoration-none">Sair</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5 class="fw-bold">Contato</h5>
                <ul class="list-unstyled text-muted">
                    <li><i class="bi bi-envelope me-2"></i> contato@checklista.com</li>
                    <li><i class="bi bi-globe me-2"></i> www.checklista.com</li>
                </ul>
                <div class="mt-3">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>

        <hr class="my-4 bg-secondary">

        <div class="text-center text-muted">
            <small>&copy; <?php echo date('Y'); ?> CheckLista. Todos os direitos reservados.</small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
</body>
</html>