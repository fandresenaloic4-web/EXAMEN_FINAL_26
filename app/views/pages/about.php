<?php
$pageTitle = 'À propos';
$currentPage = 'about';
?>

<?php Flight::render('layout/header', ['pageTitle' => $pageTitle, 'currentPage' => $currentPage]); ?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="text-center mb-5 scroll-animate">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:80px;height:80px;background:var(--primary-gradient);color:white;font-size:2rem;">
            <i class="bi bi-info-circle"></i>
        </div>
        <h1 style="font-weight:800;color:var(--gray-800);">À propos du BNGRC</h1>
        <p class="text-muted mx-auto" style="max-width:600px;font-size:1.05rem;">
            Bureau National de Gestion des Risques et des Catastrophes
        </p>
    </div>

    <div class="row g-4">
        <!-- Mission -->
        <div class="col-lg-6 scroll-animate">
            <div class="card-custom h-100">
                <div class="card-body-custom p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3" style="width:48px;height:48px;background:rgba(37,99,235,0.1);color:var(--primary);">
                            <i class="bi bi-bullseye" style="font-size:1.3rem;"></i>
                        </div>
                        <h4 class="mb-0" style="font-weight:700;color:var(--gray-800);">Notre Mission</h4>
                    </div>
                    <p style="color:var(--gray-500);line-height:1.8;">
                        Le BNGRC est l'organisme national chargé de la coordination des secours et de la gestion des catastrophes à Madagascar. 
                        Notre mission est d'assurer la protection des populations face aux risques naturels et de coordonner les opérations 
                        d'aide humanitaire sur l'ensemble du territoire malgache.
                    </p>
                </div>
            </div>
        </div>

        <!-- Plateforme -->
        <div class="col-lg-6 scroll-animate">
            <div class="card-custom h-100">
                <div class="card-body-custom p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3" style="width:48px;height:48px;background:rgba(16,185,129,0.1);color:var(--success);">
                            <i class="bi bi-laptop" style="font-size:1.3rem;"></i>
                        </div>
                        <h4 class="mb-0" style="font-weight:700;color:var(--gray-800);">La Plateforme</h4>
                    </div>
                    <p style="color:var(--gray-500);line-height:1.8;">
                        Cette application web permet le suivi en temps réel des besoins, des dons et des distributions d'aide 
                        aux populations sinistrées dans les différentes régions du pays. Elle facilite la coordination entre 
                        les acteurs humanitaires pour une réponse efficace aux catastrophes.
                    </p>
                </div>
            </div>
        </div>

        <!-- Fonctionnalités -->
        <div class="col-lg-6 scroll-animate">
            <div class="card-custom h-100">
                <div class="card-body-custom p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3" style="width:48px;height:48px;background:rgba(245,158,11,0.1);color:var(--warning);">
                            <i class="bi bi-gear" style="font-size:1.3rem;"></i>
                        </div>
                        <h4 class="mb-0" style="font-weight:700;color:var(--gray-800);">Fonctionnalités</h4>
                    </div>
                    <ul class="list-unstyled" style="color:var(--gray-500);line-height:2;">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Gestion des villes et régions sinistrées</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Suivi des besoins par catégorie et localité</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Enregistrement et traçabilité des dons</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Distribution automatique et équitable des aides</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Tableau de bord avec statistiques en temps réel</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Équipe -->
        <div class="col-lg-6 scroll-animate">
            <div class="card-custom h-100">
                <div class="card-body-custom p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3" style="width:48px;height:48px;background:rgba(139,92,246,0.1);color:#8b5cf6;">
                            <i class="bi bi-people" style="font-size:1.3rem;"></i>
                        </div>
                        <h4 class="mb-0" style="font-weight:700;color:var(--gray-800);">Notre Équipe</h4>
                    </div>
                    <p style="color:var(--gray-500);line-height:1.8;margin-bottom:1rem;">
                        Projet développé par une équipe d'étudiants dans le cadre du semestre 3.
                    </p>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center p-2 rounded" style="background:var(--gray-50);">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3" style="width:40px;height:40px;background:var(--primary-gradient);color:white;font-size:0.85rem;font-weight:600;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <span style="font-weight:600;color:var(--gray-800);">ETU004291</span>
                                <span class="text-muted ms-2" style="font-size:0.8rem;">Membre</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-2 rounded" style="background:var(--gray-50);">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3" style="width:40px;height:40px;background:var(--primary-gradient);color:white;font-size:0.85rem;font-weight:600;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <span style="font-weight:600;color:var(--gray-800);">ETU004059</span>
                                <span class="text-muted ms-2" style="font-size:0.8rem;">Membre</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-2 rounded" style="background:var(--gray-50);">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3" style="width:40px;height:40px;background:var(--primary-gradient);color:white;font-size:0.85rem;font-weight:600;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <span style="font-weight:600;color:var(--gray-800);">ETU004297</span>
                                <span class="text-muted ms-2" style="font-size:0.8rem;">Membre</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Retour au dashboard -->
    <div class="text-center mt-5 scroll-animate">
        <a href="/dashboard" class="btn btn-primary-custom btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Retour au Dashboard
        </a>
    </div>
</div>

<?php Flight::render('layout/footer'); ?>
