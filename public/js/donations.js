document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('#searchForm');
    const searchInput = document.querySelector('input[name="search"]');
    const typeSelect = document.querySelector('select[name="type"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const campaignSelect = document.querySelector('select[name="campaign_id"]');
    const donationsTableBody = document.querySelector('.donation-table tbody');
    const paginationContainer = document.querySelector('.card-footer');
    let typingTimer;

    // Function to update the page title based on filters
    function updatePageTitle() {
        const titleElement = document.querySelector('.h3.mb-0');
        const subtitleElement = document.querySelector('.text-muted.mb-0');
        let title = 'All Donations';
        let subtitle = 'Comprehensive list of all donations';

        if (typeSelect.value === 'monetary') {
            title = 'Monetary Donations';
            subtitle = 'List of all monetary donations';
        } else if (typeSelect.value === 'non_monetary') {
            title = 'Non-Monetary Donations';
            subtitle = 'List of all non-monetary donations';
        }

        if (statusSelect.value === 'completed') {
            title = 'Completed Donations';
            subtitle = 'List of all completed donations';
        } else if (statusSelect.value === 'pending') {
            title = 'Pending Donations';
            subtitle = 'List of all pending donations';
        }

        titleElement.textContent = title;
        subtitleElement.textContent = subtitle;
    }

    // Function to update URL with current filters
    function updateURL(params) {
        const url = new URL(window.location.href);
        Object.keys(params).forEach(key => {
            if (params[key]) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        });
        window.history.pushState({}, '', url);
    }

    // Function to fetch and update donations
    async function fetchDonations() {
        const params = new URLSearchParams({
            search: searchInput.value,
            type: typeSelect.value,
            status: statusSelect.value,
            campaign_id: campaignSelect.value
        });

        try {
            const response = await fetch(`${window.location.pathname}?${params}`);
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update the donations table
            const newTableBody = doc.querySelector('.donation-table tbody');
            donationsTableBody.innerHTML = newTableBody.innerHTML;
            
            // Update pagination if it exists
            const newPagination = doc.querySelector('.card-footer');
            if (paginationContainer && newPagination) {
                paginationContainer.innerHTML = newPagination.innerHTML;
            }

            // Update URL
            updateURL({
                search: searchInput.value,
                type: typeSelect.value,
                status: statusSelect.value,
                campaign_id: campaignSelect.value
            });

            // Update page title
            updatePageTitle();

        } catch (error) {
            console.error('Error fetching donations:', error);
        }
    }

    // Event listener for search input with debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(fetchDonations, 500);
    });

    // Event listeners for select filters
    typeSelect.addEventListener('change', fetchDonations);
    statusSelect.addEventListener('change', fetchDonations);
    campaignSelect.addEventListener('change', fetchDonations);

    // Prevent form submission and handle it with AJAX instead
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchDonations();
    });

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            fetch(link.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    donationsTableBody.innerHTML = doc.querySelector('.donation-table tbody').innerHTML;
                    if (paginationContainer) {
                        paginationContainer.innerHTML = doc.querySelector('.card-footer').innerHTML;
                    }
                    window.history.pushState({}, '', link.href);
                })
                .catch(error => console.error('Error:', error));
        }
    });
}); 