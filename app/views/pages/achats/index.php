<?php
$currentPage = 'achats';
$pageTitle = 'Achats';
include(__DIR__ . '/../../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-info-gradient">
            <i class="bi bi-cart-check"></i>
        </div>
        <div>
            <h1>Gestion des Achats</h1>
            <p class="breadcrumb-text">Achats effectués via les dons en argent</p>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <div class="position-relative">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);"></i>
            <input type="text" id="tableSearch" class="form-control" placeholder="Rechercher..." style="padding-left:2.2rem;border-radius:var(--radius-sm);border:2px solid var(--gray-200);width:220px;">
        </div>
        <a href="<?php echo url('/achats/create'); ?>" class="btn btn-info-custom">
            <i class="bi bi-plus-lg"></i> Nouvel achat
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Argent disponible</p>
                    <h3 class="stat-value"><?php echo number_format($argentDisponible, 0, ',', ' '); ?> Ar</h3>
                </div>
                <div class="stat-icon bg-success-soft">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Total achats</p>
                    <h3 class="stat-value count-up" data-target="<?php echo $stats['total_achats']; ?>"><?php echo $stats['total_achats']; ?></h3>
                </div>
                <div class="stat-icon bg-info-soft">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Montant HT</p>
                    <h3 class="stat-value"><?php echo number_format($stats['total_montant_ht'], 0, ',', ' '); ?> Ar</h3>
                </div>
                <div class="stat-icon bg-primary-soft">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label">Frais totaux</p>
                    <h3 class="stat-value"><?php echo number_format($stats['total_frais'], 0, ',', ' '); ?> Ar</h3>
                </div>
                <div class="stat-icon bg-warning-soft">
                    <i class="bi bi-percent"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtre par ville -->
<div class="card-custom mb-3">
    <div class="card-body py-2 px-3">
        <form method="GET" action="<?php echo url('/achats'); ?>" class="d-flex align-items-center gap-3">
            <label class="mb-0 fw-bold text-muted"><i class="bi bi-funnel me-1"></i>Filtrer :</label>
            <select name="ville_id" class="form-select form-select-sm" style="width:250px;" onchange="this.form.submit()">
                <option value="">Toutes les villes</option>
                <?php foreach ($villes as $v): ?>
                    <option value="<?php echo $v['id']; ?>" <?php echo ($villeIdFiltre == $v['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($v['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($villeIdFiltre): ?>
                <a href="<?php echo url('/achats'); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Réinitialiser
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card-custom">
    <div class="card-header-custom">
        <h5><i class="bi bi-list-ul text-info"></i> Tous les achats</h5>
        <span class="badge-custom badge-info-soft" id="resultCount"><?php echo count($achats); ?> achat(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($achats)): ?>
            <div class="empty-state">
                <i class="bi bi-cart-x"></i>
                <h5>Aucun achat effectué</h5>
                <p>Utilisez les dons en argent pour acheter des fournitures</p>
                <a href="<?php echo url('/achats/create'); ?>" class="btn btn-info-custom">
                    <i class="bi bi-plus-lg"></i> Nouvel achat
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-custom" id="achatsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Besoin</th>
                            <th>Catégorie</th>
                            <th>Ville</th>
                            <th>Quantité</th>
                            <th>Prix unit.</th>
                            <th>Montant HT</th>
                            <th>Frais (<?php echo Flight::get('frais_achat'); ?>%)</th>
                            <th>Total TTC</th>
                            <th class="col-actions text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($achats as $i => $a): ?>
                        <tr>
                            <td class="text-muted"><?php echo $i + 1; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar3 text-muted"></i>
                                    <?php echo date('d/m/Y', strtotime($a['date_achat'])); ?>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($a['besoin_libelle']); ?></strong>
                            </td>
                            <td><span class="badge-custom badge-info-soft"><?php echo htmlspecialchars($a['categorie_nom']); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-geo-alt text-primary" style="font-size:0.8rem;"></i>
                                    <?php echo htmlspecialchars($a['ville_nom']); ?>
                                </div>
                            </td>
                            <td><strong><?php echo $a['quantite']; ?></strong></td>
                            <td><?php echo number_format($a['prix_unitaire'], 0, ',', ' '); ?> Ar</td>
                            <td><?php echo number_format($a['montant_ht'], 0, ',', ' '); ?> Ar</td>
                            <td>
                                <span class="badge-custom badge-warning-soft">
                                    <?php echo number_format($a['montant_frais'], 0, ',', ' '); ?> Ar
                                </span>
                            </td>
                            <td>
                                <strong class="text-primary"><?php echo number_format($a['montant_total'], 0, ',', ' '); ?> Ar</strong>
                            </td>
                            <td class="col-actions text-center">
                                <a href="<?php echo url('/achats/delete/' . $a['id']); ?>" class="btn-sm-action btn-delete btn-delete-confirm" data-name="cet achat" data-bs-toggle="tooltip" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include(__DIR__ . '/../../layout/footer.php'); ?>
