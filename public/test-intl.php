<?php
echo "Extension intl chargée : " . (extension_loaded('intl') ? 'OUI' : 'NON') . PHP_EOL;
echo "PHP Version : " . phpversion() . PHP_EOL;
echo "php.ini utilisé : " . php_ini_loaded_file() . PHP_EOL;
phpinfo();
