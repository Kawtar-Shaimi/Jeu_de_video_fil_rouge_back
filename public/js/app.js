class GameStoreManager {
    constructor() {
        this.baseUrl = window.location.origin;
        this.clients = [];
        this.jeux = [];
        this.ventes = [];
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadAllData();
    }

    setupEventListeners() {
        // Forms
        document.getElementById('clientForm').addEventListener('submit', (e) => this.handleClientSubmit(e));
        document.getElementById('jeuForm').addEventListener('submit', (e) => this.handleJeuSubmit(e));
        document.getElementById('venteForm').addEventListener('submit', (e) => this.handleVenteSubmit(e));

        // Type de jeu change
        document.getElementById('jeuType').addEventListener('change', (e) => this.handleJeuTypeChange(e));

        // Calcul automatique pour les ventes
        document.getElementById('venteJeu').addEventListener('change', () => this.calculateVenteTotal());
        document.getElementById('venteQuantite').addEventListener('input', () => this.calculateVenteTotal());

        // Tab changes
        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                const target = e.target.getAttribute('href').substring(1);
                if (target === 'stats') {
                    this.updateStats();
                }
            });
        });
    }

    async loadAllData() {
        await Promise.all([
            this.loadClients(),
            this.loadJeux(),
            this.loadVentes()
        ]);
        this.populateSelects();
    }

    // === CLIENTS ===
    async loadClients() {
        try {
            this.showLoading('clientsLoading');
            const response = await fetch(`${this.baseUrl}/api/v1/clients`);
            if (response.ok) {
                this.clients = await response.json();
                this.renderClients();
            } else {
                this.showToast('Erreur lors du chargement des clients', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        } finally {
            this.hideLoading('clientsLoading');
        }
    }

    renderClients() {
        const tbody = document.getElementById('clientsTable');
        tbody.innerHTML = '';

        this.clients.forEach(client => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${client.id}</td>
                <td>${client.nom}</td>
                <td>${client.email}</td>
                <td>${client.phone}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="gameStore.deleteClient(${client.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    async handleClientSubmit(e) {
        e.preventDefault();
        const formData = {
            nom: document.getElementById('clientNom').value,
            email: document.getElementById('clientEmail').value,
            phone: document.getElementById('clientPhone').value
        };

        try {
            const response = await fetch(`${this.baseUrl}/api/v1/clients`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                this.showToast('Client ajouté avec succès !', 'success');
                document.getElementById('clientForm').reset();
                await this.loadClients();
                this.populateSelects();
            } else {
                this.showToast('Erreur lors de l\'ajout du client', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        }
    }

    async deleteClient(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) return;

        try {
            const response = await fetch(`${this.baseUrl}/api/v1/clients/${id}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                this.showToast('Client supprimé avec succès !', 'success');
                await this.loadClients();
                this.populateSelects();
            } else {
                this.showToast('Erreur lors de la suppression', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        }
    }

    // === JEUX ===
    async loadJeux() {
        try {
            this.showLoading('jeuxLoading');
            const response = await fetch(`${this.baseUrl}/api/v1/jeus`);
            if (response.ok) {
                this.jeux = await response.json();
                this.renderJeux();
            } else {
                this.showToast('Erreur lors du chargement des jeux', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        } finally {
            this.hideLoading('jeuxLoading');
        }
    }

    renderJeux() {
        const tbody = document.getElementById('jeuxTable');
        tbody.innerHTML = '';

        this.jeux.forEach(jeu => {
            const row = document.createElement('tr');
            const stockBadge = jeu.stockDisponible > 0 ? 
                `<span class="badge bg-success">${jeu.stockDisponible}</span>` :
                `<span class="badge bg-danger">Épuisé</span>`;

            row.innerHTML = `
                <td>${jeu.id}</td>
                <td>${jeu.titre}</td>
                <td><span class="badge bg-primary">${jeu.typeJeu || 'Non défini'}</span></td>
                <td>${jeu.prix}€</td>
                <td>${jeu.genre}</td>
                <td>${stockBadge}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="gameStore.deleteJeu(${jeu.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    handleJeuTypeChange(e) {
        const type = e.target.value;
        const specifiques = document.getElementById('jeuSpecifiques');
        
        if (type === 'PC') {
            specifiques.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Configuration Minimale</label>
                    <textarea class="form-control" id="jeuConfigMinimale" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Support DVD</label>
                    <select class="form-control" id="jeuSupportDVD">
                        <option value="true">Oui</option>
                        <option value="false">Non</option>
                    </select>
                </div>
            `;
        } else if (type === 'Console') {
            specifiques.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Plateforme</label>
                    <select class="form-control" id="jeuPlateforme">
                        <option value="PlayStation">PlayStation</option>
                        <option value="Xbox">Xbox</option>
                        <option value="Nintendo">Nintendo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Code Région</label>
                    <input type="text" class="form-control" id="jeuRegionCode" placeholder="Ex: PAL, NTSC">
                </div>
            `;
        } else {
            specifiques.innerHTML = '';
        }
    }

    async handleJeuSubmit(e) {
        e.preventDefault();
        const type = document.getElementById('jeuType').value;
        
        const baseData = {
            titre: document.getElementById('jeuTitre').value,
            prix: parseFloat(document.getElementById('jeuPrix').value),
            genre: document.getElementById('jeuGenre').value,
            editeur: document.getElementById('jeuEditeur').value,
            stockDisponible: parseInt(document.getElementById('jeuStock').value)
        };

        let formData = { ...baseData };

        if (type === 'PC') {
            formData.configurationMinimale = document.getElementById('jeuConfigMinimale').value;
            formData.supportDVD = document.getElementById('jeuSupportDVD').value === 'true';
        } else if (type === 'Console') {
            formData.plateforme = document.getElementById('jeuPlateforme').value;
            formData.regionCode = document.getElementById('jeuRegionCode').value;
        }

        try {
            const endpoint = type === 'PC' ? '/api/v1/jeupcs' : '/api/v1/jeuConsoles';
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                this.showToast('Jeu ajouté avec succès !', 'success');
                document.getElementById('jeuForm').reset();
                document.getElementById('jeuSpecifiques').innerHTML = '';
                await this.loadJeux();
                this.populateSelects();
            } else {
                this.showToast('Erreur lors de l\'ajout du jeu', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        }
    }

    async deleteJeu(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce jeu ?')) return;

        try {
            const response = await fetch(`${this.baseUrl}/api/v1/jeus/${id}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                this.showToast('Jeu supprimé avec succès !', 'success');
                await this.loadJeux();
                this.populateSelects();
            } else {
                this.showToast('Erreur lors de la suppression', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        }
    }

    // === VENTES ===
    async loadVentes() {
        try {
            this.showLoading('ventesLoading');
            const response = await fetch(`${this.baseUrl}/api/v1/ventes`);
            if (response.ok) {
                this.ventes = await response.json();
                this.renderVentes();
            } else {
                this.showToast('Erreur lors du chargement des ventes', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        } finally {
            this.hideLoading('ventesLoading');
        }
    }

    renderVentes() {
        const tbody = document.getElementById('ventesTable');
        tbody.innerHTML = '';

        this.ventes.forEach(vente => {
            const row = document.createElement('tr');
            const client = this.clients.find(c => c.id == vente.clientId) || { nom: 'Inconnu' };
            const jeu = this.jeux.find(j => j.id == vente.jeuId) || { titre: 'Inconnu' };
            
            let statutBadge;
            switch(vente.statut) {
                case 'PAYÉE':
                    statutBadge = '<span class="badge bg-success">PAYÉE</span>';
                    break;
                case 'EN_ATTENTE':
                    statutBadge = '<span class="badge bg-warning">EN_ATTENTE</span>';
                    break;
                case 'ANNULÉE':
                    statutBadge = '<span class="badge bg-danger">ANNULÉE</span>';
                    break;
                default:
                    statutBadge = '<span class="badge bg-secondary">Inconnu</span>';
            }

            row.innerHTML = `
                <td>${vente.id}</td>
                <td>${new Date(vente.dateVente).toLocaleDateString('fr-FR')}</td>
                <td>${client.nom}</td>
                <td>${jeu.titre}</td>
                <td>${vente.quantite}</td>
                <td>${vente.montantTotal}€</td>
                <td>${statutBadge}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="gameStore.deleteVente(${vente.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    calculateVenteTotal() {
        const jeuId = document.getElementById('venteJeu').value;
        const quantite = parseInt(document.getElementById('venteQuantite').value) || 0;
        const calculDiv = document.getElementById('venteCalcul');

        if (jeuId && quantite > 0) {
            const jeu = this.jeux.find(j => j.id == jeuId);
            if (jeu) {
                const sousTotal = jeu.prix * quantite;
                const tva = sousTotal * 0.20; // TVA 20%
                const total = sousTotal + tva;
                
                calculDiv.innerHTML = `
                    <strong>Calcul:</strong><br>
                    ${jeu.titre}: ${jeu.prix}€ × ${quantite} = ${sousTotal.toFixed(2)}€<br>
                    TVA (20%): ${tva.toFixed(2)}€<br>
                    <strong>Total: ${total.toFixed(2)}€</strong>
                `;
                calculDiv.style.display = 'block';
            }
        } else {
            calculDiv.style.display = 'none';
        }
    }

    async handleVenteSubmit(e) {
        e.preventDefault();
        const jeuId = parseInt(document.getElementById('venteJeu').value);
        const quantite = parseInt(document.getElementById('venteQuantite').value);
        
        // Vérifier le stock
        const jeu = this.jeux.find(j => j.id === jeuId);
        if (!jeu || jeu.stockDisponible < quantite) {
            this.showToast('Stock insuffisant pour ce jeu !', 'error');
            return;
        }

        const sousTotal = jeu.prix * quantite;
        const tva = sousTotal * 0.20;
        const montantTotal = sousTotal + tva;

        const formData = {
            clientId: parseInt(document.getElementById('venteClient').value),
            jeuId: jeuId,
            quantite: quantite,
            montantTotal: parseFloat(montantTotal.toFixed(2)),
            statut: document.getElementById('venteStatut').value,
            dateVente: new Date().toISOString()
        };

        try {
            const response = await fetch(`${this.baseUrl}/api/v1/ventes`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                this.showToast('Vente enregistrée avec succès !', 'success');
                document.getElementById('venteForm').reset();
                document.getElementById('venteCalcul').style.display = 'none';
                await Promise.all([this.loadVentes(), this.loadJeux()]); // Recharger pour mettre à jour le stock
            } else {
                this.showToast('Erreur lors de l\'enregistrement de la vente', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        }
    }

    async deleteVente(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')) return;

        try {
            const response = await fetch(`${this.baseUrl}/api/v1/ventes/${id}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                this.showToast('Vente supprimée avec succès !', 'success');
                await this.loadVentes();
            } else {
                this.showToast('Erreur lors de la suppression', 'error');
            }
        } catch (error) {
            this.showToast('Erreur de connexion', 'error');
        }
    }

    // === UTILITAIRES ===
    populateSelects() {
        // Populate client select
        const clientSelect = document.getElementById('venteClient');
        clientSelect.innerHTML = '<option value="">Sélectionner un client...</option>';
        this.clients.forEach(client => {
            const option = document.createElement('option');
            option.value = client.id;
            option.textContent = client.nom;
            clientSelect.appendChild(option);
        });

        // Populate jeu select
        const jeuSelect = document.getElementById('venteJeu');
        jeuSelect.innerHTML = '<option value="">Sélectionner un jeu...</option>';
        this.jeux.filter(jeu => jeu.stockDisponible > 0).forEach(jeu => {
            const option = document.createElement('option');
            option.value = jeu.id;
            option.textContent = `${jeu.titre} (${jeu.prix}€) - Stock: ${jeu.stockDisponible}`;
            jeuSelect.appendChild(option);
        });
    }

    updateStats() {
        document.getElementById('totalClients').textContent = this.clients.length;
        document.getElementById('totalJeux').textContent = this.jeux.length;
        document.getElementById('totalVentes').textContent = this.ventes.length;
        
        const chiffreAffaires = this.ventes
            .filter(vente => vente.statut === 'PAYÉE')
            .reduce((total, vente) => total + vente.montantTotal, 0);
        document.getElementById('chiffreAffaires').textContent = `${chiffreAffaires.toFixed(2)}€`;
    }

    showLoading(elementId) {
        document.getElementById(elementId).style.display = 'block';
    }

    hideLoading(elementId) {
        document.getElementById(elementId).style.display = 'none';
    }

    showToast(message, type) {
        const toastContainer = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now();
        
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
        
        const toastHtml = `
            <div id="${toastId}" class="toast show ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${icon} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            const toast = document.getElementById(toastId);
            if (toast) toast.remove();
        }, 3000);
    }
}

// Initialize the app
const gameStore = new GameStoreManager(); 