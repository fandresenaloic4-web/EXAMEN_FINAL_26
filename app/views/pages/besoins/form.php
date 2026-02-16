<?php
$currentPage = 'besoins';
$isEdit = isset($besoin) && $besoin;
$pageTitle = $isEdit ? 'Modifier le besoin' : 'Nouveau besoin';
include(__DIR__ . '/../../layout/header.php');
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon <?php echo $isEdit ? 'bg-warning-gradient' : 'bg-primary-gradient'; ?>">
            <i class="bi bi-<?php echo $isEdit ? 'pencil-square' : 'plus-lg'; ?>"></i>
        </div>
        <div>
            <h1><?php echo $pageTitle; ?></h1>
            <p class="breadcrumb-text">
                <a href="/besoins" class="text-decoration-none text-muted">Besoins</a>
                <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i>
                <?php echo $isEdit ? 'Modifier' : 'Créer'; ?>
            </p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>
                    <i class="bi bi-<?php echo $isEdit ? 'pencil' : 'clipboard-plus'; ?> text-primary"></i>
                    Informations du besoin
                </h5>
            </div>
            <div class="card-body">
                <form action="<?php echo $action; ?>" method="POST" class="form-custom needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ville_id" class="form-label">
                                <i class="bi bi-geo-alt"></i> Ville
                            </label>
                            <select class="form-select" id="ville_id" name="ville_id" required>
                                <option value="">-- Sélectionner une ville --</option>
                                <?php foreach ($villes as $v): ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo ($isEdit && $besoin['ville_id'] == $v['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($v['nom']); ?> (<?php echo htmlspecialchars($v['region_nom']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner une ville.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="categorie_id" class="form-label">
                                <i class="bi bi-tag"></i> Catégorie
                            </label>
                            <select class="form-select" id="categorie_id" name="categorie_id" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo ($isEdit && $besoin['categorie_id'] == $c['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner une catégorie.</div>
                        </div>

                        <div class="col-12">
                            <label for="libelle" class="form-label">
                                <i class="bi bi-card-text"></i> Libellé
                            </label>
                            <input type="text" class="form-control" id="libelle" name="libelle" 
                                   value="<?php echo $isEdit ? htmlspecialchars($besoin['libelle']) : ''; ?>" 
                                   placeholder="Ex: Tentes, Riz, Médicaments..." required>
                            <div class="invalid-feedback">Veuillez saisir un libellé.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="prix_unitaire" class="form-label">
                                <i class="bi bi-cash"></i> Prix unitaire (Ar)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-exchange"></i></span>
                                <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" 
                                       value="<?php echo $isEdit ? $besoin['prix_unitaire'] : ''; ?>" 
                                       min="0" step="0.01" placeholder="0.00" required>
                            </div>
                            <div class="invalid-feedback">Veuillez saisir un prix valide.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="quantite_demandee" class="form-label">
                                <i class="bi bi-hash"></i> Quantité demandée
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                                <input type="number" class="form-control" id="quantite_demandee" name="quantite_demandee" 
                                       value="<?php echo $isEdit ? $besoin['quantite_demandee'] : ''; ?>" 
                                       min="1" placeholder="0" required>
                            </div>
                            <div class="invalid-feedback">La quantité doit être supérieure à 0.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="/besoins" class="btn btn-outline-custom">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn <?php echo $isEdit ? 'btn-warning-custom' : 'btn-success-custom'; ?>">
                            <i class="bi bi-<?php echo $isEdit ? 'check-lg' : 'plus-lg'; ?>"></i>
                            <?php echo $isEdit ? 'Enregistrer les modifications' : 'Créer le besoin'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../../layout/footer.php'); ?>
