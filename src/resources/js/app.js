import './bootstrap';

//==========
// Wait for the DOM to fully load
document.addEventListener('DOMContentLoaded', () => {
    // console.log(`DOM Content Loaded!`);
});

// Import jQuery and make it globally available
// This ensures that jQuery is available globally in your project, so you can use it throughout your app.
// The `$` and `jQuery` are assigned to the window object so they can be accessed globally.
import $ from 'jquery';
window.$ = window.jQuery = $;

// Example: Custom script to test if jQuery is working
$(() => {
    // This function will run when the DOM is ready.
    // It logs a message to the console, confirming that jQuery is properly integrated.
    console.log(`jQuery is working!`);
});
