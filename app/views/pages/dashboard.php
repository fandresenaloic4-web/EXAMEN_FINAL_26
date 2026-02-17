<?php
$currentPage = 'dashboard';
$pageTitle = 'Dashboard';
include(__DIR__ . '/../layout/header.php');

$totalBesoins = $besoinStats['total_besoins'] ?? 0;
$totalDemande = $besoinStats['total_demande'] ?? 0;
$totalAttribue = $besoinStats['total_attribue'] ?? 0;
$totalRestant = $besoinStats['total_restant'] ?? 0;
$montantTotal = $besoinStats['montant_total'] ?? 0;
$montantCouvert = $besoinStats['montant_couvert'] ?? 0;

$totalDons = $donStats['total_dons'] ?? 0;
$totalQuantiteDon = $donStats['total_quantite'] ?? 0;
$totalDistribue = $donStats['total_distribue'] ?? 0;

$progressGlobal = $totalDemande > 0 ? round(($totalAttribue / $totalDemande) * 100) : 0;
$progressMontant = $montantTotal > 0 ? round(($montantCouvert / $montantTotal) * 100) : 0;
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-primary-gradient">
            <i class="bi bi-speedometer2"></i>
        </div>
        <div>
            <h1>Dashboard</h1>
            <p class="breadcrumb-text">Vue d'ensemble de la gestion des catastrophes</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo url('/distributions/dispatch'); ?>" class="btn btn-warning-custom" data-bs-toggle="tooltip" title="Distribuer automatiquement les dons aux besoins">
            <i class="bi bi-lightning-charge"></i> Dispatch automatique
        </a>

        <form method="POST" action="<?php echo url('/admin/reset'); ?>" onsubmit="return confirm('Confirmer la réinitialisation des données ? Toutes les distributions seront supprimées et les données seront remises à l\'état initial.');">
            <button type="submit" class="btn btn-danger-custom" data-bs-toggle="tooltip" title="Réinitialiser les données">
                <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser les données
            </button>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card border-primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Total Besoins</p>
                    <h3 class="stat-value count-up" data-target="<?php echo $totalBesoins; ?>"><?php echo $totalBesoins; ?></h3>
                </div>
                <div class="stat-icon bg-primary-soft">
                    <i class="bi bi-clipboard-check"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="progress-custom">
                    <div class="progress-bar progress-bar-animate bg-primary" data-width="<?php echo $progressGlobal; ?>"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="progress-label"><?php echo $totalAttribue; ?> attribués</span>
                    <span class="progress-label"><?php echo $progressGlobal; ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Total Dons</p>
                    <h3 class="stat-value count-up" data-target="<?php echo $totalDons; ?>"><?php echo $totalDons; ?></h3>
                </div>
                <div class="stat-icon bg-success-soft">
                    <i class="bi bi-gift"></i>
                </div>
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    <i class="bi bi-box-seam me-1"></i><?php echo number_format($totalQuantiteDon, 0, ',', ' '); ?> unités reçues
                </small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card border-warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Montant Total</p>
                    <h3 class="stat-value" style="font-size:1.4rem;">
                        <span class="count-up" data-target="<?php echo intval($montantTotal); ?>" data-suffix=" Ar"><?php echo number_format($montantTotal, 0, ',', ' '); ?> Ar</span>
                    </h3>
                </div>
                <div class="stat-icon bg-warning-soft">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="progress-custom">
                    <div class="progress-bar progress-bar-animate bg-warning" data-width="<?php echo $progressMontant; ?>"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="progress-label"><?php echo number_format($montantCouvert, 0, ',', ' '); ?> Ar couvert</span>
                    <span class="progress-label"><?php echo $progressMontant; ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card border-danger">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Quantité Restante</p>
                    <h3 class="stat-value count-up" data-target="<?php echo $totalRestant; ?>"><?php echo $totalRestant; ?></h3>
                </div>
                <div class="stat-icon bg-danger-soft">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    <i class="bi bi-arrow-down-circle me-1"></i>sur <?php echo number_format($totalDemande, 0, ',', ' '); ?> demandés
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Region Filter + Villes Cards -->
<div class="card-custom mb-4">
    <div class="card-header-custom">
        <h5><i class="bi bi-geo-alt-fill text-primary"></i> Situation par Ville</h5>
        <div class="d-flex align-items-center gap-3">
            <form method="GET" action="<?php echo url('/dashboard'); ?>" class="d-flex align-items-center gap-2" id="regionFilterForm">
                <label class="text-muted" style="font-size:0.85rem;white-space:nowrap;"><i class="bi bi-funnel me-1"></i>Filtrer par région :</label>
                <select name="region_id" class="form-select form-select-sm" style="width:200px;border:2px solid var(--gray-200);border-radius:var(--radius-sm);" onchange="this.form.submit()">
                    <option value="">Toutes les régions</option>
                    <?php foreach ($regions as $r): ?>
                        <option value="<?php echo $r['id']; ?>" <?php echo (isset($selectedRegion) && $selectedRegion == $r['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($r['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <span class="badge-custom badge-primary-soft"><?php echo count($villes); ?> ville(s)</span>
        </div>
    </div>
</div>

<?php if (empty($villes)): ?>
    <div class="empty-state">
        <i class="bi bi-geo-alt"></i>
        <h5>Aucune ville enregistrée</h5>
        <p>Commencez par ajouter des villes et des besoins</p>
        <a href="<?php echo url('/villes/create'); ?>" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Ajouter une ville
        </a>
    </div>
<?php else: ?>
    <?php foreach ($villes as $ville): ?>
    <div class="ville-card fade-in-up">
        <div class="ville-card-header">
            <h5>
                <i class="bi bi-geo-alt-fill me-1"></i>
                <?php echo htmlspecialchars($ville['nom']); ?>
            </h5>
            <span class="region-badge">
                <i class="bi bi-map me-1"></i><?php echo htmlspecialchars($ville['region']); ?>
            </span>
        </div>
        <div class="ville-card-body">
            <?php if (empty($ville['besoins'])): ?>
                <p class="text-muted text-center py-3 mb-0">
                    <i class="bi bi-inbox me-1"></i>Aucun besoin enregistré pour cette ville
                </p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Libellé</th>
                                <th>Prix Unit.</th>
                                <th>Demandé</th>
                                <th>Attribué</th>
                                <th>Restant</th>
                                <th>Montant</th>
                                <th style="width:200px">Progression</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ville['besoins'] as $besoin): 
                                $attribue = $besoin['quantite_demandee'] - $besoin['quantite_restante'];
                                $progress = $besoin['quantite_demandee'] > 0 ? round(($attribue / $besoin['quantite_demandee']) * 100) : 0;
                                $progressClass = $progress >= 100 ? 'bg-success' : ($progress >= 50 ? 'bg-info' : ($progress > 0 ? 'bg-warning' : 'bg-danger'));
                            ?>
                            <tr>
                                <td><span class="badge-custom badge-info-soft"><?php echo htmlspecialchars($besoin['categorie_nom']); ?></span></td>
                                <td><strong><?php echo htmlspecialchars($besoin['libelle']); ?></strong></td>
                                <td><?php echo number_format($besoin['prix_unitaire'], 0, ',', ' '); ?> Ar</td>
                                <td><?php echo $besoin['quantite_demandee']; ?></td>
                                <td>
                                    <span class="badge-custom badge-success-soft"><?php echo $attribue; ?></span>
                                </td>
                                <td>
                                    <?php if ($besoin['quantite_restante'] > 0): ?>
                                        <span class="badge-custom badge-danger-soft"><?php echo $besoin['quantite_restante']; ?></span>
                                    <?php else: ?>
                                        <span class="badge-custom badge-success-soft">0 ✓</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo number_format($besoin['montant_total'], 0, ',', ' '); ?> Ar</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress-custom flex-grow-1">
                                            <div class="progress-bar progress-bar-animate <?php echo $progressClass; ?>" data-width="<?php echo $progress; ?>"></div>
                                        </div>
                                        <span class="progress-label" style="min-width:38px;"><?php echo $progress; ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include(__DIR__ . '/../layout/footer.php'); ?>
