<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Takalo</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="accueil-page">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-arrow-left-right me-2"></i>
                TAKALO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">
                            <i class="bi bi-house-door me-1"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-box-seam me-1"></i>Produits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person-circle me-1"></i>Profil
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <h1 class="welcome-title animate-on-scroll fade-in-up">
                <i class="bi bi-emoji-smile text-primary"></i>
                Tongasoa!
            </h1>
            <p class="welcome-subtitle animate-on-scroll fade-in-up fade-in-delay-1">
                Bienvenue sur votre plateforme d'échange. Découvrez, échangez et partagez en toute simplicité.
            </p>
            
            <div class="row mt-5 g-4">
                <div class="col-md-4 animate-on-scroll fade-in-up fade-in-delay-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-lightning-charge text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Rapide et Sécurisé</h5>
                            <p class="card-text text-muted">Des transactions rapides avec une sécurité optimale</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 animate-on-scroll fade-in-up fade-in-delay-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Communauté Active</h5>
                            <p class="card-text text-muted">Rejoignez une communauté d'utilisateurs passionnés</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 animate-on-scroll fade-in-up">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">100% Fiable</h5>
                            <p class="card-text text-muted">Vos données et transactions sont protégées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include(__DIR__ . "/../footer/footer.php"); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/app.js"></script>
</body>
</html>