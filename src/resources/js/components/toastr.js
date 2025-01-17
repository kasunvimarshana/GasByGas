import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

// Default Toastr Configuration
const defaultOptions = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 5000,
    extendedTimeOut: 1000,
    preventDuplicates: true,
    newestOnTop: true,
};

/**
 * Initialize Toastr with custom options
 * @param {Object} customOptions
 */
const initToastr = (customOptions = {}) => {
    toastr.options = { ...defaultOptions, ...customOptions };
};

/**
 * Show Toastr Notification
 * @param {string} type - Notification type (success, error, info, warning)
 * @param {string} message - Notification message
 * @param {string} title - Notification title (optional)
 * @param {Object} options - Custom Toastr options
 */
const showNotification = (type, message, title = '', options = {}) => {
    initToastr(options);
    if (toastr[type]) {
        toastr[type](message, title);
    } else {
        console.warn(`Invalid Toastr type: ${type}`);
    }
};

export { showNotification };
