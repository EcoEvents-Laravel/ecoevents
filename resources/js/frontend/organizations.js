import '../bootstrap';

class OrganizationsFrontend {
    constructor() {
        console.log('OrganizationsFrontend constructor called');
        this.organizations = { data: [], current_page: 1, last_page: 1 };
        this.loading = false;
        this.searchQuery = '';
        this.searchTimeout = null;
        this.perPage = 9;

        this.init();
    }

    init() {
        console.log('OrganizationsFrontend init called');
        this.setupAxios();
        this.setupEventListeners();
        this.fetchOrganizations();
    }

    setupAxios() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
    }

    setupEventListeners() {
        // Handle search input
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchQuery = e.target.value;
                this.debounceSearch();
            });
        }
    }

    async fetchOrganizations() {
        this.loading = true;
        this.showLoadingState();

        try {
            const params = new URLSearchParams({
                page: this.organizations.current_page,
                per_page: this.perPage
            });

            if (this.searchQuery) {
                params.append('search', this.searchQuery);
            }

            console.log('Fetching organizations from:', `/api/organisations?${params}`);
            const response = await axios.get(`/api/organisations?${params}`);
            console.log('API Response:', response.data);

            this.organizations = response.data;
            this.displayOrganizations();
        } catch (error) {
            console.error('Error fetching organizations:', error);
            this.showError('Failed to load organizations. Check console for details.');
        } finally {
            this.loading = false;
        }
    }

    debounceSearch() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.organizations.current_page = 1;
            this.fetchOrganizations();
        }, 300);
    }

    displayOrganizations() {
        console.log('Displaying organizations:', this.organizations);
        const grid = document.getElementById('organizations-grid');
        const emptyState = document.getElementById('empty-state');
        const pagination = document.getElementById('pagination');

        if (!grid) {
            console.error('Organizations grid element not found!');
            return;
        }

        // Hide loading state
        this.hideLoadingState();

        if (!this.organizations.data || this.organizations.data.length === 0) {
            console.log('No organizations to display');
            grid.style.display = 'none';
            if (pagination) pagination.style.display = 'none';
            if (emptyState) emptyState.style.display = 'block';
            return;
        }

        console.log(`Displaying ${this.organizations.data.length} organizations`);
        // Show organizations
        grid.style.display = 'grid';
        if (emptyState) emptyState.style.display = 'none';

        const html = this.organizations.data.map(org => `
            <div class="organization-card bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
                <!-- Organization Logo -->
                <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-blue-50 to-purple-50 p-8 flex items-center justify-center">
                    <img src="${org.logo_url || '/images/default-logo.png'}"
                         alt="${this.escapeHtml(org.name)} logo"
                         class="max-h-20 max-w-full object-contain group-hover:scale-105 transition-transform duration-300"
                         onerror="this.src='/images/default-logo.png'">
                </div>

                <!-- Organization Info -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                        ${this.escapeHtml(org.name)}
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                        ${this.escapeHtml(org.description)}
                    </p>

                    <!-- Contact Information -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:${this.escapeHtml(org.contact_email)}" class="hover:text-blue-600 transition-colors">
                                ${this.escapeHtml(org.contact_email)}
                            </a>
                        </div>

                        ${org.website ? `
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <a href="${this.escapeHtml(org.website)}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="hover:text-blue-600 transition-colors">
                                    Visit Website
                                </a>
                            </div>
                        ` : ''}
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                        <button onclick="viewOrganization(${org.id})"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            View Details
                        </button>
                        ${org.website ? `
                            <a href="${this.escapeHtml(org.website)}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        ` : ''}
                    </div>
                </div>
            </div>
        `).join('');

        console.log('Generated HTML:', html);
        grid.innerHTML = html;
        console.log('Organizations displayed successfully');

        // Update pagination
        this.updatePagination();
    }

    updatePagination() {
        const pagination = document.getElementById('pagination');
        if (!pagination || !this.organizations.last_page || this.organizations.last_page <= 1) {
            if (pagination) pagination.style.display = 'none';
            return;
        }

        pagination.style.display = 'flex';

        const pages = this.getPaginationPages();
        pagination.innerHTML = pages.map(page => {
            if (page === '...') {
                return '<span class="px-3 py-2 text-sm font-medium text-gray-700">...</span>';
            }

            const isActive = page === this.organizations.current_page;
            return `
                <button onclick="changePage(${page})"
                        class="px-4 py-2 mx-1 text-sm font-medium rounded-lg transition-colors ${
                            isActive
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
                        }">
                    ${page}
                </button>
            `;
        }).join('');
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

    showLoadingState() {
        const loadingState = document.getElementById('loading-state');
        const grid = document.getElementById('organizations-grid');

        if (loadingState) loadingState.style.display = 'flex';
        if (grid) grid.style.display = 'none';
    }

    hideLoadingState() {
        const loadingState = document.getElementById('loading-state');
        if (loadingState) loadingState.style.display = 'none';
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg transform transition-transform ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;

        notification.innerHTML = `
            <div class="flex items-center">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Global functions for onclick handlers
function viewOrganization(id) {
    // For now, just show an alert - you can expand this to show a detailed modal or navigate to a detail page
    alert(`Viewing organization with ID: ${id}`);
}

function changePage(page) {
    const app = window.organizationsApp;
    if (app && page >= 1 && page <= app.organizations.last_page) {
        app.organizations.current_page = page;
        app.fetchOrganizations();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Initialize the application
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM Content Loaded - Initializing Organizations Frontend...');

    // Check if required elements exist
    const grid = document.getElementById('organizations-grid');
    const searchInput = document.getElementById('search-input');

    console.log('Grid element found:', !!grid);
    console.log('Search input found:', !!searchInput);

    try {
        window.organizationsApp = new OrganizationsFrontend();
        console.log('Organizations Frontend initialized successfully');

        // Add global test function for debugging
        window.testOrganizations = () => {
            console.log('Testing organizations fetch...');
            window.organizationsApp.fetchOrganizations();
        };

        console.log('Test function available: run testOrganizations() in console');

    } catch (error) {
        console.error('Error initializing Organizations Frontend:', error);
    }
});