<?php
$currentPage = 'besoins';
$pageTitle = 'Besoins';
include(__DIR__ . '/../../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-primary-gradient">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div>
            <h1>Gestion des Besoins</h1>
            <p class="breadcrumb-text">Liste de tous les besoins enregistrés</p>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <div class="position-relative">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);"></i>
            <input type="text" id="tableSearch" class="form-control" placeholder="Rechercher..." style="padding-left:2.2rem;border-radius:var(--radius-sm);border:2px solid var(--gray-200);width:220px;">
        </div>
        <a href="/besoins/create" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Nouveau besoin
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="card-custom">
    <div class="card-header-custom">
        <h5><i class="bi bi-list-ul text-primary"></i> Tous les besoins</h5>
        <span class="badge-custom badge-primary-soft" id="resultCount"><?php echo count($besoins); ?> besoin(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($besoins)): ?>
            <div class="empty-state">
                <i class="bi bi-clipboard-x"></i>
                <h5>Aucun besoin enregistré</h5>
                <p>Ajoutez un nouveau besoin pour commencer le suivi</p>
                <a href="/besoins/create" class="btn btn-primary-custom">
                    <i class="bi bi-plus-lg"></i> Ajouter un besoin
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-custom" id="besoinsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ville</th>
                            <th>Catégorie</th>
                            <th>Libellé</th>
                            <th>Prix Unit.</th>
                            <th>Demandé</th>
                            <th>Attribué</th>
                            <th>Restant</th>
                            <th>Montant Total</th>
                            <th style="width:180px">Progression</th>
                            <th class="col-actions text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($besoins as $i => $b): 
                            $attribue = $b['quantite_demandee'] - $b['quantite_restante'];
                            $progress = $b['quantite_demandee'] > 0 ? round(($attribue / $b['quantite_demandee']) * 100) : 0;
                            $progressClass = $progress >= 100 ? 'bg-success' : ($progress >= 50 ? 'bg-info' : ($progress > 0 ? 'bg-warning' : 'bg-secondary'));
                        ?>
                        <tr>
                            <td class="text-muted"><?php echo $i + 1; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-geo-alt text-primary"></i>
                                    <strong><?php echo htmlspecialchars($b['ville_nom']); ?></strong>
                                </div>
                            </td>
                            <td><span class="badge-custom badge-info-soft"><?php echo htmlspecialchars($b['categorie_nom']); ?></span></td>
                            <td><?php echo htmlspecialchars($b['libelle']); ?></td>
                            <td><?php echo number_format($b['prix_unitaire'], 0, ',', ' '); ?> Ar</td>
                            <td><strong><?php echo $b['quantite_demandee']; ?></strong></td>
                            <td><span class="badge-custom badge-success-soft"><?php echo $attribue; ?></span></td>
                            <td>
                                <?php if ($b['quantite_restante'] > 0): ?>
                                    <span class="badge-custom badge-danger-soft"><?php echo $b['quantite_restante']; ?></span>
                                <?php else: ?>
                                    <span class="badge-custom badge-success-soft">0 ✓</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($b['montant_total'] ?? ($b['prix_unitaire'] * $b['quantite_demandee']), 0, ',', ' '); ?> Ar</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-custom flex-grow-1">
                                        <div class="progress-bar progress-bar-animate <?php echo $progressClass; ?>" data-width="<?php echo $progress; ?>"></div>
                                    </div>
                                    <span class="progress-label"><?php echo $progress; ?>%</span>
                                </div>
                            </td>
                            <td class="col-actions text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="/besoins/edit/<?php echo $b['id']; ?>" class="btn-sm-action btn-edit" data-bs-toggle="tooltip" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/besoins/delete/<?php echo $b['id']; ?>" class="btn-sm-action btn-delete btn-delete-confirm" data-name="<?php echo htmlspecialchars($b['libelle']); ?>" data-bs-toggle="tooltip" title="Supprimer">
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
