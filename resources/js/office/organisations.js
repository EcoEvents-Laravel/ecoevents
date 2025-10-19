import '../bootstrap';

class OrganizationOffice {
    constructor() {
        this.organizations = { data: [], current_page: 1, last_page: 1 };
        this.loading = false;
        this.saving = false;
        this.deleting = false;
        this.searchQuery = '';
        this.searchTimeout = null;
        this.perPage = 12;
        this.currentPage = 1;

        // Modal states
        this.showCreateModal = false;
        this.showEditModal = false;
        this.showDeleteModal = false;

        // Form data
        this.form = {
            name: '',
            description: '',
            contact_email: '',
            website: '',
            logo_url: ''
        };

        this.editingOrganization = null;
        this.organizationToDelete = null;

        this.init();
    }

    init() {
        this.setupAxios();
        this.setupEventListeners();
        this.fetchOrganizations();
        this.setupReactivity();
    }

    setupAxios() {
        // CSRF token setup for axios
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
    }

    setupEventListeners() {
        // Handle create button
        document.addEventListener('click', (e) => {
            if (e.target.id === 'add-organization-btn') {
                e.preventDefault();
                this.showCreateModal = true;
                this.updateView();
            }

            // Handle edit buttons
            if (e.target.classList.contains('edit-btn')) {
                e.preventDefault();
                const card = e.target.closest('[data-organization-id]');
                if (card) {
                    const orgId = parseInt(card.dataset.organizationId);
                    const organization = this.organizations.data.find(org => org.id === orgId);
                    if (organization) {
                        this.editOrganization(organization);
                    }
                }
            }

            // Handle delete buttons
            if (e.target.classList.contains('delete-btn')) {
                e.preventDefault();
                const card = e.target.closest('[data-organization-id]');
                if (card) {
                    const orgId = parseInt(card.dataset.organizationId);
                    const organization = this.organizations.data.find(org => org.id === orgId);
                    if (organization) {
                        this.deleteOrganization(organization);
                    }
                }
            }

            // Handle modal close buttons
            if (e.target.id === 'cancel-btn' || e.target.id === 'cancel-delete-btn') {
                e.preventDefault();
                this.closeModal();
            }

            // Handle confirm delete
            if (e.target.id === 'confirm-delete-btn') {
                e.preventDefault();
                this.confirmDelete();
            }

            // Handle pagination
            if (e.target.matches('#pagination button')) {
                e.preventDefault();
                const text = e.target.textContent.trim();
                if (text === 'Previous') {
                    this.changePage(this.currentPage - 1);
                } else if (text === 'Next') {
                    this.changePage(this.currentPage + 1);
                } else if (/^\d+$/.test(text)) {
                    this.changePage(parseInt(text));
                }
            }
        });

        // Handle form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.matches('#create-edit-modal form')) {
                e.preventDefault();
                if (this.editingOrganization) {
                    this.updateOrganization();
                } else {
                    this.createOrganization();
                }
            }
        });

        // Handle search input
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchQuery = e.target.value;
                this.debounceSearch();
            });
        }

        // Handle per page change
        const perPageSelect = document.getElementById('per-page-select');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', (e) => {
                this.perPage = parseInt(e.target.value);
                this.currentPage = 1;
                this.fetchOrganizations();
            });
        }
    }

    setupReactivity() {
        // Update view when state changes
        this.updateView();
    }

    updateView() {
        this.updateOrganizationsDisplay();
        this.updateModals();
        this.updatePagination();
    }

    updateOrganizationsDisplay() {
        const grid = document.querySelector('.grid');
        if (!grid) return;

        if (this.loading) {
            grid.innerHTML = `
                <div class="col-span-full flex justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                </div>
            `;
            return;
        }

        if (!this.organizations.data || this.organizations.data.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No organizations found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first organization.</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = this.organizations.data.map(org => `
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow" data-organization-id="${org.id}">
                <div class="mb-4">
                    <img src="${org.logo_url || '/images/default-logo.png'}"
                         alt="${org.name} logo"
                         class="w-16 h-16 object-cover rounded-lg bg-gray-100">
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">${this.escapeHtml(org.name)}</h3>
                    <p class="text-gray-600 text-sm line-clamp-3 mb-3">${this.escapeHtml(org.description)}</p>
                    <div class="space-y-1 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            ${this.escapeHtml(org.contact_email)}
                        </div>
                        ${org.website ? `
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <a href="${this.escapeHtml(org.website)}" target="_blank" class="text-blue-600 hover:underline">
                                    ${this.escapeHtml(org.website)}
                                </a>
                            </div>
                        ` : ''}
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <button class="edit-btn text-blue-600 hover:text-blue-800 px-3 py-1 rounded text-sm font-medium">
                        Edit
                    </button>
                    <button class="delete-btn text-red-600 hover:text-red-800 px-3 py-1 rounded text-sm font-medium">
                        Delete
                    </button>
                </div>
            </div>
        `).join('');
    }

    updateModals() {
        const createEditModal = document.getElementById('create-edit-modal');
        const deleteModal = document.getElementById('delete-modal');

        // Update create/edit modal
        if (this.showCreateModal || this.showEditModal) {
            if (createEditModal) {
                createEditModal.style.display = 'block';
                this.updateFormValues();
                this.updateModalTitle();
            }
        } else {
            if (createEditModal) {
                createEditModal.style.display = 'none';
            }
        }

        // Update delete modal
        if (this.showDeleteModal) {
            if (deleteModal) {
                deleteModal.style.display = 'block';
                const deleteMessage = document.getElementById('delete-message');
                if (deleteMessage && this.organizationToDelete) {
                    deleteMessage.textContent = `Are you sure you want to delete "${this.organizationToDelete.name}"? This action cannot be undone.`;
                }
            }
        } else {
            if (deleteModal) {
                deleteModal.style.display = 'none';
            }
        }
    }

    updateFormValues() {
        const nameInput = document.getElementById('org-name');
        const descInput = document.getElementById('org-description');
        const emailInput = document.getElementById('org-email');
        const websiteInput = document.getElementById('org-website');
        const logoInput = document.getElementById('org-logo');

        if (nameInput) nameInput.value = this.form.name;
        if (descInput) descInput.value = this.form.description;
        if (emailInput) emailInput.value = this.form.contact_email;
        if (websiteInput) websiteInput.value = this.form.website;
        if (logoInput) logoInput.value = this.form.logo_url;
    }

    updateModalTitle() {
        const modalTitle = document.getElementById('modal-title');
        const submitBtn = document.getElementById('submit-btn');

        if (modalTitle && submitBtn) {
            if (this.editingOrganization) {
                modalTitle.textContent = 'Edit Organization';
                submitBtn.textContent = 'Update';
            } else {
                modalTitle.textContent = 'Create New Organization';
                submitBtn.textContent = 'Create';
            }
        }
    }

    updatePagination() {
        const pagination = document.querySelector('.flex.space-x-2');
        if (!pagination || !this.organizations.last_page || this.organizations.last_page <= 1) return;

        const pages = this.getPaginationPages();
        pagination.innerHTML = pages.map(page => {
            if (page === '...') {
                return '<span class="px-3 py-2 text-sm font-medium text-gray-700">...</span>';
            }

            const isActive = page === this.organizations.current_page;
            const isDisabled = (page === 'Previous' && this.organizations.current_page <= 1) ||
                             (page === 'Next' && this.organizations.current_page >= this.organizations.last_page);

            return `
                <button class="${isActive ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'} ${isDisabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'} px-3 py-2 text-sm font-medium border border-gray-300 rounded-md">
                    ${page}
                </button>
            `;
        }).join('');
    }

    async fetchOrganizations() {
        this.loading = true;
        this.updateView();

        try {
            const params = new URLSearchParams({
                page: this.currentPage,
                per_page: this.perPage
            });

            if (this.searchQuery) {
                params.append('search', this.searchQuery);
            }

            const response = await axios.get(`/api/organisations?${params}`);
            this.organizations = response.data;
        } catch (error) {
            console.error('Error fetching organizations:', error);
            this.showError('Failed to load organizations');
        } finally {
            this.loading = false;
            this.updateView();
        }
    }

    debounceSearch() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.currentPage = 1;
            this.fetchOrganizations();
        }, 300);
    }

    async createOrganization() {
        this.saving = true;
        try {
            const formData = this.getFormData();
            const response = await axios.post('/api/organisations', formData);
            this.showSuccess(response.data.message);
            this.closeModal();
            this.resetForm();
            this.fetchOrganizations();
        } catch (error) {
            console.error('Error creating organization:', error);
            if (error.response && error.response.data.errors) {
                this.showValidationErrors(error.response.data.errors);
            } else {
                this.showError('Failed to create organization');
            }
        } finally {
            this.saving = false;
        }
    }

    editOrganization(organization) {
        this.editingOrganization = organization;
        this.form = { ...organization };
        this.showEditModal = true;
        this.updateView();
    }

    async updateOrganization() {
        this.saving = true;
        try {
            const formData = this.getFormData();
            const response = await axios.put(`/api/organisations/${this.editingOrganization.id}`, formData);
            this.showSuccess(response.data.message);
            this.closeModal();
            this.resetForm();
            this.fetchOrganizations();
        } catch (error) {
            console.error('Error updating organization:', error);
            if (error.response && error.response.data.errors) {
                this.showValidationErrors(error.response.data.errors);
            } else {
                this.showError('Failed to update organization');
            }
        } finally {
            this.saving = false;
        }
    }

    getFormData() {
        return {
            name: document.getElementById('org-name').value,
            description: document.getElementById('org-description').value,
            contact_email: document.getElementById('org-email').value,
            website: document.getElementById('org-website').value,
            logo_url: document.getElementById('org-logo').value
        };
    }

    deleteOrganization(organization) {
        this.organizationToDelete = organization;
        this.showDeleteModal = true;
        this.updateView();
    }

    async confirmDelete() {
        this.deleting = true;
        try {
            const response = await axios.delete(`/api/organisations/${this.organizationToDelete.id}`);
            this.showSuccess(response.data.message);
            this.showDeleteModal = false;
            this.organizationToDelete = null;
            this.fetchOrganizations();
        } catch (error) {
            console.error('Error deleting organization:', error);
            this.showError('Failed to delete organization');
        } finally {
            this.deleting = false;
        }
    }

    changePage(page) {
        if (page >= 1 && page <= this.organizations.last_page) {
            this.currentPage = page;
            this.fetchOrganizations();
            this.scrollToTop();
        }
    }

    closeModal() {
        this.showCreateModal = false;
        this.showEditModal = false;
        this.showDeleteModal = false;
        this.editingOrganization = null;
        this.organizationToDelete = null;
        this.resetForm();
        this.updateView();
    }

    resetForm() {
        this.form = {
            name: '',
            description: '',
            contact_email: '',
            website: '',
            logo_url: ''
        };
    }

    scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showValidationErrors(errors) {
        const errorMessages = Object.values(errors).flat();
        this.showError(errorMessages.join(', '));
    }

    showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }

    getPaginationPages() {
        const pages = [];
        const totalPages = this.organizations.last_page;
        const currentPage = this.organizations.current_page;

        if (totalPages <= 7) {
            for (let i = 1; i <= totalPages; i++) {
                pages.push(i);
            }
        } else {
            if (currentPage <= 4) {
                pages.push(1, 2, 3, 4, 5, '...', totalPages);
            } else if (currentPage >= totalPages - 3) {
                pages.push(1, '...', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages);
            } else {
                pages.push(1, '...', currentPage - 1, currentPage, currentPage + 1, '...', totalPages);
            }
        }

        return pages;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize the application
document.addEventListener('DOMContentLoaded', () => {
    new OrganizationOffice();
});