<?php
$currentPage = 'distributions';
$pageTitle = 'Distributions';
include(__DIR__ . '/../../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-warning-gradient">
            <i class="bi bi-truck"></i>
        </div>
        <div>
            <h1>Gestion des Distributions</h1>
            <p class="breadcrumb-text">Historique des distributions de dons aux besoins</p>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <div class="position-relative">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);"></i>
            <input type="text" id="tableSearch" class="form-control" placeholder="Rechercher..." style="padding-left:2.2rem;border-radius:var(--radius-sm);border:2px solid var(--gray-200);width:220px;">
        </div>
        <a href="/distributions/dispatch" class="btn btn-warning-custom" data-bs-toggle="tooltip" title="Dispatch automatique">
            <i class="bi bi-lightning-charge"></i> Dispatch auto
        </a>
        <a href="/distributions/create" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Nouvelle distribution
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="card-custom">
    <div class="card-header-custom">
        <h5><i class="bi bi-list-ul text-warning"></i> Toutes les distributions</h5>
        <span class="badge-custom badge-warning-soft" id="resultCount"><?php echo count($distributions); ?> distribution(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($distributions)): ?>
            <div class="empty-state">
                <i class="bi bi-truck"></i>
                <h5>Aucune distribution effectuée</h5>
                <p>Créez une distribution ou utilisez le dispatch automatique</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="/distributions/dispatch" class="btn btn-warning-custom">
                        <i class="bi bi-lightning-charge"></i> Dispatch auto
                    </a>
                    <a href="/distributions/create" class="btn btn-primary-custom">
                        <i class="bi bi-plus-lg"></i> Distribution manuelle
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-custom" id="distributionsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Don</th>
                            <th>Catégorie</th>
                            <th>Besoin</th>
                            <th>Ville</th>
                            <th>Quantité</th>
                            <th class="col-actions text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($distributions as $i => $dist): ?>
                        <tr>
                            <td class="text-muted"><?php echo $i + 1; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar3 text-muted"></i>
                                    <?php echo date('d/m/Y', strtotime($dist['date_distribution'])); ?>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-heart text-success"></i>
                                    <strong><?php echo htmlspecialchars($dist['don_nom']); ?></strong>
                                </div>
                            </td>
                            <td><span class="badge-custom badge-info-soft"><?php echo htmlspecialchars($dist['categorie_nom']); ?></span></td>
                            <td><?php echo htmlspecialchars($dist['besoin_libelle']); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-geo-alt text-primary" style="font-size:0.8rem;"></i>
                                    <?php echo htmlspecialchars($dist['ville_nom']); ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge-custom badge-success-soft">
                                    <i class="bi bi-box-seam me-1"></i><?php echo $dist['quantite_attribuee']; ?>
                                </span>
                            </td>
                            <td class="col-actions text-center">
                                <a href="/distributions/delete/<?php echo $dist['id']; ?>" class="btn-sm-action btn-delete btn-delete-confirm" data-name="cette distribution" data-bs-toggle="tooltip" title="Supprimer">
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
