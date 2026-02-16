<?php
$l = $_SESSION['l'] ?? null;
$roleText = $l === 'admin' ? 'Administrateur' : 'Utilisateur';
$roleClass = $l === 'admin' ? 'admin' : 'user';
$roleIcon = $l === 'admin' ? 'shield-check' : 'person-circle';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - BNGRC</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="login-page">
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-image">
            <div class="login-image-content">
                <h2 class="mb-4">
                    <i class="bi bi-heart-pulse" style="font-size: 4rem;"></i>
                </h2>
                <h2>Bienvenue sur BNGRC</h2>
                <p>Plateforme de suivi des dons et gestion des catastrophes</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-form-container">
            <div class="login-header">
                <h1>Connexion</h1>
                <p>Entrez vos informations pour continuer</p>
            </div>

            <?php if ($l): ?>
            <div class="role-badge <?php echo $roleClass; ?>">
                <i class="bi bi-<?php echo $roleIcon; ?>"></i>
                <span>Connexion en tant que <strong><?php echo $roleText; ?></strong></span>
            </div>
            <?php endif; ?>

            <form action="/home" method="GET" class="login-form">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                    <label for="nom"><i class="bi bi-person me-2"></i>Nom</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="email" class="form-control" id="email" name="email" placeholder="nom@exemple.com" required>
                    <label for="email"><i class="bi bi-envelope me-2"></i>Email</label>
                </div>

                <input type="hidden" name="roles" value="<?php echo htmlspecialchars($l ?? ''); ?>">

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Se connecter
                </button>

                <a href="/" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Retour à l'accueil
                </a>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
