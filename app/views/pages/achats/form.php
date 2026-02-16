<?php
$currentPage = 'achats';
$pageTitle = 'Nouvel Achat';
include(__DIR__ . '/../../layout/header.php');

$errorMessages = [
    'besoin_invalide' => 'Le besoin sélectionné est invalide.',
    'categorie_argent' => 'Impossible d\'acheter un besoin de catégorie "Argent".',
    'quantite_invalide' => 'La quantité est invalide ou dépasse le besoin restant.',
    'dons_disponibles' => 'Il reste des dons disponibles dans cette catégorie. Utilisez d\'abord le dispatch avant d\'acheter.',
    'argent_insuffisant' => 'Le montant en argent disponible est insuffisant pour cet achat.'
];
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-title">
        <div class="page-icon bg-info-gradient">
            <i class="bi bi-cart-plus"></i>
        </div>
        <div>
            <h1>Nouvel Achat</h1>
            <p class="breadcrumb-text">
                <a href="<?php echo url('/achats'); ?>" class="text-decoration-none text-muted">Achats</a>
                <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i>
                Nouveau
            </p>
        </div>
    </div>
    <a href="<?php echo url('/achats'); ?>" class="btn btn-primary-custom">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<?php if ($error && isset($errorMessages[$error])): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 fade-in-up" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div><?php echo $errorMessages[$error]; ?></div>
    </div>
<?php endif; ?>

<!-- Argent disponible info -->
<div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="bi bi-wallet2 fs-5"></i>
    <div>
        <strong>Argent disponible :</strong> 
        <span class="fs-5 fw-bold"><?php echo number_format($argentDisponible, 0, ',', ' '); ?> Ar</span>
        <small class="text-muted ms-2">(Frais d'achat : <?php echo $fraisPourcent; ?>%)</small>
    </div>
</div>

<!-- Form Card -->
<div class="card-custom">
    <div class="card-header-custom">
        <h5><i class="bi bi-cart-plus text-info"></i> Formulaire d'achat</h5>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo $action; ?>" method="POST" id="achatForm">
            <div class="row g-4">
                <!-- Sélection du besoin -->
                <div class="col-md-12">
                    <label for="besoin_id" class="form-label fw-bold">
                        <i class="bi bi-clipboard-check me-1 text-primary"></i>Besoin à satisfaire
                    </label>
                    <select name="besoin_id" id="besoin_id" class="form-select" required>
                        <option value="">-- Sélectionner un besoin --</option>
                        <?php foreach ($besoins as $b): ?>
                            <option value="<?php echo $b['id']; ?>" 
                                    data-prix="<?php echo $b['prix_unitaire']; ?>"
                                    data-restant="<?php echo $b['quantite_restante']; ?>"
                                    data-categorie="<?php echo htmlspecialchars($b['categorie_nom']); ?>"
                                    data-ville="<?php echo htmlspecialchars($b['ville_nom']); ?>"
                                    <?php echo ($besoinIdPreselect == $b['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($b['libelle']); ?> — <?php echo htmlspecialchars($b['ville_nom']); ?> (<?php echo htmlspecialchars($b['categorie_nom']); ?>) — Restant: <?php echo $b['quantite_restante']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Info besoin sélectionné -->
                <div class="col-md-12" id="besoinInfo" style="display:none;">
                    <div class="alert alert-light border d-flex flex-wrap gap-4 py-2 px-3">
                        <span><i class="bi bi-geo-alt text-primary me-1"></i><strong>Ville:</strong> <span id="infoVille">-</span></span>
                        <span><i class="bi bi-tag text-info me-1"></i><strong>Catégorie:</strong> <span id="infoCategorie">-</span></span>
                        <span><i class="bi bi-box-seam text-warning me-1"></i><strong>Restant:</strong> <span id="infoRestant">-</span></span>
                        <span><i class="bi bi-cash text-success me-1"></i><strong>Prix unit.:</strong> <span id="infoPrix">-</span> Ar</span>
                    </div>
                </div>

                <!-- Quantité -->
                <div class="col-md-6">
                    <label for="quantite" class="form-label fw-bold">
                        <i class="bi bi-123 me-1 text-warning"></i>Quantité
                    </label>
                    <input type="number" name="quantite" id="quantite" class="form-control" min="1" required>
                    <small class="text-muted" id="quantiteHelp">Sélectionnez d'abord un besoin</small>
                </div>

                <!-- Prix unitaire -->
                <div class="col-md-6">
                    <label for="prix_unitaire" class="form-label fw-bold">
                        <i class="bi bi-cash me-1 text-success"></i>Prix unitaire (Ar)
                    </label>
                    <input type="number" name="prix_unitaire" id="prix_unitaire" class="form-control" min="0" step="any" required>
                </div>

                <!-- Calculateur en temps réel -->
                <div class="col-md-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body py-3">
                            <h6 class="fw-bold mb-3"><i class="bi bi-calculator me-1"></i>Récapitulatif du coût</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-white rounded">
                                        <small class="text-muted d-block">Montant HT</small>
                                        <strong class="fs-5 text-primary" id="calcHT">0 Ar</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-white rounded">
                                        <small class="text-muted d-block">Frais (<?php echo $fraisPourcent; ?>%)</small>
                                        <strong class="fs-5 text-warning" id="calcFrais">0 Ar</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-white rounded border border-primary">
                                        <small class="text-muted d-block">Total TTC</small>
                                        <strong class="fs-4 text-success" id="calcTotal">0 Ar</strong>
                                    </div>
                                </div>
                            </div>
                            <div id="calcWarning" class="mt-2 text-danger small" style="display:none;">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <span id="calcWarningText"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-info-custom" id="submitBtn">
                    <i class="bi bi-cart-check"></i> Effectuer l'achat
                </button>
                <a href="<?php echo url('/achats'); ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const besoinSelect = document.getElementById('besoin_id');
    const quantiteInput = document.getElementById('quantite');
    const prixInput = document.getElementById('prix_unitaire');
    const fraisPourcent = <?php echo $fraisPourcent; ?>;
    const argentDisponible = <?php echo $argentDisponible; ?>;
    
    const besoinInfo = document.getElementById('besoinInfo');
    const infoVille = document.getElementById('infoVille');
    const infoCategorie = document.getElementById('infoCategorie');
    const infoRestant = document.getElementById('infoRestant');
    const infoPrix = document.getElementById('infoPrix');
    const quantiteHelp = document.getElementById('quantiteHelp');
    
    const calcHT = document.getElementById('calcHT');
    const calcFrais = document.getElementById('calcFrais');
    const calcTotal = document.getElementById('calcTotal');
    const calcWarning = document.getElementById('calcWarning');
    const calcWarningText = document.getElementById('calcWarningText');
    const submitBtn = document.getElementById('submitBtn');

    function formatNumber(n) {
        return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    function updateBesoinInfo() {
        const opt = besoinSelect.options[besoinSelect.selectedIndex];
        if (!opt || !opt.value) {
            besoinInfo.style.display = 'none';
            quantiteInput.max = '';
            quantiteHelp.textContent = 'Sélectionnez d\'abord un besoin';
            return;
        }
        besoinInfo.style.display = 'block';
        infoVille.textContent = opt.dataset.ville;
        infoCategorie.textContent = opt.dataset.categorie;
        infoRestant.textContent = opt.dataset.restant;
        infoPrix.textContent = formatNumber(opt.dataset.prix);
        quantiteInput.max = opt.dataset.restant;
        quantiteHelp.textContent = 'Maximum: ' + opt.dataset.restant;
        prixInput.value = opt.dataset.prix;
        updateCalc();
    }

    function updateCalc() {
        const q = parseInt(quantiteInput.value) || 0;
        const p = parseFloat(prixInput.value) || 0;
        const ht = p * q;
        const frais = ht * (fraisPourcent / 100);
        const total = ht + frais;

        calcHT.textContent = formatNumber(ht) + ' Ar';
        calcFrais.textContent = formatNumber(frais) + ' Ar';
        calcTotal.textContent = formatNumber(total) + ' Ar';

        if (total > argentDisponible && q > 0) {
            calcWarning.style.display = 'block';
            calcWarningText.textContent = 'Le montant total (' + formatNumber(total) + ' Ar) dépasse l\'argent disponible (' + formatNumber(argentDisponible) + ' Ar)';
            submitBtn.disabled = true;
        } else {
            calcWarning.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    besoinSelect.addEventListener('change', updateBesoinInfo);
    quantiteInput.addEventListener('input', updateCalc);
    prixInput.addEventListener('input', updateCalc);

    // Si pré-sélection
    if (besoinSelect.value) {
        updateBesoinInfo();
    }
});
</script>

<?php include(__DIR__ . '/../../layout/footer.php'); ?>
