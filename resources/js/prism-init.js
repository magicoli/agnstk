// Load Prism.js as a script (not as ES6 module)
// We need to get the base URL from the page's meta or calculate it
function getBaseUrl() {
    // Try to get base URL from the page context
    const base = document.querySelector('base');
    if (base) {
        return base.href;
    }
    
    // No fallback, if failed there is no way to know the actual path
    // const path = window.location.pathname;
    // if (path.includes('/agnstk/')) {
    //     return window.location.origin + '/agnstk/';
    // }
    // return window.location.origin + '/';
}

const script = document.createElement('script');
script.src = getBaseUrl() + 'js/prism.js';
script.onload = function() {
    // Initialize Prism.js after it's loaded
    if (typeof Prism !== 'undefined') {
        // Re-highlight all code blocks when the script loads
        Prism.highlightAll();
        
        // Also highlight dynamically added content
        document.addEventListener('DOMContentLoaded', function() {
            Prism.highlightAll();
        });
    }
};
document.head.appendChild(script);
