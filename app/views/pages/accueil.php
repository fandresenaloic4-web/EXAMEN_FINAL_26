<?php
$currentPage = 'home';
$pageTitle = 'Accueil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - BNGRC</title>
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
    <nav class="navbar navbar-expand-lg navbar-bngrc">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <span class="brand-icon">
                    <i class="bi bi-heart-pulse"></i>
                </span>
                BNGRC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/home">
                            <i class="bi bi-house-door"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/besoins">
                            <i class="bi bi-clipboard-check"></i> Besoins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dons">
                            <i class="bi bi-gift"></i> Dons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/distributions">
                            <i class="bi bi-truck"></i> Distributions
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link" href="/logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <h1 class="welcome-title fade-in-up">
                <i class="bi bi-heart-pulse text-danger"></i>
                Tongasoa!
            </h1>
            <p class="welcome-subtitle fade-in-up" style="animation-delay:0.2s;">
                Bienvenue sur la plateforme BNGRC. Gérez les besoins, suivez les dons et coordonnez les distributions efficacement.
            </p>
            
            <div class="row mt-5 g-4">
                <!-- Card 1: Besoins -->
                <div class="col-lg-3 col-md-6">
                    <a href="/besoins" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 card-custom" style="transition:var(--transition);">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="stat-icon bg-primary-soft mx-auto" style="width:64px;height:64px;border-radius:16px;font-size:1.8rem;">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                </div>
                                <h5 class="card-title" style="color:var(--dark);font-weight:700;">Suivi des Besoins</h5>
                                <p class="card-text text-muted" style="font-size:0.9rem;">Identifiez et suivez les besoins des zones sinistrées</p>
                                <span class="btn btn-primary-custom btn-sm mt-2">
                                    Accéder <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Card 2: Dons -->
                <div class="col-lg-3 col-md-6">
                    <a href="/dons" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 card-custom" style="transition:var(--transition);">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="stat-icon bg-success-soft mx-auto" style="width:64px;height:64px;border-radius:16px;font-size:1.8rem;">
                                        <i class="bi bi-gift"></i>
                                    </div>
                                </div>
                                <h5 class="card-title" style="color:var(--dark);font-weight:700;">Gestion des Dons</h5>
                                <p class="card-text text-muted" style="font-size:0.9rem;">Collectez et gérez les dons pour les populations</p>
                                <span class="btn btn-success-custom btn-sm mt-2">
                                    Accéder <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Card 3: Distributions -->
                <div class="col-lg-3 col-md-6">
                    <a href="/distributions" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 card-custom" style="transition:var(--transition);">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="stat-icon bg-warning-soft mx-auto" style="width:64px;height:64px;border-radius:16px;font-size:1.8rem;">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                </div>
                                <h5 class="card-title" style="color:var(--dark);font-weight:700;">Distributions</h5>
                                <p class="card-text text-muted" style="font-size:0.9rem;">Coordonnez la distribution des aides aux villes</p>
                                <span class="btn btn-warning-custom btn-sm mt-2">
                                    Accéder <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card 4: Dashboard -->
                <div class="col-lg-3 col-md-6">
                    <a href="/dashboard" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 card-custom" style="transition:var(--transition);">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div class="stat-icon bg-danger-soft mx-auto" style="width:64px;height:64px;border-radius:16px;font-size:1.8rem;">
                                        <i class="bi bi-speedometer2"></i>
                                    </div>
                                </div>
                                <h5 class="card-title" style="color:var(--dark);font-weight:700;">Dashboard</h5>
                                <p class="card-text text-muted" style="font-size:0.9rem;">Vue d'ensemble et statistiques en temps réel</p>
                                <span class="btn btn-danger-custom btn-sm mt-2">
                                    Accéder <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" style="margin-top:4rem;">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand-section">
                    <h3 class="footer-brand">BNGRC</h3>
                    <p class="text-muted" style="font-size:0.85rem;">Bureau National de Gestion des Risques et des Catastrophes</p>
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
    <script src="/assets/js/app.js"></script>
</body>
</html>
