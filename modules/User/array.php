<?php
/**
 * array.php
 *
 * User array
 *
 * @version 1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2013 Nuked-Klan (Registred Trademark)
 */
$arrayListUsers     =   array(
                        'private_mail' => 'mail', 'website' => 'url', 'password' => 'pass', 'pseudo' => 'pseudo', 'avatar' => 'avatar', 'country' => 'country'
                        );
$arrayListMaterial  =   array(
                            'first_name' => 'prenom', 'date_of_birth' => 'age', 'gender' => 'sexe', 'ville' => 'ville', 'motherboard' => 'motherboard', 'cpu' => 'cpu', 'ram' => 'ram', 'gpu' => 'video', 'resolution'  => 'resolution', 'soundcard' => 'son', 'screen' => 'ecran', 'mouse' => 'souris', 'keyboard' => 'clavier', 'connection' => 'connexion', 'os' => 'system'
                        );
$arraySystemOs      =   array(
                        'Windows 8 32 Bits', 'Windows 8 64 Bits', 'Windows 7 32 Bits', 'Windows 7 64 Bits', 'Windows Vista 32 Bits', 'Windows Vista 64 Bits', 'Windows XP 32 Bits', 'Linux', 'Mac OS X', 'Android', 'Apple', 'Autre'
                        );
$arrayConnection    =   array(
                        'Modem 56K', 'Modem 128K', 'ADSL 128K', 'ADSL 512K', 'ADSL 1024K', 'ADSL 2048K', 'ADSL 3M', 'ADSL 4M', 'ADSL 8M', 'ADSL 20M +', 'Cable 128K', 'Cable 512K', 'Cable 1024K', 'Cable 2048K', 'Cable 8M', 'Cable 20M +', 'T1 1,5M', 'T2 6M', 'T3 45M', 'Fiber 50M', 'Fiber 100M', 'Autre'
                        );
$arrayListReso      =   array(
                        '300*200', '320*480', '360*240', '480*320', '720*480', '768*1024', '768*1366', '800*600', '800*1280', '900*1440', '900*1600', '1024*600', '1024*768', '1024*1280', '1050*1680', '1080*1920', '1280*720', '1280*768', '1280*800', '1280*1024', '1360*768', '1440*900', '1600*900', '1680*1050', '1920*1080', '1920*1200', '1920*1440', '2048*1536', '2276*1707', '2560*1440', '2560*1920',
                        );
$arrayListRam       =   array(
                        '512 Mo', '1 GB', '1.5 GB', '2 GB', '2.5 GB', '3 GB', '3.5 GB', '4 GB', '6 GB', '8 GB', '10 GB', '12 GB', '16 GB', '18 GB', '32 GB', '64 GB',
                        );
// -> array list flags
$folderFlags   = 'assets/images/flags';
$folderFlags   = opendir($folderFlags) or die(ERROR_DIRECTORY);
$arrayFlags    = array();
while($TpmFolderTheme = readdir($folderFlags)) {
    if($TpmFolderTheme != '.' && $TpmFolderTheme != '..'&& $folderFlags != "index.html" && $folderFlags != "Thumbs.db") {
        $arrayFlags[] = $TpmFolderTheme;
    }
}
closedir($folderFlags);
if (!empty($arrayFlags)) {
    sort($arrayFlags);
    foreach ($arrayFlags as $key) {
        $arrayTmpFlags = explode ('.', $key);
        $arrayNameFlags[$arrayTmpFlags[0]] = $key;
    }
}
?>
