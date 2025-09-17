/**
 * EbtekarDCB Anti-Fraud Integration JavaScript
 * This script provides integration with the Connex platform anti-fraud system
 * and handles mobile webview communication.
 */

class EbtekarDCBAntiFraud {
    constructor() {
        this.transactionIdentify = null;
        this.dcbProtectScript = null;
        this.isInitialized = false;
        this.retryCount = 0;
        this.maxRetries = 3;
    }

    /**
     * Initialize anti-fraud protection
     * @param {string} targetedElement - CSS selector for the target button
     * @param {object} options - Configuration options
     */
    async initialize(targetedElement = '#cta_button', options = {}) {
        const defaultOptions = {
            apiBaseUrl: '/api/ebtekardcb',
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            retryOnFailure: true,
            debug: false
        };

        this.options = { ...defaultOptions, ...options };
        this.targetedElement = targetedElement;

        try {
            await this.requestProtectedScript(targetedElement);
            this.isInitialized = true;

            if (this.options.debug) {
                console.log('EbtekarDCB Anti-Fraud initialized successfully');
            }
        } catch (error) {
            console.error('Failed to initialize anti-fraud protection:', error);

            if (this.options.retryOnFailure && this.retryCount < this.maxRetries) {
                this.retryCount++;
                setTimeout(() => this.initialize(targetedElement, options), 1000 * this.retryCount);
            } else {
                this.handleInitializationError(error);
            }
        }
    }

    /**
     * Request protected script from the API
     * @param {string} targetedElement - CSS selector for the target button
     */
    async requestProtectedScript(targetedElement) {
        const response = await fetch(`${this.options.apiBaseUrl}/request-protected-script`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.options.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || 'Failed to get protected script');
        }

        // Store transaction identify
        this.transactionIdentify = data.transaction_identify;
        localStorage.setItem('transaction_identify', this.transactionIdentify);

        // Inject the DCB protect script
        this.dcbProtectScript = data.dcbprotect;
        this.injectProtectedScript();

        // Set up event listeners
        this.setupEventListeners();
    }

    /**
     * Inject the protected script into the page
     */
    injectProtectedScript() {
        if (!this.dcbProtectScript) {
            throw new Error('No DCB protect script available');
        }

        const script = document.createElement('script');
        script.type = 'text/javascript';
        script.text = this.dcbProtectScript;
        document.body.appendChild(script);

        // Dispatch custom event
        const event = new Event('DCBProtectRun');
        document.dispatchEvent(event);

        if (this.options.debug) {
            console.log('DCB protect script injected and event dispatched');
        }
    }

    /**
     * Set up event listeners for gateway events
     */
    setupEventListeners() {
        // Listen for gateway load event
        document.addEventListener('gateway-load', () => {
            this.hideLoader();

            if (this.options.debug) {
                console.log('Gateway loaded successfully');
            }
        });

        // Listen for gateway error events
        document.addEventListener('gateway-error', (event) => {
            console.error('Gateway error:', event.detail);
            this.handleGatewayError(event.detail);
        });
    }

    /**
     * Hide the loading overlay
     */
    hideLoader() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.classList.add('hidden_loader');
        }
    }

    /**
     * Get the stored transaction identify
     * @returns {string|null}
     */
    getTransactionIdentify() {
        return this.transactionIdentify || localStorage.getItem('transaction_identify');
    }

    /**
     * Check if anti-fraud is properly initialized
     * @returns {boolean}
     */
    isReady() {
        return this.isInitialized && this.getTransactionIdentify() !== null;
    }

    /**
     * Handle initialization errors
     * @param {Error} error
     */
    handleInitializationError(error) {
        // Show user-friendly error message
        const locale = document.documentElement.lang || 'en';
        const message = locale === 'ar' ?
            'فشل في تهيئة نظام الحماية من الاحتيال' :
            'Failed to initialize fraud protection system';

        this.showErrorMessage(message);
    }

    /**
     * Handle gateway errors
     * @param {object} errorDetail
     */
    handleGatewayError(errorDetail) {
        const locale = document.documentElement.lang || 'en';
        const message = locale === 'ar' ?
            'خطأ في بوابة الحماية من الاحتيال' :
            'Fraud protection gateway error';

        this.showErrorMessage(message);
    }

    /**
     * Show error message to user
     * @param {string} message
     */
    showErrorMessage(message) {
        // Use existing toast system if available
        if (typeof showToast === 'function') {
            showToast(message, 'error');
        } else {
            // Fallback to alert
            alert(message);
        }
    }
}

/**
 * Mobile WebView Communication Handler
 */
class EbtekarDCBMobile {
    constructor() {
        this.isWebview = this.detectWebview();
        this.messageQueue = [];
    }

    /**
     * Detect if running in mobile webview
     * @returns {boolean}
     */
    detectWebview() {
        const userAgent = navigator.userAgent.toLowerCase();
        return /webview|wv|android.*version\/\d+\.\d+|iphone.*mobile.*safari/i.test(userAgent) ||
               window.webkit?.messageHandlers ||
               window.Android;
    }

    /**
     * Send message to mobile app
     * @param {string} action - Action name
     * @param {object} data - Data to send
     */
    sendToApp(action, data = {}) {
        if (!this.isWebview) {
            console.warn('Not in mobile webview - message not sent');
            return;
        }

        const message = {
            action: action,
            data: data,
            timestamp: Date.now()
        };

        try {
            if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.ebtekarApp) {
                // iOS WKWebView
                window.webkit.messageHandlers.ebtekarApp.postMessage(message);
            } else if (window.Android && window.Android.onWebViewMessage) {
                // Android WebView
                window.Android.onWebViewMessage(JSON.stringify(message));
            } else {
                // Queue message for later
                this.messageQueue.push(message);
                console.warn('Mobile interface not ready - message queued');
            }
        } catch (error) {
            console.error('Failed to send message to app:', error);
            this.messageQueue.push(message);
        }
    }

    /**
     * Process queued messages when mobile interface becomes available
     */
    processMessageQueue() {
        while (this.messageQueue.length > 0) {
            const message = this.messageQueue.shift();
            this.sendToApp(message.action, message.data);
        }
    }

    /**
     * Handle messages from mobile app
     * @param {string} messageString - JSON string from app
     */
    handleAppMessage(messageString) {
        try {
            const message = JSON.parse(messageString);
            this.processAppMessage(message);
        } catch (error) {
            console.error('Failed to parse app message:', error);
        }
    }

    /**
     * Process message from mobile app
     * @param {object} message
     */
    processAppMessage(message) {
        const { action, data } = message;

        switch (action) {
            case 'prefillPhone':
                this.prefillPhoneNumber(data.phone);
                break;
            case 'autofillOtp':
                this.autofillOtp(data.otp);
                break;
            case 'clearForm':
                this.clearForm();
                break;
            case 'setLanguage':
                this.setLanguage(data.locale);
                break;
            default:
                console.warn('Unknown app message action:', action);
        }
    }

    /**
     * Prefill phone number in form
     * @param {string} phone
     */
    prefillPhoneNumber(phone) {
        const phoneInput = document.querySelector('input[name="msisdn"], #msisdn, #mobile-msisdn');
        if (phoneInput) {
            phoneInput.value = phone;
            phoneInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    /**
     * Auto-fill OTP inputs
     * @param {string} otp
     */
    autofillOtp(otp) {
        if (otp.length !== 4) return;

        const otpInputs = document.querySelectorAll('.otp-input, .mobile-otp-input');
        otpInputs.forEach((input, index) => {
            if (index < otp.length) {
                input.value = otp[index];
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    }

    /**
     * Clear form inputs
     */
    clearForm() {
        const inputs = document.querySelectorAll('input[type="tel"], input[type="text"], .otp-input');
        inputs.forEach(input => {
            input.value = '';
            input.dispatchEvent(new Event('input', { bubbles: true }));
        });
    }

    /**
     * Set language
     * @param {string} locale
     */
    setLanguage(locale) {
        const url = new URL(window.location);
        url.searchParams.set('locale', locale);
        window.location.href = url.toString();
    }
}

/**
 * Utility Functions
 */
const EbtekarDCBUtils = {
    /**
     * Format Libyan phone number
     * @param {string} phone
     * @returns {string}
     */
    formatPhoneNumber(phone) {
        const cleaned = phone.replace(/\D/g, '');
        if (cleaned.length === 12 && cleaned.startsWith('2189')) {
            return cleaned.replace(/(\d{4})(\d{2})(\d{3})(\d{3})/, '$1 $2 $3 $4');
        }
        return phone;
    },

    /**
     * Validate Libyan phone number
     * @param {string} phone
     * @returns {boolean}
     */
    validateLibyanPhone(phone) {
        const cleaned = phone.replace(/\D/g, '');
        return /^2189[1-6][0-9]{7}$/.test(cleaned);
    },

    /**
     * Validate OTP format
     * @param {string} otp
     * @returns {boolean}
     */
    validateOTP(otp) {
        return /^[0-9]{4}$/.test(otp);
    },

    /**
     * Debounce function
     * @param {Function} func
     * @param {number} wait
     * @returns {Function}
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Check if device is mobile
     * @returns {boolean}
     */
    isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
};

/**
 * Global initialization
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize global instances
    window.ebtekarAntiFraud = new EbtekarDCBAntiFraud();
    window.ebtekarMobile = new EbtekarDCBMobile();

    // Make utilities globally available
    window.EbtekarDCBUtils = EbtekarDCBUtils;

    // Export utility functions to global scope for compatibility
    window.formatPhoneNumber = EbtekarDCBUtils.formatPhoneNumber;
    window.validateLibyanPhone = EbtekarDCBUtils.validateLibyanPhone;
    window.validateOTP = EbtekarDCBUtils.validateOTP;

    // Set up mobile message handler
    window.onAppMessage = function(message) {
        window.ebtekarMobile.handleAppMessage(message);
    };

    // Set up sendToApp function
    window.sendToApp = function(action, data) {
        window.ebtekarMobile.sendToApp(action, data);
    };

    // Auto-initialize anti-fraud on landing pages
    const isLandingPage = document.body.classList.contains('cptpl_page');
    if (isLandingPage) {
        const targetButton = document.querySelector('.cptpl_subscribe, #web-login-btn, #cta_button');
        if (targetButton) {
            const targetSelector = targetButton.id ? `#${targetButton.id}` : '.cptpl_subscribe';
            window.ebtekarAntiFraud.initialize(targetSelector);
        }
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        EbtekarDCBAntiFraud,
        EbtekarDCBMobile,
        EbtekarDCBUtils
    };
}