<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const sidebar = document.querySelector('.fi-sidebar');
        const sidebarNav = document.querySelector('.fi-sidebar-nav');
        
        if (sidebar && sidebarNav && !document.getElementById('sidebar-scroll-up')) {
            // Créer le conteneur pour les boutons
            const buttonsContainer = document.createElement('div');
            buttonsContainer.className = 'sidebar-scroll-buttons-container';
            buttonsContainer.style.cssText = 'position: relative; width: 100%; height: 100%;';
            
            // Bouton scroll haut
            const scrollUpBtn = document.createElement('button');
            scrollUpBtn.id = 'sidebar-scroll-up';
            scrollUpBtn.className = 'sidebar-scroll-btn sidebar-scroll-btn-up';
            scrollUpBtn.innerHTML = '<svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>';
            scrollUpBtn.title = 'Défiler vers le haut';
            
            // Bouton scroll bas
            const scrollDownBtn = document.createElement('button');
            scrollDownBtn.id = 'sidebar-scroll-down';
            scrollDownBtn.className = 'sidebar-scroll-btn sidebar-scroll-btn-down';
            scrollDownBtn.innerHTML = '<svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
            scrollDownBtn.title = 'Défiler vers le bas';
            
            // Ajouter les boutons à la sidebar
            sidebar.style.position = 'relative';
            sidebar.appendChild(scrollUpBtn);
            sidebar.appendChild(scrollDownBtn);
            
            // Fonction pour vérifier si on peut scroller
            function updateScrollButtons() {
                const canScrollUp = sidebarNav.scrollTop > 0;
                const canScrollDown = sidebarNav.scrollTop < (sidebarNav.scrollHeight - sidebarNav.clientHeight - 1);
                
                scrollUpBtn.classList.toggle('hidden', !canScrollUp);
                scrollDownBtn.classList.toggle('hidden', !canScrollDown);
            }
            
            // Fonction de scroll
            scrollUpBtn.addEventListener('click', function() {
                sidebarNav.scrollBy({ top: -100, behavior: 'smooth' });
            });
            
            scrollDownBtn.addEventListener('click', function() {
                sidebarNav.scrollBy({ top: 100, behavior: 'smooth' });
            });
            
            // Mettre à jour les boutons lors du scroll
            sidebarNav.addEventListener('scroll', updateScrollButtons);
            
            // Mettre à jour les boutons au chargement et lors des changements
            updateScrollButtons();
            
            // Observer les changements dans la sidebar pour mettre à jour les boutons
            const observer = new MutationObserver(function() {
                setTimeout(updateScrollButtons, 100);
            });
            
            observer.observe(sidebarNav, { childList: true, subtree: true });
            
            // Mettre à jour lors du redimensionnement
            window.addEventListener('resize', updateScrollButtons);
        }
    }, 500);
});
</script>
