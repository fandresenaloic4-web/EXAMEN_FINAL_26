<?php
$currentPage = 'recap';
$pageTitle = 'Récapitulation';
include(__DIR__ . '/../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-primary-gradient">
            <i class="bi bi-clipboard-data"></i>
        </div>
        <div>
            <h1>Récapitulation Générale</h1>
            <p class="breadcrumb-text">Vue d'ensemble de la couverture des besoins</p>
        </div>
    </div>
    <button type="button" class="btn btn-primary-custom" id="btnRefresh">
        <i class="bi bi-arrow-clockwise"></i> Actualiser (Ajax)
    </button>
</div>

<!-- Stats principales -->
<div class="row g-3 mb-4" id="statsCards">
    <div class="col-md-4">
        <div class="stat-card border-primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Montant total des besoins</p>
                    <h3 class="stat-value" id="statMontantTotal"><?php echo number_format($montantTotal, 0, ',', ' '); ?> Ar</h3>
                </div>
                <div class="stat-icon bg-primary-soft">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Montant satisfait</p>
                    <h3 class="stat-value" id="statMontantSatisfait"><?php echo number_format($montantSatisfait, 0, ',', ' '); ?> Ar</h3>
                </div>
                <div class="stat-icon bg-success-soft">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card border-danger">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Montant restant</p>
                    <h3 class="stat-value" id="statMontantRestant"><?php echo number_format($montantRestant, 0, ',', ' '); ?> Ar</h3>
                </div>
                <div class="stat-icon bg-danger-soft">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Barre de progression -->
<div class="card-custom mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-1"></i> Progression globale</h6>
            <span class="badge-custom badge-primary-soft fs-6" id="progressPourcentage"><?php echo $pourcentage; ?>%</span>
        </div>
        <div class="progress" style="height: 25px; border-radius: var(--radius-sm);">
            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                 role="progressbar" 
                 id="progressBar"
                 style="width: <?php echo $pourcentage; ?>%;" 
                 aria-valuenow="<?php echo $pourcentage; ?>" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                <?php echo $pourcentage; ?>%
            </div>
        </div>
    </div>
</div>

<!-- Détails -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card border-info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Total besoins</p>
                    <h3 class="stat-value" id="statTotalBesoins"><?php echo $totalBesoins; ?></h3>
                </div>
                <div class="stat-icon bg-info-soft">
                    <i class="bi bi-clipboard-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Total dons</p>
                    <h3 class="stat-value" id="statTotalDons"><?php echo $totalDons; ?></h3>
                </div>
                <div class="stat-icon bg-success-soft">
                    <i class="bi bi-gift"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Distributions</p>
                    <h3 class="stat-value" id="statTotalDistributions"><?php echo $totalDistributions; ?></h3>
                </div>
                <div class="stat-icon bg-warning-soft">
                    <i class="bi bi-truck"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Achats effectués</p>
                    <h3 class="stat-value" id="statTotalAchats"><?php echo $statsAchats['total_achats']; ?></h3>
                </div>
                <div class="stat-icon bg-info-soft">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Argent disponible -->
<div class="card-custom mb-4">
    <div class="card-header-custom">
        <h5><i class="bi bi-wallet2 text-success"></i> Situation financière</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-center p-3 bg-light rounded">
                    <small class="text-muted d-block">Montant achats TTC</small>
                    <strong class="fs-4 text-info" id="statMontantAchats"><?php echo number_format($statsAchats['total_montant'], 0, ',', ' '); ?> Ar</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 bg-light rounded">
                    <small class="text-muted d-block">Frais totaux</small>
                    <strong class="fs-4 text-warning" id="statFraisAchats"><?php echo number_format($statsAchats['total_frais'], 0, ',', ' '); ?> Ar</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 bg-light rounded border border-success">
                    <small class="text-muted d-block">Argent restant</small>
                    <strong class="fs-4 text-success" id="statArgentDisponible"><?php echo number_format($argentDisponible, 0, ',', ' '); ?> Ar</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnRefresh = document.getElementById('btnRefresh');
    
    function formatNumber(n) {
        return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    btnRefresh.addEventListener('click', function() {
        btnRefresh.disabled = true;
        btnRefresh.innerHTML = '<i class="bi bi-hourglass-split"></i> Chargement...';

        fetch(window.BASE_URL + '/api/recap')
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les stats principales
                document.getElementById('statMontantTotal').textContent = formatNumber(data.montantTotal) + ' Ar';
                document.getElementById('statMontantSatisfait').textContent = formatNumber(data.montantSatisfait) + ' Ar';
                document.getElementById('statMontantRestant').textContent = formatNumber(data.montantRestant) + ' Ar';
                
                // Barre de progression
                const bar = document.getElementById('progressBar');
                bar.style.width = data.pourcentage + '%';
                bar.textContent = data.pourcentage + '%';
                bar.setAttribute('aria-valuenow', data.pourcentage);
                document.getElementById('progressPourcentage').textContent = data.pourcentage + '%';

                // Détails
                document.getElementById('statTotalBesoins').textContent = data.totalBesoins;
                document.getElementById('statTotalDons').textContent = data.totalDons;
                document.getElementById('statTotalDistributions').textContent = data.totalDistributions;
                document.getElementById('statTotalAchats').textContent = data.statsAchats.total_achats;

                // Financier
                document.getElementById('statMontantAchats').textContent = formatNumber(data.statsAchats.total_montant) + ' Ar';
                document.getElementById('statFraisAchats').textContent = formatNumber(data.statsAchats.total_frais) + ' Ar';
                document.getElementById('statArgentDisponible').textContent = formatNumber(data.argentDisponible) + ' Ar';

                btnRefresh.disabled = false;
                btnRefresh.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualiser (Ajax)';
            })
            .catch(err => {
                console.error('Erreur:', err);
                btnRefresh.disabled = false;
                btnRefresh.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualiser (Ajax)';
                alert('Erreur lors du chargement des données.');
            });
    });
});
</script>

<?php include(__DIR__ . '/../layout/footer.php'); ?>
