// Import OverlayScrollbars CSS
import 'overlayscrollbars/css/OverlayScrollbars.css';

// Import OverlayScrollbars library
import { OverlayScrollbars } from 'overlayscrollbars';

// Assign OverlayScrollbars to the window object to make it globally available
window.OverlayScrollbars = OverlayScrollbars;

// Default settings for the scrollbar
const DEFAULT_SCROLLBAR_CONFIG = {
    theme: 'os-theme-light', // Default theme
    autoHide: 'leave',       // Scrollbar auto-hide behavior
    clickScroll: true,       // Enable click scrolling
};

/**
 * Initialize OverlayScrollbars on a given element with custom options
 * @param {HTMLElement} element - The DOM element where the scrollbar will be applied.
 * @param {Object} [customConfig={}] - Custom configuration to override the default ones.
 */
export function initializeOverlayScrollbars(element, customConfig = {}) {
    if (!element) {
        return;
    }

    const config = {
      scrollbars: {
        theme: customConfig.theme || DEFAULT_SCROLLBAR_CONFIG.theme,
        autoHide: customConfig.autoHide || DEFAULT_SCROLLBAR_CONFIG.autoHide,
        clickScroll: customConfig.clickScroll || DEFAULT_SCROLLBAR_CONFIG.clickScroll,
      },
      ...customConfig, // Allow other custom settings to override the defaults
    };

    // Apply OverlayScrollbars with the provided options
    OverlayScrollbars(element, config);
}

/**
 * Apply OverlayScrollbars to multiple elements with custom options.
 * @param {NodeList} elements - List of elements to apply OverlayScrollbars.
 * @param {Object} options - Custom configuration options for scrollbars.
 */
export function applyOverlayScrollbarsToElements(elements, options = {}) {
    elements.forEach(element => {
        initializeOverlayScrollbars(element, options);
    });
}
