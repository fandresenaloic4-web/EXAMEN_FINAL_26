<?php
$currentPage = 'dons';
$pageTitle = 'Dons';
include(__DIR__ . '/../../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-success-gradient">
            <i class="bi bi-gift"></i>
        </div>
        <div>
            <h1>Gestion des Dons</h1>
            <p class="breadcrumb-text">Liste de tous les dons reçus</p>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <div class="position-relative">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);"></i>
            <input type="text" id="tableSearch" class="form-control" placeholder="Rechercher..." style="padding-left:2.2rem;border-radius:var(--radius-sm);border:2px solid var(--gray-200);width:220px;">
        </div>
        <a href="<?php echo url('/dons/create'); ?>" class="btn btn-success-custom">
            <i class="bi bi-plus-lg"></i> Nouveau don
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="card-custom">
    <div class="card-header-custom">
        <h5><i class="bi bi-list-ul text-success"></i> Tous les dons</h5>
        <span class="badge-custom badge-success-soft" id="resultCount"><?php echo count($dons); ?> don(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($dons)): ?>
            <div class="empty-state">
                <i class="bi bi-gift"></i>
                <h5>Aucun don enregistré</h5>
                <p>Ajoutez un nouveau don pour commencer la distribution</p>
                <a href="<?php echo url('/dons/create'); ?>" class="btn btn-success-custom">
                    <i class="bi bi-plus-lg"></i> Ajouter un don
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-custom" id="donsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom du donateur</th>
                            <th>Catégorie</th>
                            <th>Quantité totale</th>
                            <th>Distribué</th>
                            <th>Disponible</th>
                            <th>Statut</th>
                            <th class="col-actions text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dons as $i => $d): 
                            $disponible = $d['quantite_disponible'] ?? ($d['quantite'] - ($d['quantite_distribuee'] ?? 0));
                            $distribue = $d['quantite_distribuee'] ?? 0;
                            $progress = $d['quantite'] > 0 ? round(($distribue / $d['quantite']) * 100) : 0;
                        ?>
                        <tr>
                            <td class="text-muted"><?php echo $i + 1; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-heart text-success"></i>
                                    <strong><?php echo htmlspecialchars($d['nom']); ?></strong>
                                </div>
                            </td>
                            <td><span class="badge-custom badge-info-soft"><?php echo htmlspecialchars($d['categorie_nom']); ?></span></td>
                            <td><strong><?php echo $d['quantite']; ?></strong></td>
                            <td><span class="badge-custom badge-success-soft"><?php echo $distribue; ?></span></td>
                            <td>
                                <?php if ($disponible > 0): ?>
                                    <span class="badge-custom badge-warning-soft"><?php echo $disponible; ?></span>
                                <?php else: ?>
                                    <span class="badge-custom badge-success-soft">0 ✓</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($disponible <= 0): ?>
                                    <span class="badge-custom badge-success-soft"><i class="bi bi-check-circle me-1"></i>Épuisé</span>
                                <?php elseif ($distribue > 0): ?>
                                    <span class="badge-custom badge-warning-soft"><i class="bi bi-clock-history me-1"></i>En cours</span>
                                <?php else: ?>
                                    <span class="badge-custom badge-info-soft"><i class="bi bi-box-seam me-1"></i>Disponible</span>
                                <?php endif; ?>
                            </td>
                            <td class="col-actions text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="<?php echo url('/dons/edit/' . $d['id']); ?>" class="btn-sm-action btn-edit" data-bs-toggle="tooltip" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?php echo url('/dons/delete/' . $d['id']); ?>" class="btn-sm-action btn-delete btn-delete-confirm" data-name="<?php echo htmlspecialchars($d['nom']); ?>" data-bs-toggle="tooltip" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </a>
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

<?php include(__DIR__ . '/../../layout/footer.php'); ?>
