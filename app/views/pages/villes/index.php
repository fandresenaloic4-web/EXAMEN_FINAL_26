<?php
$currentPage = 'villes';
$pageTitle = 'Villes';
include(__DIR__ . '/../../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-info-gradient">
            <i class="bi bi-geo-alt"></i>
        </div>
        <div>
            <h1>Gestion des Villes</h1>
            <p class="breadcrumb-text">Liste des villes sinistrées</p>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <form method="GET" action="<?php echo url('/villes'); ?>" class="d-flex align-items-center gap-2">
            <label class="text-muted" style="font-size:0.85rem;white-space:nowrap;"><i class="bi bi-funnel me-1"></i>Région :</label>
            <select name="region_id" class="form-select form-select-sm" style="width:180px;border:2px solid var(--gray-200);border-radius:var(--radius-sm);" onchange="this.form.submit()">
                <option value="">Toutes</option>
                <?php foreach ($regions as $r): ?>
                    <option value="<?php echo $r['id']; ?>" <?php echo (isset($selectedRegion) && $selectedRegion == $r['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($r['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="position-relative">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);"></i>
            <input type="text" id="tableSearch" class="form-control" placeholder="Rechercher..." style="padding-left:2.2rem;border-radius:var(--radius-sm);border:2px solid var(--gray-200);width:220px;">
        </div>
        <a href="<?php echo url('/villes/create'); ?>" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Nouvelle ville
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="card-custom">
    <div class="card-header-custom">
        <h5><i class="bi bi-list-ul text-info"></i> Toutes les villes</h5>
        <span class="badge-custom badge-info-soft" id="resultCount"><?php echo count($villes); ?> ville(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($villes)): ?>
            <div class="empty-state">
                <i class="bi bi-geo-alt"></i>
                <h5>Aucune ville enregistrée</h5>
                <p>Ajoutez une nouvelle ville pour commencer</p>
                <a href="<?php echo url('/villes/create'); ?>" class="btn btn-primary-custom">
                    <i class="bi bi-plus-lg"></i> Ajouter une ville
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-custom" id="villesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom de la ville</th>
                            <th>Région</th>
                            <th class="col-actions text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($villes as $i => $v): ?>
                        <tr>
                            <td class="text-muted"><?php echo $i + 1; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-geo-alt-fill text-info"></i>
                                    <strong><?php echo htmlspecialchars($v['nom']); ?></strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge-custom badge-primary-soft">
                                    <i class="bi bi-map me-1"></i><?php echo htmlspecialchars($v['region_nom']); ?>
                                </span>
                            </td>
                            <td class="col-actions text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="<?php echo url('/villes/edit/' . $v['id']); ?>" class="btn-sm-action btn-edit" data-bs-toggle="tooltip" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?php echo url('/villes/delete/' . $v['id']); ?>" class="btn-sm-action btn-delete btn-delete-confirm" data-name="<?php echo htmlspecialchars($v['nom']); ?>" data-bs-toggle="tooltip" title="Supprimer">
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
