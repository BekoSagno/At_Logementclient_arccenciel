<script>
    (function() {
        'use strict';
        
        let initializedInputs = new Set();
        let conversionAttempts = new Map();
        
        function findScheduledAtInput() {
            const allInputs = document.querySelectorAll('input[type="text"], input[type="datetime-local"], input');
            
            for (let input of allInputs) {
                const name = input.name || '';
                const id = input.id || '';
                const wireModel = input.getAttribute('wire:model') || input.getAttribute('x-model') || '';
                const className = input.className || '';
                const placeholder = input.placeholder || '';
                
                if (name.includes('scheduled_at') || 
                    id.includes('scheduled_at') || 
                    wireModel.includes('scheduled_at') || 
                    className.includes('scheduled') ||
                    placeholder.includes('Date et Heure')) {
                    return input;
                }
                
                // Vérifier par le label parent
                const label = input.closest('label') || 
                             input.parentElement?.querySelector('label') ||
                             document.querySelector(`label[for="${id}"]`);
                if (label && (label.textContent.includes('Date et Heure') || 
                             label.textContent.includes('Programmer') ||
                             label.textContent.includes('publication'))) {
                    return input;
                }
            }
            
            return null;
        }

        function convertTimeInputsToSelects() {
            // Chercher tous les calendriers Flatpickr ouverts
            const allCalendars = document.querySelectorAll('.flatpickr-calendar.open, .flatpickr-calendar:not([style*="display: none"])');
            
            allCalendars.forEach(calendar => {
                const timeContainer = calendar.querySelector('.flatpickr-time');
                if (!timeContainer) return;
                
                timeContainer.style.display = 'flex';
                timeContainer.style.visibility = 'visible';
                timeContainer.style.alignItems = 'center';
                timeContainer.style.justifyContent = 'center';
                timeContainer.style.gap = '10px';
                timeContainer.style.padding = '15px';
                
                // Chercher tous les inputs numériques dans le time container
                const numberInputs = timeContainer.querySelectorAll('input[type="number"], input.flatpickr-hour, input.flatpickr-minute');
                
                if (numberInputs.length >= 2) {
                    const hourInput = numberInputs[0];
                    const minuteInput = numberInputs[1];
                    
                    // Vérifier si déjà convertis
                    if (hourInput.tagName === 'SELECT' && minuteInput.tagName === 'SELECT') {
                        return;
                    }
                    
                    // Obtenir les valeurs actuelles
                    const currentHour = parseInt(hourInput.value) || 10;
                    const currentMinute = parseInt(minuteInput.value) || 40;
                    
                    // Créer le sélecteur d'heures (0-23)
                    const hourSelect = document.createElement('select');
                    hourSelect.className = 'flatpickr-hour custom-time-select';
                    hourSelect.style.cssText = 'width: 90px; padding: 10px 30px 10px 12px; border: 2px solid #D1D5DB; border-radius: 6px; font-size: 15px; font-weight: 500; background: white; cursor: pointer; transition: all 0.2s; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' viewBox=\'0 0 12 12\'%3E%3Cpath fill=\'%23374151\' d=\'M6 9L1 4h10z\'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 12px;';
                    
                    for (let h = 0; h < 24; h++) {
                        const option = document.createElement('option');
                        option.value = h;
                        option.textContent = String(h).padStart(2, '0') + 'h';
                        if (h === currentHour) option.selected = true;
                        hourSelect.appendChild(option);
                    }
                    
                    // Créer le sélecteur de minutes (0-59)
                    const minuteSelect = document.createElement('select');
                    minuteSelect.className = 'flatpickr-minute custom-time-select';
                    minuteSelect.style.cssText = 'width: 90px; padding: 10px 30px 10px 12px; border: 2px solid #D1D5DB; border-radius: 6px; font-size: 15px; font-weight: 500; background: white; cursor: pointer; transition: all 0.2s; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' viewBox=\'0 0 12 12\'%3E%3Cpath fill=\'%23374151\' d=\'M6 9L1 4h10z\'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 12px;';
                    
                    for (let m = 0; m < 60; m++) {
                        const option = document.createElement('option');
                        option.value = m;
                        option.textContent = String(m).padStart(2, '0') + 'min';
                        if (m === currentMinute) option.selected = true;
                        minuteSelect.appendChild(option);
                    }
                    
                    // Ajouter les événements hover/focus
                    [hourSelect, minuteSelect].forEach(select => {
                        select.addEventListener('mouseenter', function() {
                            this.style.borderColor = '#F59E0B';
                            this.style.boxShadow = '0 2px 4px rgba(245, 158, 11, 0.2)';
                        });
                        select.addEventListener('mouseleave', function() {
                            this.style.borderColor = '#D1D5DB';
                            this.style.boxShadow = 'none';
                        });
                        select.addEventListener('focus', function() {
                            this.style.borderColor = '#F59E0B';
                            this.style.boxShadow = '0 0 0 3px rgba(245, 158, 11, 0.1)';
                        });
                        select.addEventListener('blur', function() {
                            this.style.borderColor = '#D1D5DB';
                            this.style.boxShadow = 'none';
                        });
                    });
                    
                    // Trouver l'instance Flatpickr associée
                    let fp = null;
                    const allInputs = document.querySelectorAll('input');
                    for (let input of allInputs) {
                        if (input._flatpickr && input._flatpickr.calendarContainer === calendar) {
                            fp = input._flatpickr;
                            break;
                        }
                    }
                    
                    // Remplacer les inputs
                    try {
                        // Supprimer les flèches et autres éléments
                        const arrows = timeContainer.querySelectorAll('.arrowUp, .arrowDown, .flatpickr-arrow, [class*="arrow"]');
                        arrows.forEach(arrow => arrow.remove());
                        
                        // Remplacer les inputs
                        hourInput.replaceWith(hourSelect);
                        minuteInput.replaceWith(minuteSelect);
                        
                        // Ajouter un séparateur visuel
                        const separator = document.createElement('span');
                        separator.textContent = ':';
                        separator.style.cssText = 'font-size: 18px; font-weight: bold; color: #6B7280; margin: 0 5px;';
                        hourSelect.insertAdjacentElement('afterend', separator);
                        
                        // Ajouter les écouteurs d'événements
                        hourSelect.addEventListener('change', function() {
                            updateTimeFromSelects(fp, hourSelect, minuteSelect);
                        });
                        
                        minuteSelect.addEventListener('change', function() {
                            updateTimeFromSelects(fp, hourSelect, minuteSelect);
                        });
                        
                        // Mettre à jour immédiatement
                        if (fp) {
                            updateTimeFromSelects(fp, hourSelect, minuteSelect);
                        }
                        
                        console.log('✅ Sélecteurs d\'heure convertis avec succès');
                    } catch (error) {
                        console.error('❌ Erreur lors de la conversion:', error);
                    }
                }
            });
        }

        function updateTimeFromSelects(fp, hourSelect, minuteSelect) {
            if (!fp) return;
            
            const hour = parseInt(hourSelect.value) || 0;
            const minute = parseInt(minuteSelect.value) || 0;
            
            let selectedDate;
            if (fp.selectedDates && fp.selectedDates.length > 0) {
                selectedDate = fp.selectedDates[0];
            } else {
                selectedDate = new Date();
            }
            
            selectedDate.setHours(hour);
            selectedDate.setMinutes(minute);
            selectedDate.setSeconds(0);
            selectedDate.setMilliseconds(0);
            
            fp.setDate(selectedDate, false);
            
            // Synchroniser avec Livewire
            if (fp.input && window.Livewire) {
                const formattedDate = fp.formatDate(selectedDate, fp.config.dateFormat || 'Y-m-d H:i');
                if (fp.input.value !== formattedDate) {
                    fp.input.value = formattedDate;
                    fp.input.dispatchEvent(new Event('input', { bubbles: true }));
                    fp.input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }

        function forceTimePickerActivation() {
            if (typeof flatpickr === 'undefined') {
                setTimeout(forceTimePickerActivation, 200);
                return;
            }

            const input = findScheduledAtInput();
            
            if (!input) {
                setTimeout(forceTimePickerActivation, 500);
                return;
            }
            
            const inputId = input.id || input.name || Math.random().toString();
            if (initializedInputs.has(inputId)) {
                // Toujours essayer de convertir même si déjà initialisé
                convertTimeInputsToSelects();
                return;
            }

            const checkFlatpickr = () => {
                let fp = input._flatpickr;
                
                if (!fp) {
                    setTimeout(checkFlatpickr, 300);
                    return;
                }
                
                initializedInputs.add(inputId);
                
                // Forcer l'activation du time picker
                if (!fp.config.enableTime) {
                    fp.config.enableTime = true;
                    fp.config.time_24hr = true;
                    fp.redraw();
                }
                
                // Écouter l'ouverture du calendrier
                const originalOpen = fp.open;
                fp.open = function() {
                    const result = originalOpen.apply(this, arguments);
                    setTimeout(() => {
                        convertTimeInputsToSelects();
                    }, 300);
                    return result;
                };
                
                // Convertir immédiatement si le calendrier est déjà ouvert
                if (fp.isOpen || fp.calendarContainer?.classList.contains('open')) {
                    setTimeout(() => {
                        convertTimeInputsToSelects();
                    }, 300);
                }
            };
            
            setTimeout(checkFlatpickr, 100);
            setTimeout(checkFlatpickr, 500);
            setTimeout(checkFlatpickr, 1000);
        }

        // Exécuter immédiatement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                forceTimePickerActivation();
                setInterval(convertTimeInputsToSelects, 1000);
            });
        } else {
            forceTimePickerActivation();
            setInterval(convertTimeInputsToSelects, 1000);
        }

        // Observer les changements du DOM
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        // Vérifier si un calendrier Flatpickr a été ajouté
                        if (node.classList && node.classList.contains('flatpickr-calendar')) {
                            setTimeout(() => {
                                convertTimeInputsToSelects();
                            }, 300);
                        }
                        
                        // Vérifier si un time container a été ajouté
                        const timeContainer = node.querySelector ? node.querySelector('.flatpickr-time') : null;
                        if (timeContainer) {
                            setTimeout(() => {
                                convertTimeInputsToSelects();
                            }, 300);
                        }
                    }
                });
            });
            
            convertTimeInputsToSelects();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'style']
        });

        // Écouter les événements de clic sur les inputs
        document.addEventListener('click', function(e) {
            const input = e.target;
            if (input && (input.tagName === 'INPUT' || input.closest('input'))) {
                const targetInput = input.tagName === 'INPUT' ? input : input.closest('input');
                if (targetInput._flatpickr) {
                    setTimeout(() => {
                        convertTimeInputsToSelects();
                    }, 500);
                }
            }
        }, true);

        // Écouter les événements Livewire
        if (window.Livewire) {
            document.addEventListener('livewire:load', function() {
                forceTimePickerActivation();
                setInterval(convertTimeInputsToSelects, 1000);
            });
            document.addEventListener('livewire:update', function() {
                setTimeout(() => {
                    forceTimePickerActivation();
                    convertTimeInputsToSelects();
                }, 200);
            });
        }

        // Ré-exécuter périodiquement
        setTimeout(forceTimePickerActivation, 500);
        setTimeout(forceTimePickerActivation, 1000);
        setTimeout(forceTimePickerActivation, 2000);
    })();
</script>
