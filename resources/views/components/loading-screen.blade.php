<div id="loading-screen" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background-color: white;">
    <div style="position: relative; width: 256px; height: 256px;">
        <!-- Logo au centre -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 10; display: flex; align-items: center; justify-content: center;">
            <img src="{{ asset('images/logo.jpg') }}" alt="AT.Logement" style="width: 128px; height: 128px; object-fit: contain;">
        </div>
        
        <!-- Première courbe (orange/doré) - tourne dans le sens antihoraire -->
        <svg class="loading-circle-1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" width="256" height="256" viewBox="0 0 256 256">
            <circle 
                cx="128" 
                cy="128" 
                r="110" 
                fill="none" 
                stroke="#FF8C00" 
                stroke-width="4" 
                stroke-dasharray="150 100"
                stroke-linecap="round"
                transform="rotate(-90 128 128)"
            />
        </svg>
        
        <!-- Deuxième courbe (gris foncé) - tourne dans le sens antihoraire -->
        <svg class="loading-circle-2" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" width="256" height="256" viewBox="0 0 256 256">
            <circle 
                cx="128" 
                cy="128" 
                r="125" 
                fill="none" 
                stroke="#333333" 
                stroke-width="4" 
                stroke-dasharray="130 120"
                stroke-linecap="round"
                transform="rotate(90 128 128)"
            />
        </svg>
    </div>
</div>

<style>
    /* Animation de rotation lente dans le sens antihoraire */
    @keyframes spin-counterclockwise {
        from {
            transform: rotate(360deg);
        }
        to {
            transform: rotate(0deg);
        }
    }
    
    /* Animation de rotation lente inverse (sens antihoraire) */
    @keyframes spin-counterclockwise-reverse {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(-360deg);
        }
    }
    
    .loading-circle-1 {
        animation: spin-counterclockwise 3s linear infinite;
    }
    
    .loading-circle-2 {
        animation: spin-counterclockwise-reverse 4s linear infinite;
    }
    
    #loading-screen {
        transition: opacity 0.5s ease-out;
    }
</style>

