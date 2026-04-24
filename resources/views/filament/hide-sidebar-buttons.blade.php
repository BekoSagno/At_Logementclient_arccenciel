<script>
document.addEventListener('DOMContentLoaded', function() {
    function hideSidebarButtons() {
        // Sélectionner tous les boutons de toggle de la sidebar
        const buttons = document.querySelectorAll(
            'button[aria-label*="barre latérale"], ' +
            'button[aria-label*="sidebar"], ' +
            'button[aria-label*="Agrandir"], ' +
            'button[aria-label*="Réduire"], ' +
            '.fi-sidebar-open-button, ' +
            '.fi-sidebar-close-button'
        );
        
        buttons.forEach(button => {
            button.style.display = 'none';
            button.style.visibility = 'hidden';
            button.style.opacity = '0';
            button.style.width = '0';
            button.style.height = '0';
            button.style.padding = '0';
            button.style.margin = '0';
            button.style.pointerEvents = 'none';
        });
    }
    
    // Masquer immédiatement
    hideSidebarButtons();
    
    // Observer les changements du DOM pour masquer les boutons qui apparaissent plus tard
    const observer = new MutationObserver(function(mutations) {
        hideSidebarButtons();
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Masquer aussi après un délai pour être sûr
    setTimeout(hideSidebarButtons, 1000);
    setTimeout(hideSidebarButtons, 2000);
});
</script>
