document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const bookmarkForm = document.getElementById('bookmarkForm');
    const loginForm = document.getElementById('loginForm');
    const settingsForm = document.getElementById('settingsForm');
    const saveBookmarkForm = document.getElementById('saveBookmarkForm');
    const status = document.getElementById('status');

    // Form elements
    const titleInput = document.getElementById('title');
    const urlInput = document.getElementById('url');
    const descriptionInput = document.getElementById('description');
    const favoriteInput = document.getElementById('favorite');
    const privateInput = document.getElementById('private');
    const saveBtn = document.getElementById('saveBtn');

    // Settings elements
    const apiUrlInput = document.getElementById('apiUrl');
    const apiTokenInput = document.getElementById('apiToken');
    const saveSettingsBtn = document.getElementById('saveSettings');

    // Navigation elements
    const openSettingsBtn = document.getElementById('openSettings');
    const showSettingsLink = document.getElementById('showSettings');
    const backToBookmarkLink = document.getElementById('backToBookmark');

    let currentTab = null;

    // Initialize
    init();

    async function init() {
        try {
            // Get current tab
            const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
            currentTab = tab;

            // Pre-fill form with current tab data
            titleInput.value = tab.title || '';
            urlInput.value = tab.url || '';

            // Load settings and check if configured
            const settings = await loadSettings();
            if (settings.apiUrl && settings.apiToken) {
                showBookmarkForm();
            } else {
                showLoginForm();
            }
        } catch (error) {
            showError('Failed to initialize extension: ' + error.message);
        }
    }

    // Event listeners
    saveBookmarkForm.addEventListener('submit', handleSaveBookmark);
    saveSettingsBtn.addEventListener('click', handleSaveSettings);
    openSettingsBtn.addEventListener('click', showSettingsForm);
    showSettingsLink.addEventListener('click', showSettingsForm);
    backToBookmarkLink.addEventListener('click', showBookmarkForm);

    async function handleSaveBookmark(e) {
        e.preventDefault();

        saveBtn.disabled = true;
        saveBtn.textContent = '💾 Saving...';
        hideStatus();

        try {
            const settings = await loadSettings();
            if (!settings.apiUrl || !settings.apiToken) {
                throw new Error('API settings not configured');
            }

            const bookmarkData = {
                title: titleInput.value.trim(),
                url: urlInput.value.trim(),
                description: descriptionInput.value.trim(),
                favorite: favoriteInput.checked,
                private: privateInput.checked
            };

            const response = await fetch(`${settings.apiUrl}/quick-bookmark`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${settings.apiToken}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(bookmarkData)
            });

            const result = await response.json();

            if (response.ok) {
                showSuccess(result.message || 'Bookmark saved successfully!');

                // Close popup after a short delay
                setTimeout(() => {
                    window.close();
                }, 1500);
            } else {
                throw new Error(result.message || 'Failed to save bookmark');
            }
        } catch (error) {
            showError('Error: ' + error.message);
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = '💾 Save Bookmark';
        }
    }

    async function handleSaveSettings() {
        const apiUrl = apiUrlInput.value.trim();
        const apiToken = apiTokenInput.value.trim();

        if (!apiUrl || !apiToken) {
            showError('Please fill in both API URL and token');
            return;
        }

        try {
            // Test the API connection
            const response = await fetch(`${apiUrl}/user`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Invalid API URL or token');
            }

            // Save settings
            await chrome.storage.sync.set({
                apiUrl: apiUrl,
                apiToken: apiToken
            });

            showSuccess('Settings saved successfully!');

            setTimeout(() => {
                showBookmarkForm();
            }, 1000);
        } catch (error) {
            showError('Failed to save settings: ' + error.message);
        }
    }

    async function loadSettings() {
        try {
            const result = await chrome.storage.sync.get(['apiUrl', 'apiToken']);
            return {
                apiUrl: result.apiUrl || '',
                apiToken: result.apiToken || ''
            };
        } catch (error) {
            return { apiUrl: '', apiToken: '' };
        }
    }

    function showBookmarkForm() {
        bookmarkForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        settingsForm.classList.add('hidden');
        hideStatus();
    }

    function showLoginForm() {
        loginForm.classList.remove('hidden');
        bookmarkForm.classList.add('hidden');
        settingsForm.classList.add('hidden');
        hideStatus();
    }

    function showSettingsForm() {
        settingsForm.classList.remove('hidden');
        bookmarkForm.classList.add('hidden');
        loginForm.classList.add('hidden');
        hideStatus();

        // Load current settings
        loadSettings().then(settings => {
            apiUrlInput.value = settings.apiUrl;
            apiTokenInput.value = settings.apiToken;
        });
    }

    function showSuccess(message) {
        status.textContent = message;
        status.className = 'status success';
        status.classList.remove('hidden');
    }

    function showError(message) {
        status.textContent = message;
        status.className = 'status error';
        status.classList.remove('hidden');
    }

    function hideStatus() {
        status.classList.add('hidden');
    }
});
