<?php
$currentPage = 'distributions';
$pageTitle = 'Résultat du Dispatch';
include(__DIR__ . '/../../layout/header.php');

$totalDistribue = 0;
foreach ($log as $entry) {
    $totalDistribue += $entry['quantite'];
}
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-warning-gradient">
            <i class="bi bi-lightning-charge"></i>
        </div>
        <div>
            <h1>Résultat du Dispatch Automatique</h1>
            <p class="breadcrumb-text">
                <a href="/distributions" class="text-decoration-none text-muted">Distributions</a>
                <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i>
                Dispatch
            </p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button onclick="printDispatchResult()" class="btn btn-outline-custom">
            <i class="bi bi-printer"></i> Imprimer
        </button>
        <a href="/distributions" class="btn btn-primary-custom">
            <i class="bi bi-arrow-left"></i> Retour aux distributions
        </a>
    </div>
</div>

<?php if (empty($log)): ?>
    <!-- No distributions made -->
    <div class="card-custom">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-check-circle text-success" style="opacity:0.5;"></i>
                <h5>Aucune distribution effectuée</h5>
                <p>Tous les dons sont déjà distribués ou il n'y a pas de besoins correspondants.</p>
                <a href="/dashboard" class="btn btn-primary-custom">
                    <i class="bi bi-speedometer2"></i> Voir le dashboard
                </a>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card border-success">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label">Distributions créées</p>
                        <h3 class="stat-value count-up" data-target="<?php echo count($log); ?>"><?php echo count($log); ?></h3>
                    </div>
                    <div class="stat-icon bg-success-soft">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card border-info">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label">Quantité totale distribuée</p>
                        <h3 class="stat-value count-up" data-target="<?php echo $totalDistribue; ?>"><?php echo $totalDistribue; ?></h3>
                    </div>
                    <div class="stat-icon bg-info-soft">
                        <i class="bi bi-boxes"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card border-warning">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label">Villes desservies</p>
                        <h3 class="stat-value count-up" data-target="<?php echo count(array_unique(array_column($log, 'ville'))); ?>"><?php echo count(array_unique(array_column($log, 'ville'))); ?></h3>
                    </div>
                    <div class="stat-icon bg-warning-soft">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dispatch Details -->
    <div class="dispatch-card">
        <div class="card-header-custom" style="padding:1rem 1.25rem;border-bottom:1px solid var(--gray-200);">
            <h5 style="margin:0;font-weight:700;display:flex;align-items:center;gap:0.5rem;">
                <i class="bi bi-journal-check text-success"></i> Détails des distributions
            </h5>
        </div>
        <?php foreach ($log as $i => $entry): ?>
        <div class="dispatch-item fade-in-up">
            <div class="dispatch-icon">
                <i class="bi bi-arrow-right-circle"></i>
            </div>
            <div class="dispatch-info">
                <div>
                    <strong><?php echo htmlspecialchars($entry['don']); ?></strong>
                    <i class="bi bi-arrow-right mx-2 text-muted"></i>
                    <span><?php echo htmlspecialchars($entry['besoin']); ?></span>
                </div>
                <small>
                    <i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($entry['ville']); ?>
                </small>
            </div>
            <div class="dispatch-qty">
                ×<?php echo $entry['quantite']; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include(__DIR__ . '/../../layout/footer.php'); ?>
