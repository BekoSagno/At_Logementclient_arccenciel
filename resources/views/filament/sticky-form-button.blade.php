<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour rendre le bouton sticky et appliquer les styles orange
        function makeFormButtonSticky() {
            // Chercher le formulaire dans la page
            const forms = document.querySelectorAll('.fi-page form, .fi-main-content form');
            
            forms.forEach(form => {
                // Chercher le conteneur des boutons d'action
                const actionContainers = form.querySelectorAll('[data-slot="form-actions"], .fi-form-actions, .fi-fo-actions, .fi-ac-actions');
                
                if (actionContainers.length > 0) {
                    actionContainers.forEach(container => {
                        container.style.position = 'sticky';
                        container.style.bottom = '0';
                        container.style.background = 'white';
                        container.style.padding = '1rem 0';
                        container.style.marginTop = '1.5rem';
                        container.style.borderTop = '1px solid rgba(229, 231, 235, 0.5)';
                        container.style.zIndex = '10';
                        container.style.boxShadow = '0 -2px 8px rgba(0, 0, 0, 0.05)';
                    });
                } else {
                    // Si aucun conteneur trouvé, chercher le dernier élément avec des boutons
                    const lastElement = form.lastElementChild;
                    if (lastElement && (lastElement.querySelector('button[type="submit"]') || lastElement.querySelector('button[type="button"]'))) {
                        lastElement.style.position = 'sticky';
                        lastElement.style.bottom = '0';
                        lastElement.style.background = 'white';
                        lastElement.style.padding = '1rem 0';
                        lastElement.style.marginTop = '1.5rem';
                        lastElement.style.borderTop = '1px solid rgba(229, 231, 235, 0.5)';
                        lastElement.style.zIndex = '10';
                        lastElement.style.boxShadow = '0 -2px 8px rgba(0, 0, 0, 0.05)';
                    }
                }
                
                // Appliquer les styles orange aux boutons de soumission
                const submitButtons = form.querySelectorAll('button[type="submit"]');
                submitButtons.forEach(button => {
                    if (!button.classList.contains('fi-btn-secondary') && !button.classList.contains('fi-btn-danger')) {
                        button.style.backgroundColor = '#F59E0B';
                        button.style.color = 'white';
                        button.style.borderColor = '#F59E0B';
                        button.style.boxShadow = '0 2px 4px rgba(245, 158, 11, 0.3)';
                        
                        // Ajouter les événements hover
                        button.addEventListener('mouseenter', function() {
                            this.style.backgroundColor = '#D97706';
                            this.style.borderColor = '#D97706';
                            this.style.boxShadow = '0 4px 8px rgba(245, 158, 11, 0.4)';
                            this.style.transform = 'translateY(-1px)';
                        });
                        
                        button.addEventListener('mouseleave', function() {
                            this.style.backgroundColor = '#F59E0B';
                            this.style.borderColor = '#F59E0B';
                            this.style.boxShadow = '0 2px 4px rgba(245, 158, 11, 0.3)';
                            this.style.transform = 'translateY(0)';
                        });
                    }
                });
                
                // Ajouter un padding en bas du formulaire pour éviter que le bouton cache le contenu
                form.style.paddingBottom = '5rem';
                form.style.overflowX = 'hidden';
                form.style.maxWidth = '100%';
            });
        }
        
        // Fonction pour corriger le DateTimePicker et forcer l'activation de l'heure
        function fixDateTimePicker() {
            // Attendre que Flatpickr soit chargé
            if (typeof flatpickr !== 'undefined') {
                // Trouver tous les inputs de date/heure pour scheduled_at
                const dateInputs = document.querySelectorAll('input[type="text"], input.scheduled-datetime-picker, input[class*="scheduled"]');
                dateInputs.forEach(input => {
                    // Vérifier si c'est le champ scheduled_at
                    const name = input.name || input.id || '';
                    const wireModel = input.getAttribute('wire:model') || input.getAttribute('x-model') || '';
                    const className = input.className || '';
                    
                    if (name.includes('scheduled_at') || wireModel.includes('scheduled_at') || className.includes('scheduled')) {
                        // Attendre que Filament initialise Flatpickr
                        const checkAndFix = () => {
                            let fp = input._flatpickr;
                            
                            if (fp) {
                                // Forcer l'activation du time picker
                                if (!fp.config.enableTime) {
                                    fp.config.enableTime = true;
                                    fp.config.time_24hr = true;
                                    fp.config.dateFormat = 'Y-m-d H:i';
                                    fp.config.defaultHour = 10;
                                    fp.config.defaultMinute = 40;
                                    fp.config.minDate = 'today';
                                    fp.redraw();
                                } else {
                                    // S'assurer que les valeurs par défaut sont correctes
                                    if (!fp.selectedDates || fp.selectedDates.length === 0) {
                                        const now = new Date();
                                        now.setHours(10);
                                        now.setMinutes(40);
                                        fp.setDate(now, false);
                                    }
                                }
                                
                                // S'assurer que le time picker est visible
                                if (fp.calendarContainer) {
                                    const timeContainer = fp.calendarContainer.querySelector('.flatpickr-time');
                                    if (timeContainer) {
                                        timeContainer.style.display = 'block';
                                    }
                                }
                            }
                        };
                        
                        // Essayer plusieurs fois avec des délais différents
                        setTimeout(checkAndFix, 100);
                        setTimeout(checkAndFix, 500);
                        setTimeout(checkAndFix, 1000);
                        setTimeout(checkAndFix, 2000);
                    }
                });
            }
        }
        
        // Exécuter immédiatement
        makeFormButtonSticky();
        fixDateTimePicker();
        
        // Ré-exécuter après un court délai pour les composants Livewire qui se chargent après
        setTimeout(() => {
            makeFormButtonSticky();
            fixDateTimePicker();
        }, 500);
        
        setTimeout(() => {
            makeFormButtonSticky();
            fixDateTimePicker();
        }, 1000);
        
        setTimeout(() => {
            makeFormButtonSticky();
            fixDateTimePicker();
        }, 2000);
        
        // Observer les changements du DOM pour les composants Livewire
        const observer = new MutationObserver(function(mutations) {
            makeFormButtonSticky();
            fixDateTimePicker();
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
</script>
