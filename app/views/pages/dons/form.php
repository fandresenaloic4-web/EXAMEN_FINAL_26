<?php
$currentPage = 'dons';
$isEdit = isset($don) && $don;
$pageTitle = $isEdit ? 'Modifier le don' : 'Nouveau don';
include(__DIR__ . '/../../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon <?php echo $isEdit ? 'bg-warning-gradient' : 'bg-success-gradient'; ?>">
            <i class="bi bi-<?php echo $isEdit ? 'pencil-square' : 'plus-lg'; ?>"></i>
        </div>
        <div>
            <h1><?php echo $pageTitle; ?></h1>
            <p class="breadcrumb-text">
                <a href="/dons" class="text-decoration-none text-muted">Dons</a>
                <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i>
                <?php echo $isEdit ? 'Modifier' : 'Créer'; ?>
            </p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>
                    <i class="bi bi-<?php echo $isEdit ? 'pencil' : 'gift'; ?> text-success"></i>
                    Informations du don
                </h5>
            </div>
            <div class="card-body">
                <form action="<?php echo $action; ?>" method="POST" class="form-custom needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="nom" class="form-label">
                                <i class="bi bi-person-heart"></i> Nom du donateur
                            </label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   value="<?php echo $isEdit ? htmlspecialchars($don['nom']) : ''; ?>" 
                                   placeholder="Ex: Croix-Rouge, UNICEF, Particulier..." required>
                            <div class="invalid-feedback">Veuillez saisir le nom du donateur.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="categorie_id" class="form-label">
                                <i class="bi bi-tag"></i> Catégorie
                            </label>
                            <select class="form-select" id="categorie_id" name="categorie_id" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo ($isEdit && $don['categorie_id'] == $c['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner une catégorie.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="quantite" class="form-label">
                                <i class="bi bi-hash"></i> Quantité
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                                <input type="number" class="form-control" id="quantite" name="quantite" 
                                       value="<?php echo $isEdit ? $don['quantite'] : ''; ?>" 
                                       min="1" placeholder="0" required>
                            </div>
                            <div class="invalid-feedback">La quantité doit être supérieure à 0.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="/dons" class="btn btn-outline-custom">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn <?php echo $isEdit ? 'btn-warning-custom' : 'btn-success-custom'; ?>">
                            <i class="bi bi-<?php echo $isEdit ? 'check-lg' : 'plus-lg'; ?>"></i>
                            <?php echo $isEdit ? 'Enregistrer les modifications' : 'Créer le don'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../../layout/footer.php'); ?>
