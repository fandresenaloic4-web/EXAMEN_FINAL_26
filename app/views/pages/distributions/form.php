<?php
$currentPage = 'distributions';
$pageTitle = 'Nouvelle distribution';
include(__DIR__ . '/../../layout/header.php');

$error = $_GET['error'] ?? null;
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-primary-gradient">
            <i class="bi bi-plus-lg"></i>
        </div>
        <div>
            <h1>Nouvelle Distribution</h1>
            <p class="breadcrumb-text">
                <a href="/distributions" class="text-decoration-none text-muted">Distributions</a>
                <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i>
                Créer
            </p>
        </div>
    </div>
</div>

<?php if ($error): ?>
<div class="alert alert-custom alert-danger alert-auto-dismiss mb-3">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <?php 
    switch ($error) {
        case 'don_insuffisant': echo "Quantité disponible du don insuffisante."; break;
        case 'besoin_depasse': echo "La quantité dépasse le besoin restant."; break;
        case 'categorie_differente': echo "Le don et le besoin doivent être de la même catégorie."; break;
        default: echo "Une erreur est survenue.";
    }
    ?>
</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>
                    <i class="bi bi-truck text-primary"></i>
                    Détails de la distribution
                </h5>
            </div>
            <div class="card-body">
                <form action="<?php echo $action; ?>" method="POST" class="form-custom needs-validation" novalidate>
                    <div class="row g-3">
                        <!-- Don Select -->
                        <div class="col-12">
                            <label for="don_id" class="form-label">
                                <i class="bi bi-gift"></i> Don
                            </label>
                            <select class="form-select" id="don_id" name="don_id" required>
                                <option value="">-- Sélectionner un don --</option>
                                <?php foreach ($dons as $d): 
                                    $disponible = $d['quantite_disponible'] ?? ($d['quantite'] - ($d['quantite_distribuee'] ?? 0));
                                    if ($disponible <= 0) continue;
                                ?>
                                    <option value="<?php echo $d['id']; ?>" 
                                            data-categorie-id="<?php echo $d['categorie_id']; ?>"
                                            data-disponible="<?php echo $disponible; ?>">
                                        <?php echo htmlspecialchars($d['nom']); ?> 
                                        (<?php echo htmlspecialchars($d['categorie_nom']); ?>) 
                                        - Disponible: <?php echo $disponible; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un don.</div>
                        </div>

                        <!-- Besoin Select (dynamic) -->
                        <div class="col-12">
                            <label for="besoin_id" class="form-label">
                                <i class="bi bi-clipboard-check"></i> Besoin
                            </label>
                            <select class="form-select" id="besoin_id" name="besoin_id" required disabled>
                                <option value="">-- Sélectionner d'abord un don --</option>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un besoin.</div>
                        </div>

                        <!-- Info Quantité Max -->
                        <div class="col-12">
                            <div id="maxQuantiteInfo" class="alert alert-custom alert-info" style="display:none;"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="quantite_attribuee" class="form-label">
                                <i class="bi bi-hash"></i> Quantité à distribuer
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                                <input type="number" class="form-control" id="quantite_attribuee" name="quantite_attribuee" 
                                       min="1" placeholder="0" required>
                            </div>
                            <div class="invalid-feedback">Veuillez saisir une quantité valide.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="date_distribution" class="form-label">
                                <i class="bi bi-calendar3"></i> Date de distribution
                            </label>
                            <input type="date" class="form-control" id="date_distribution" name="date_distribution" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                            <div class="invalid-feedback">Veuillez saisir une date.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="/distributions" class="btn btn-outline-custom">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-success-custom">
                            <i class="bi bi-check-lg"></i> Créer la distribution
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initDistributionForm();
});
</script>

<?php include(__DIR__ . '/../../layout/footer.php'); ?>
