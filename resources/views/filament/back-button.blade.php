@php
    // Ne pas afficher le bouton de retour sur le dashboard
    $currentPath = request()->path();
    $isDashboard = $currentPath === 'admin' || $currentPath === 'admin/';
    
    // Ne pas afficher sur la page de login
    $isLogin = str_contains($currentPath, 'admin/login');
@endphp

@if(!$isDashboard && !$isLogin)
<div class="fi-back-button-container" style="position: fixed; top: 80px; left: 20px; z-index: 1000;">
    <button 
        onclick="window.history.back()" 
        class="fi-back-button"
        title="Retour en arrière"
        style="
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(243, 164, 62, 0.15) 0%, rgba(249, 115, 22, 0.1) 100%);
            border: 2px solid rgba(243, 164, 62, 0.3);
            color: #f97316;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(243, 164, 62, 0.2);
        "
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(243, 164, 62, 0.4)'; this.style.borderColor='rgba(243, 164, 62, 0.5)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(243, 164, 62, 0.2)'; this.style.borderColor='rgba(243, 164, 62, 0.3)';"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
    </button>
</div>

<style>
    @media (max-width: 768px) {
        .fi-back-button-container {
            top: 70px !important;
            left: 10px !important;
        }
        
        .fi-back-button {
            width: 40px !important;
            height: 40px !important;
        }
        
        .fi-back-button svg {
            width: 20px !important;
            height: 20px !important;
        }
    }
</style>
@endif
