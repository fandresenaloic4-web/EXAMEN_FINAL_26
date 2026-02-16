<?php
$currentPage = 'villes';
$isEdit = isset($ville) && $ville;
$pageTitle = $isEdit ? 'Modifier la ville' : 'Nouvelle ville';
include(__DIR__ . '/../../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon <?php echo $isEdit ? 'bg-warning-gradient' : 'bg-info-gradient'; ?>">
            <i class="bi bi-<?php echo $isEdit ? 'pencil-square' : 'plus-lg'; ?>"></i>
        </div>
        <div>
            <h1><?php echo $pageTitle; ?></h1>
            <p class="breadcrumb-text">
                <a href="<?php echo url('/villes'); ?>" class="text-decoration-none text-muted">Villes</a>
                <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i>
                <?php echo $isEdit ? 'Modifier' : 'Créer'; ?>
            </p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>
                    <i class="bi bi-<?php echo $isEdit ? 'pencil' : 'geo-alt'; ?> text-info"></i>
                    Informations de la ville
                </h5>
            </div>
            <div class="card-body">
                <form action="<?php echo $action; ?>" method="POST" class="form-custom needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="nom" class="form-label">
                                <i class="bi bi-building"></i> Nom de la ville
                            </label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   value="<?php echo $isEdit ? htmlspecialchars($ville['nom']) : ''; ?>" 
                                   placeholder="Ex: Antananarivo, Toamasina..." required>
                            <div class="invalid-feedback">Veuillez saisir le nom de la ville.</div>
                        </div>

                        <div class="col-12">
                            <label for="region_id" class="form-label">
                                <i class="bi bi-map"></i> Région
                            </label>
                            <select class="form-select" id="region_id" name="region_id" required>
                                <option value="">-- Sélectionner une région --</option>
                                <?php foreach ($regions as $r): ?>
                                    <option value="<?php echo $r['id']; ?>" <?php echo ($isEdit && $ville['region_id'] == $r['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($r['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner une région.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="<?php echo url('/villes'); ?>" class="btn btn-outline-custom">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn <?php echo $isEdit ? 'btn-warning-custom' : 'btn-success-custom'; ?>">
                            <i class="bi bi-<?php echo $isEdit ? 'check-lg' : 'plus-lg'; ?>"></i>
                            <?php echo $isEdit ? 'Enregistrer les modifications' : 'Créer la ville'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../../layout/footer.php'); ?>
