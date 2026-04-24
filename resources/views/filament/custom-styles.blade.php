<style>
    /* ============================================================
       1. STRUCTURE GÉNÉRALE & CENTRAGE (Arccencien Expert UI)
       ============================================================ */
    body, .fi-body {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
        overflow-x: hidden !important;
    }

    .fi-main {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        width: 100% !important;
    }

    .fi-main-content, .fi-page-content {
        max-width: 1200px !important;
        width: 100% !important;
        margin: 0 auto !important;
    }

    /* Masquer la sidebar pour l'aspect épuré demandé */
    .fi-sidebar { display: none !important; }

    /* ============================================================
       2. TOGGLES (INTERRUPTEURS) - FORCE ORANGE
       ============================================================ */
    /* État activé (ON) */
    .fi-fo-toggle button[aria-checked="true"],
    button[role="switch"][aria-checked="true"] {
        background-color: #f59e0b !important;
        background: #f59e0b !important;
    }

    /* État désactivé (OFF) - Bordure orange pour visibilité */
    .fi-fo-toggle button[aria-checked="false"],
    button[role="switch"][aria-checked="false"] {
        border: 2px solid #f59e0b !important;
        background-color: rgba(245, 158, 11, 0.05) !important;
    }

    /* Le petit rond blanc à l'intérieur */
    .fi-fo-toggle button span, button[role="switch"] span {
        background-color: white !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2) !important;
    }

    /* ============================================================
       3. CHECKBOXES (CASES À COCHER) - FORCE ORANGE
       ============================================================ */
    /* Case cochée */
    input[type="checkbox"]:checked,
    .fi-checkbox-input:checked,
    .fi-fo-checkbox[aria-checked="true"] {
        background-color: #f59e0b !important;
        border-color: #d97706 !important;
        /* Ajout de la coche blanche en SVG */
        background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e") !important;
        background-size: 100% 100% !important;
    }

    /* Case non cochée - Bordure visible */
    input[type="checkbox"], .fi-checkbox-input {
        border: 2px solid #d1d5db !important;
        border-radius: 4px !important;
    }

    /* ============================================================
       4. BOUTON DE CONNEXION (LOGIN) - AGRANDI ET COLLÉ
       ============================================================ */
    .fi-simple-page form {
        display: flex !important;
        flex-direction: column !important;
        gap: 1rem !important;
    }

    /* Bouton Se connecter */
    button[type="submit"].fi-btn, 
    .fi-btn-primary {
        width: 100% !important;
        height: 50px !important;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        color: white !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        border-radius: 8px !important;
        border: none !important;
        cursor: pointer !important;
        box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3) !important;
        margin-top: 10px !important;
    }

    button[type="submit"]:hover {
        filter: brightness(1.1) !important;
        transform: translateY(-1px) !important;
    }

    /* ============================================================
       5. FIX DES MODALES (COMPACTES)
       ============================================================ */
    .fi-modal-content {
        max-width: 400px !important;
        border-radius: 12px !important;
        border: 2px solid rgba(245, 158, 11, 0.2) !important;
    }
</style>