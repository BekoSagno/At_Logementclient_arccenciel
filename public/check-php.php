<?php
header('Content-Type: text/plain; charset=utf-8');
echo "=== Vérification PHP ===\n\n";
echo "PHP Version: " . phpversion() . "\n";
echo "php.ini utilisé: " . php_ini_loaded_file() . "\n";
echo "Extension dir: " . ini_get('extension_dir') . "\n\n";

echo "=== Extensions chargées ===\n";
$extensions = get_loaded_extensions();
sort($extensions);
echo "Total: " . count($extensions) . "\n";
echo "intl chargée: " . (extension_loaded('intl') ? 'OUI ✅' : 'NON ❌') . "\n\n";

if (extension_loaded('intl')) {
    echo "=== Informations intl ===\n";
    echo "Version intl: " . INTL_ICU_VERSION . "\n";
    echo "ICU Version: " . INTL_ICU_DATA_VERSION . "\n";
} else {
    echo "=== Tentative de diagnostic ===\n";
    $dllPath = ini_get('extension_dir') . DIRECTORY_SEPARATOR . 'php_intl.dll';
    echo "Chemin DLL attendu: $dllPath\n";
    echo "DLL existe: " . (file_exists($dllPath) ? 'OUI' : 'NON') . "\n";
    
    $xamppPath = 'C:\xampp\php\ext\php_intl.dll';
    echo "Chemin XAMPP: $xamppPath\n";
    echo "DLL XAMPP existe: " . (file_exists($xamppPath) ? 'OUI' : 'NON') . "\n";
}
