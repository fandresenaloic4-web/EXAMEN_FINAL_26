<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takalo - Plateforme d'Échange</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-logo fade-in">TAKALO</h1>
            <p class="hero-subtitle text-white-50 fade-in-delay-1">Plateforme moderne d'échange et de commerce</p>
            
            <div class="role-cards">
                <!-- User Card -->
                <a href="/index?l=user" class="role-card user">
                    <div class="role-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h3 class="role-title">Utilisateur</h3>
                    <p class="role-description">Accédez à la plateforme en tant qu'utilisateur standard</p>
                </a>
                
                <!-- Admin Card -->
                <a href="/index?l=admin" class="role-card admin">
                    <div class="role-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3 class="role-title">Administrateur</h3>
                    <p class="role-description">Gérez la plateforme avec des privilèges admin</p>
                </a>
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