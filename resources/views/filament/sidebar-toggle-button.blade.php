<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attendre que la sidebar soit chargée
    setTimeout(function() {
        const sidebar = document.querySelector('.fi-sidebar');
        const sidebarNav = document.querySelector('.fi-sidebar-nav');
        
        if (sidebar && sidebarNav && !document.getElementById('custom-sidebar-toggle')) {
            // Créer le bouton toggle
            const toggleContainer = document.createElement('div');
            toggleContainer.className = 'sidebar-toggle-container';
            toggleContainer.style.cssText = 'padding: 0.75rem !important; border-bottom: 1px solid rgba(229, 231, 235, 0.3) !important; margin-bottom: 0.5rem !important; background: #fff !important;';
            
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'sidebar-toggle-btn';
            toggleBtn.id = 'custom-sidebar-toggle';
            toggleBtn.style.cssText = 'width: 100% !important; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important; border: 2px solid #f59e0b !important; border-radius: 0.5rem !important; padding: 0.875rem 1.25rem !important; color: #000 !important; font-weight: 700 !important; font-size: 0.9375rem !important; box-shadow: 0 4px 8px rgba(251, 191, 36, 0.4) !important; transition: all 0.2s ease !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: 0.625rem !important; margin-bottom: 0.5rem !important; opacity: 1 !important; visibility: visible !important;';
            
            const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            icon.setAttribute('style', 'width: 1.25rem; height: 1.25rem;');
            icon.setAttribute('fill', 'none');
            icon.setAttribute('stroke', 'currentColor');
            icon.setAttribute('viewBox', '0 0 24 24');
            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('stroke-linecap', 'round');
            path.setAttribute('stroke-linejoin', 'round');
            path.setAttribute('stroke-width', '2');
            path.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            icon.appendChild(path);
            
            const text = document.createElement('span');
            text.id = 'sidebar-toggle-text';
            text.textContent = 'Réduire';
            
            toggleBtn.appendChild(icon);
            toggleBtn.appendChild(text);
            toggleContainer.appendChild(toggleBtn);
            
            // Insérer au début de la sidebar
            sidebarNav.insertBefore(toggleContainer, sidebarNav.firstChild);
            
            // Fonction toggle
            toggleBtn.addEventListener('click', function() {
                const expandBtn = document.querySelector('button[aria-label*="Agrandir"]');
                const collapseBtn = document.querySelector('button[aria-label*="Réduire"]');
                
                const isCollapsed = sidebar.classList.contains('fi-sidebar-collapsed');
                
                if (isCollapsed && expandBtn) {
                    expandBtn.click();
                    text.textContent = 'Réduire';
                } else if (!isCollapsed && collapseBtn) {
                    collapseBtn.click();
                    text.textContent = 'Agrandir';
                }
            });
            
            // Observer les changements
            const observer = new MutationObserver(function() {
                const isCollapsed = sidebar.classList.contains('fi-sidebar-collapsed');
                text.textContent = isCollapsed ? 'Agrandir' : 'Réduire';
            });
            
            observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
            
            // Effet hover
            toggleBtn.addEventListener('mouseenter', function() {
                this.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            toggleBtn.addEventListener('mouseleave', function() {
                this.style.background = 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)';
                this.style.transform = 'translateY(0) scale(1)';
            });
        }
    }, 500);
});
</script>
