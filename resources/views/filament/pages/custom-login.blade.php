<x-filament-panels::page.simple>
    <form wire:submit="authenticate" class="login-form-container">
        {{ $this->form }}
        
        <div class="fi-fo-field-wrp login-button-field" id="login-button-container">
            <button type="submit" class="btn-full-width">
                Se connecter
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trouver le checkbox "Se souvenir"
            const rememberCheckbox = document.querySelector('input[type="checkbox"][wire\\:model="remember"], input[type="checkbox"][name="remember"]');
            if (rememberCheckbox) {
                const checkboxWrapper = rememberCheckbox.closest('.fi-fo-field-wrp, .fi-checkbox-wrapper, label');
                const buttonContainer = document.getElementById('login-button-container');
                
                if (checkboxWrapper && buttonContainer) {
                    // Déplacer le bouton juste après le checkbox
                    checkboxWrapper.parentNode.insertBefore(buttonContainer, checkboxWrapper.nextSibling);
                }
            }
        });
    </script>

    <style>
        /* Container du formulaire */
        .login-form-container {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Tous les champs au même niveau */
        .fi-fo-field-wrp,
        .login-button-field {
            width: 100% !important;
            max-width: 100% !important;
            margin-bottom: 1rem !important;
            display: block !important;
        }

        .login-button-field {
            margin-top: 0.75rem !important;
            margin-bottom: 0 !important;
        }

        /* Style du bouton - même largeur que les autres champs */
        .btn-full-width {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            display: block !important;
            background-color: #f59e0b !important;
            color: white !important;
            padding: 0.75rem 1rem !important;
            font-size: 1rem !important;
            font-weight: bold !important;
            border-radius: 0.5rem !important;
            text-align: center !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            transition: background 0.2s !important;
            box-sizing: border-box !important;
        }

        .btn-full-width:hover {
            background-color: #d97706 !important;
        }

        /* Assure que le texte ne soit pas coupé */
        .btn-full-width {
            white-space: nowrap !important;
            overflow: visible !important;
        }

        /* Supprime les limitations de largeur des parents Filament */
        .fi-simple-page form,
        .fi-simple-page-main, 
        .fi-simple-main-form {
            max-width: 100% !important;
            width: 100% !important;
        }
    </style>
</x-filament-panels::page.simple>