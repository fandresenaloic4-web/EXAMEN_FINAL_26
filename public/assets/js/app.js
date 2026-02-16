/* ============================================
   BNGRC - Application JavaScript
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initScrollAnimations();
    initCountUpAnimations();
    initDeleteConfirmations();
    initTooltips();
    initProgressBars();
    initTableSearch();
    initFormValidation();
    initAlertAutoDismiss();
});

/* ---------- Scroll Animations ---------- */
function initScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.fade-in-up, .animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
}

/* ---------- Count Up Animations ---------- */
function initCountUpAnimations() {
    const countElements = document.querySelectorAll('.count-up');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-target')) || 0;
                const duration = parseInt(el.getAttribute('data-duration')) || 1500;
                const suffix = el.getAttribute('data-suffix') || '';
                const prefix = el.getAttribute('data-prefix') || '';
                
                animateCount(el, 0, target, duration, prefix, suffix);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    countElements.forEach(el => observer.observe(el));
}

function animateCount(el, start, end, duration, prefix, suffix) {
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
        const value = Math.round(start + (end - start) * eased);
        
        el.textContent = prefix + formatNumber(value) + suffix;
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

/* ---------- Delete Confirmations ---------- */
function initDeleteConfirmations() {
    document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href') || this.getAttribute('data-url');
            const name = this.getAttribute('data-name') || 'cet élément';
            
            showDeleteModal(url, name);
        });
    });
}

function showDeleteModal(url, name) {
    // Remove existing modal if any
    const existing = document.getElementById('deleteConfirmModal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'deleteConfirmModal';
    modal.className = 'modal fade';
    modal.setAttribute('tabindex', '-1');
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered modal-delete">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Êtes-vous sûr de vouloir supprimer <strong>${name}</strong> ?</p>
                    <p class="text-muted mt-2 mb-0"><small>Cette action est irréversible.</small></p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Annuler
                    </button>
                    <a href="${url}" class="btn btn-danger-custom">
                        <i class="bi bi-trash me-1"></i>Supprimer
                    </a>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    modal.addEventListener('hidden.bs.modal', () => modal.remove());
}

/* ---------- Bootstrap Tooltips ---------- */
function initTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
}

/* ---------- Progress Bar Animation ---------- */
function initProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar-animate');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const width = bar.getAttribute('data-width') || '0';
                setTimeout(() => {
                    bar.style.width = width + '%';
                }, 200);
                observer.unobserve(bar);
            }
        });
    }, { threshold: 0.3 });

    progressBars.forEach(bar => {
        bar.style.width = '0%';
        observer.observe(bar);
    });
}

/* ---------- Table Search/Filter ---------- */
function initTableSearch() {
    const searchInput = document.getElementById('tableSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const table = document.querySelector('.table-custom tbody');
        if (!table) return;

        const rows = table.querySelectorAll('tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const match = text.includes(query);
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        // Update count badge
        const countBadge = document.getElementById('resultCount');
        if (countBadge) {
            countBadge.textContent = visibleCount + ' résultat(s)';
        }

        // Show/hide empty message
        const emptyRow = document.getElementById('emptySearchRow');
        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    });
}

/* ---------- Form Validation ---------- */
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}

/* ---------- Alert Auto-Dismiss ---------- */
function initAlertAutoDismiss() {
    document.querySelectorAll('.alert-auto-dismiss').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
}

/* ---------- Distribution Form: Dynamic Category Filter ---------- */
function initDistributionForm() {
    const donSelect = document.getElementById('don_id');
    const besoinSelect = document.getElementById('besoin_id');
    const quantiteInput = document.getElementById('quantite_attribuee');
    const maxInfo = document.getElementById('maxQuantiteInfo');

    if (!donSelect || !besoinSelect) return;

    donSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const categorieId = selectedOption.getAttribute('data-categorie-id');
        const disponible = parseInt(selectedOption.getAttribute('data-disponible')) || 0;

        // Filter besoins by category
        if (categorieId) {
            fetch('/api/besoins/categorie/' + categorieId)
                .then(res => res.json())
                .then(data => {
                    besoinSelect.innerHTML = '<option value="">-- Sélectionner un besoin --</option>';
                    data.forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b.id;
                        opt.textContent = b.libelle + ' (' + b.ville_nom + ') - Restant: ' + b.quantite_restante;
                        opt.setAttribute('data-restante', b.quantite_restante);
                        besoinSelect.appendChild(opt);
                    });
                    besoinSelect.disabled = false;
                })
                .catch(() => {
                    besoinSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            besoinSelect.innerHTML = '<option value="">-- Sélectionner d\'abord un don --</option>';
            besoinSelect.disabled = true;
        }

        updateMaxQuantite();
    });

    besoinSelect.addEventListener('change', function() {
        updateMaxQuantite();
    });

    function updateMaxQuantite() {
        const donOption = donSelect.options[donSelect.selectedIndex];
        const besoinOption = besoinSelect.options[besoinSelect.selectedIndex];
        
        const disponible = parseInt(donOption?.getAttribute('data-disponible')) || 0;
        const restante = parseInt(besoinOption?.getAttribute('data-restante')) || 0;
        
        const max = Math.min(disponible, restante);
        
        if (quantiteInput) {
            quantiteInput.max = max;
            quantiteInput.placeholder = 'Max: ' + max;
        }
        
        if (maxInfo) {
            if (max > 0) {
                maxInfo.innerHTML = `<i class="bi bi-info-circle me-1"></i>Quantité max: <strong>${max}</strong> (Don disponible: ${disponible}, Besoin restant: ${restante})`;
                maxInfo.style.display = 'block';
            } else {
                maxInfo.style.display = 'none';
            }
        }
    }
}

/* ---------- Export Table to CSV ---------- */
function exportTableCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(col => {
            // Skip action columns
            if (!col.classList.contains('col-actions')) {
                let text = col.textContent.trim().replace(/"/g, '""');
                rowData.push('"' + text + '"');
            }
        });
        csv.push(rowData.join(','));
    });
    
    const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename || 'export.csv';
    link.click();
}

/* ---------- Smooth Scroll ---------- */
function smoothScrollTo(elementId) {
    const el = document.getElementById(elementId);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
