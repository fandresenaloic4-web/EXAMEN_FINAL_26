        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand-section">
                    <h3 class="footer-brand">BNGRC</h3>
                    <p class="text-muted" style="font-size:0.85rem;">Bureau National de Gestion des Risques et des Catastrophes</p>
                </div>

                <div class="footer-links">
                    <a href="<?php echo url('/about'); ?>" class="footer-link">
                        <i class="bi bi-info-circle me-1"></i>À propos
                    </a>
                    <a href="<?php echo url('/dashboard'); ?>" class="footer-link">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                    <a href="<?php echo url('/besoins'); ?>" class="footer-link">
                        <i class="bi bi-clipboard-check me-1"></i>Besoins
                    </a>
                    <a href="<?php echo url('/dons'); ?>" class="footer-link">
                        <i class="bi bi-gift me-1"></i>Dons
                    </a>
                    <a href="<?php echo url('/distributions'); ?>" class="footer-link">
                        <i class="bi bi-truck me-1"></i>Distributions
                    </a>
                    <a href="<?php echo url('/achats'); ?>" class="footer-link">
                        <i class="bi bi-cart me-1"></i>Achats
                    </a>
                    <a href="<?php echo url('/recap'); ?>" class="footer-link">
                        <i class="bi bi-clipboard-data me-1"></i>Récapitulation
                    </a>
                </div>

                <div class="footer-team">
                    <span class="team-badge"><i class="bi bi-person-badge me-1"></i>ETU004291</span>
                    <span class="team-badge"><i class="bi bi-person-badge me-1"></i>ETU004059</span>
                    <span class="team-badge"><i class="bi bi-person-badge me-1"></i>ETU004297</span>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="mb-0">
                    <i class="bi bi-c-circle me-1"></i>
                    2026 BNGRC. Tous droits réservés. | Fait avec <i class="bi bi-heart-fill text-danger"></i> par l'équipe BNGRC
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Pass BASE_URL to JS -->
    <script>window.BASE_URL = '<?php echo BASE_URL; ?>';</script>
    <!-- Custom JS -->
    <script src="<?php echo url('/assets/js/app.js'); ?>"></script>
</body>
</html>
