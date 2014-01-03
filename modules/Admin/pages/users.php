<?php

/**
 Delete 'ajout' de colonne fonctionne pas
 Ajout utilisateur non fonctionnel
 Pref general user manque le button
 Edition d'un membre fonctionne pas

*/
/**
 * Users.php
 *
 * Users Management
 *
 * @version 1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2013 Nuked-Klan (Registred Trademark)
 */

if (!defined("INDEX_CHECK")) exit('You can\'t run this file alone.');

// Inclusion du layout de l'administration
require_once 'modules/Admin/views/layout.php';

$hasAdminAccess = nkAccessAdmin('Users');

adminHeader();

if ($hasAdminAccess === true) {

        function main() {
        $arrayRequest = array('orderby', 'query', 'p');
        foreach($arrayRequest as $key){
            if(!array_key_exists($key, $_REQUEST)){
                $_REQUEST[$key] = '';
            }
        }
        // Le menu
        adminMenu($GLOBALS['arrayMenuMain']);
        // les colonnes
        $arrayColumns = array(NICK_USER, GROUPS_MAIN, DATE_USER, LAST_USER, ACTIONS);
        // les variables
        $tmpColumns   = null;
        foreach ($arrayColumns as $value) {
            $tmpColumns .= '<td class="center">
                                <strong>'.$value.'</strong>
                            </td>';
        }
        /**
        **@todo
        A Remplacer par jquery [tableaux dynamique] /* !!! Annulé tableaux normal
        **@todo
        */
        if ($_REQUEST['query'] != "") {
            $urlPage = "index.php?file=Admin&amp;page=user&amp;query=" . $_REQUEST['query'] . "&amp;orderby=" . $_REQUEST['orderby'];
            $and     = "AND (UT.pseudo LIKE '%" . $_REQUEST['query'] . "%')";
        }
        else {
            $urlPage = "index.php?file=Admin&amp;page=user&amp;orderby=" . $_REQUEST['orderby'];
            $and     = "";
        }
        if ($_REQUEST['orderby'] == "date") {
            $orderBy = "UT.date DESC";
        }
        elseif ($_REQUEST['orderby'] == "group") {
            $orderBy = "UT.nameGroup ASC, UT.date DESC";
        }
        elseif ($_REQUEST['orderby'] == "last_date") {
            $orderBy = "ST.date DESC";
        }
        elseif ($_REQUEST['orderby'] == "pseudo") {
            $orderBy = "UT.pseudo";
        }
        else {
            $orderBy = "UT.nameGroup DESC, UT.date DESC";
        }
        /**
        **@todo
        A Remplacer par jquery [tableaux dynamique] /* !!! Annulé tableaux normal
        **@todo
        */
        $dbuUserSelect = ' SELECT UT.id AS idUser, UT.pseudo, UT.date, UT.main_group, ST.date AS lastUsed, GR.nameGroup
                           FROM '.USERS_TABLE.' as UT
                           LEFT OUTER JOIN ' . SESSIONS_TABLE . ' as ST
                            ON UT.id = ST.user_id
                           LEFT OUTER JOIN ' . GROUPS_TABLE . ' as GR
                           ON UT.main_group = GR.id
                           WHERE UT.main_group != "3" ' . $and . '
                           ORDER BY "' . $orderBy . '" ';
        $dbeUserSelect =   mysql_query($dbuUserSelect);
?>
        <div class="widget">
            <div class="whead">
                <h6><?php echo USERS_MANAGEMENT; ?></h6>
                <div class="clear"></div>
            </div>

            <table class="tDefault">
                <thead>
                    <tr>
                        <?php echo $tmpColumns; ?>
                    </tr>
                </thead>
                <tbody>
<?php
        while ($users = mysql_fetch_assoc($dbeUserSelect)):

            $_CLEAN['pseudo'] = mysql_real_escape_string(stripslashes($users['pseudo']));

            $users['date']    = nkDate($users['date']);

            $users['lastUsed'] == '' ? $users['lastUsed'] = '-' : $users['lastUsed'] = nkDate($users['lastUsed']);
            // traduits le nom des groupes
            if (defined($users['nameGroup'])) {
                $translateNameGroup = constant($users['nameGroup']);
            }
            // Interdire de suprimer son propre compte
            if ($GLOBALS['user']['id'] == $users['idUser']) {
                $delPerm = '';
            }
            else {
                if(nkHasGod() === true) {
                    $delPerm = "
                    <a class=\"tablectrl_medium bDefault tipS nkIcons icon-delete\" href=\"javascript:delUser('".$_CLEAN['pseudo']. "', '" .$users['idUser']."')\" original-title=\"".DELETE."\"></a>";
                }
            }
            // les actions ( edit - del ect... )
            if(nkHasGod() === true) {
                $action = '<a class="tablectrl_medium bDefault tipS nkIcons icon-edit"  href="index.php?file=Admin&amp;page=users&amp;op=formUser&amp;id='.$users['idUser'].'" original-title="'.EDIT.'"></a>'.$delPerm.' ';
            }
            // les colonnes
            $arrayColumns = array($_CLEAN['pseudo'], $translateNameGroup, $users['date'], $users['lastUsed'], $action);
            // les variables
            $tmpDataColumns   = null;
            ?>
            <tr>
            <?php
            foreach ($arrayColumns as $value) {
                $tmpDataColumns .= '    <td class="center">
                                            <strong>'.$value.'</strong>
                                        </td>';
            }
            ?>
            </tr>
            <?php
            echo $tmpDataColumns;
        endwhile;
?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="body center">
                            <a class="buttonM bDefault" href="index.php?file=Admin"><?php echo BACK; ?></a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
<?php
    }

    function formUser() {

        $arrayRequest = array('id');
        foreach($arrayRequest as $key) {
            if(!array_key_exists($key, $_REQUEST))  {
                $_REQUEST[$key] = '';
            }
        }
        $dbsGame = ' SELECT id, name
                     FROM '.GAMES_TABLE.'
                     ORDER BY name ';
        $dbeGame = mysql_query($dbsGame);
        $directory = Array();
        $handle = @opendir("assets/images/flags");
        while (false !== ($f = readdir($handle))) {
            if ($f != ".." && $f != "." && $f != "index.html" && $f != "Thumbs.db") {
                $directory[] = $f;
            }
        }
        closedir($handle);
        sort ($directory);
        reset ($directory);

        // Variables
        $_CLEAN           = array();
        $arrayShowColoumn = array();
        $arrayTmp         = '';
        $tmpProfilGen     = '';
        $tmpInfosGen      = '';
        $tmpHardware      = '';

        // Liste des colonnes
        $dbsShowColumn   = ' SHOW COLUMNS FROM '.USERS_PROFILS.' ';
        $dbeShowColumn   =   mysql_query($dbsShowColumn);
        // liste des colonnes a ne pas recuperer
        $arrayBase = array('id', 'user_id');

        while($showColumn = mysql_fetch_assoc($dbeShowColumn)) {
            if (!in_array($showColumn['Field'], $arrayBase, true)) {
                $arrayShowColoumn[] = $showColumn['Field'];
            }
        }
        if (!empty($arrayShowColoumn)) {
            $tableShowColoumn = implode(", ", $arrayShowColoumn);
            if (!empty($tableShowColoumn)) {
                $tableShowColoumn = ', ' . $tableShowColoumn;
            }
            foreach ($arrayShowColoumn as $value) {
                $arrayTmp .= ', UP.'.$value;
            }
            foreach ($arrayShowColoumn as $value) {
                $arrayTmpData[$value] = '';
            }
        }
        if (array_key_exists('id', $_REQUEST)) {
            //$_CLEAN['id'] =   is_string($_REQUEST['id']);
            $_CLEAN['id'] = $_REQUEST['id'];
            $dbuUser      = '   SELECT  UT.niveau, UT.pseudo, UT.pass, UT.url, UT.mail, UT.url AS website,
                                        UT.email, UT.rang, UT.team, UT.team2, UT.team3,
                                        UT.country, UT.game, UT.avatar, UT.signature AS signing, UT.ids_group,
                                        UT.main_group '.$arrayTmp.' ,
                                        UDT.prenom AS firstName, UDT.age AS birthday, UDT.sexe AS sex, UDT.ville AS city, UDT.photo AS shot,
                                        UDT.motherboard, UDT.cpu, UDT.ram, UDT.video AS gpu, UDT.resolution,
                                        UDT.son AS soundcard, UDT.ecran AS screen, UDT.souris AS mouse, UDT.clavier AS keyboard,
                                        UDT.connexion, UDT.system AS os
                                FROM '.USERS_TABLE.' AS UT
                                INNER JOIN '.USERS_PROFILS.' AS UP
                                INNER JOIN '.USERS_DETAIL_TABLE.' AS UDT
                                WHERE UT.id = "' . $_REQUEST['id'] . '" AND
                                      UP.user_id = "' . $_REQUEST['id'] . '" AND
                                      UDT.user_id = "' . $_REQUEST['id'] . '" ';
            $dbeUser      =   mysql_query($dbuUser);
            $data         =   mysql_fetch_assoc($dbeUser);
        }
        // Destruction des variables
        unset($dbeUser);
        unset($arrayTmp);

        if (empty($data)) {
            $submitValue  = ADD;
            $hiddenId     = '';
            $data         = array('id' => '', 'pseudo' => '','pass' => '','url' => '','mail' => '','email' => '','rang' => '','team' => '','team2' => '','team3' => '','country' => '','game' => '','avatar' => '','signing' => '','ids_group' => '','firstName' => '','birthday' => '','sex' => '','city' => '','shot' => '','website' => '' ,'motherboard' => '' ,'cpu' => '' ,'ram' => '' ,'gpu' => '' ,'resolution' => '' ,'soundcard' => '' ,'screen' => '' ,'mouse' => '' ,'keyboard' => '' ,'connexion' => '' ,'os' => '');
            if (!empty($arrayTmpData)) {
                $data = array_merge($data, $arrayTmpData);
            }
        }
        else {
            $submitValue   = MODIFY;
            $hiddenId      = '<input type="hidden" name="id" value="'.$_CLEAN['id'].'" />';
        }
        // Assemble les tableaux
        /**
        *@todo
        * Verifier la sécurité ! sur le $value
        *@todo
        */
        // Sécurise les données
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $_CLEAN[$key] = html_entity_decode($value);
            }
            else {
                $_CLEAN[$key] = '';
            }
        }
        // Le menu
        adminMenu($GLOBALS['arrayMenuMain']);
        // Tableau des input
    $arrayProfilGen      =  array(
                            'pseudo'       => array('type' => 'text', 'required'     => true,  'value' => $_CLEAN['pseudo'],     'name' => NICK_USER),
                            'passReg'      => array('type' => 'password', 'required' => false, 'value' => '',                    'name' => PASSWORD),
                            'passConf'     => array('type' => 'password', 'required' => false, 'value' => '',                    'name' => CONFIRM_PASS),
                            'private_Mail' => array('type' => 'email', 'required'    => true,  'value' => $_CLEAN['mail'],       'name' => MAIL_PRIVATE),
                            'public_Mail'  => array('type' => 'email', 'required'    => false, 'value' => $_CLEAN['email'],      'name' => MAIL_PUBLIC),
                            'website'      => array('type' => 'url', 'required'      => false, 'value' => $_CLEAN['website'],    'name' => WEBSITE),
                            'avatar'       => array('type' => 'file', 'required'     => false, 'value' => $_CLEAN['avatar'],     'name' => AVATAR)
                            );
    $arrayInfosGen       =  array(
                            'firstName'    => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['firstName'],  'name' => FIRSTNAME),
                            'birthday'     => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['birthday'],   'name' => BIRTHDAY),
                            'sex'          => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['sex'],        'name' => SEX),
                            'city'         => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['city'],       'name' => CITY),
                            'shot'         => array('type' => 'file', 'required'     => false, 'value' => $_CLEAN['shot'],       'name' => SHOT),
                            'signing'      => array('type' => 'textarea', 'required' => false, 'value' => $_CLEAN['signing'],    'name' => SIGNING)
                            );
    $arrayHardware       =  array(
                            'motherboard'  => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['motherboard'],'name' => MOTHERBOARD),
                            'cpu'          => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['cpu'],        'name' => CPU),
                            'ram'          => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['ram'],        'name' => RAM),
                            'gpu'          => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['gpu'],        'name' => GPU),
                            'resolution'   => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['resolution'], 'name' => RESOLUTION),
                            'soundcard'    => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['soundcard'],  'name' => SOUNDCARD),
                            'screen'       => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['screen'],     'name' => SCREEN),
                            'mouse'        => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['mouse'],      'name' => MOUSE),
                            'keyboard'     => array('type' => 'text', 'required'     => false, 'value' => $_CLEAN['keyboard'],   'name' => KEYBOARD),
                            'connexion'    => array('type' => 'select', 'required'   => false, 'value' => $_CLEAN['connexion'],  'name' => CONNECTION),
                            'os'           => array('type' => 'select', 'required'   => false, 'value' => $_CLEAN['os'],         'name' => OS)
                            );
    $arraySystemOs       =  array(
                            'Windows 8 32 Bits', 'Windows 8 64 Bits', 'Windows 7 32 Bits', 'Windows 7 64 Bits', 'Windows Vista 32 Bits', 'Windows Vista 64 Bits', 'Windows XP 32 Bits', 'Linux', 'Mac OS X', 'Android', 'Apple', 'Autre'
                            );

    $arrayConnection    =   array(
                            'Modem 56K', 'Modem 128K', 'ADSL 128K', 'ADSL 512K', 'ADSL 1024K', 'ADSL 2048K', 'ADSL 3M', 'ADSL 4M', 'ADSL 8M', 'ADSL 20M +', 'Cable 128K', 'Cable 512K', 'Cable 1024K', 'Cable 2048K', 'Cable 8M', 'Cable 20M +', 'T1 1,5M', 'T2 6M', 'T3 45M', 'Fiber 50M', 'Fiber 100M', 'Autre'
                            );
    foreach ($arrayProfilGen as $key => $data) {
        if ($data['required'] === true) {
            $required = 'required="required" ';
        } else {
            $required = '';
        }
        $name  = 'name="'.$key.'" ';
        $type  = 'type="'.$data['type'].'" ';
        $value = 'value="'.$data['value'].'" ';

        $tmpProfilGen .= '  <div class="formRow">
                                <div class="grid3">
                                    <label>'.$data['name'].'</label>
                                </div>
                                <div class="grid9">
                                    <input '.$name.$type.$value.' maxlength="80" />
                                </div>
                                <div class="clear"></div>
                            </div> ';
    }
    foreach ($arrayInfosGen as $key => $data) {
        if ($data['required'] === true) {
            $required = 'required="required" ';
        } else {
            $required = '';
        }
        $name  = 'name="'.$key.'" ';
        $type  = 'type="'.$data['type'].'" ';
        $value = 'value="'.$data['value'].'" ';
        // in_array ! a faire
        if ($key == 'birthday') {
            $inputClass = 'class="datepicker" ';
        }
        else {
            $inputClass = '';
        }
        if ($data['type'] == 'textarea') {
            $form = '<textarea class="editor" '.$name.' rows="5" cols="50">'.$data['value'].'</textarea>';
        }
        else {
            $form = '<input '.$name.$type.$value.$inputClass.' maxlength="80" />';
        }
        $tmpInfosGen .= '   <div class="formRow">
                                <div class="grid3">
                                    <label>'.$data['name'].'</label>
                                </div>
                                <div class="grid9">'.$form.'</div>
                                <div class="clear"></div>
                            </div> ';
    }
    foreach ($arrayHardware as $key => $data) {
        if ($data['required'] === true) {
            $required = 'required="required" ';
        } else {
            $required = '';
        }
        $name  = 'name="'.$key.'" ';
        $type  = 'type="'.$data['type'].'" ';
        $value = 'value="'.$data['value'].'" ';
        if ($data['type'] == 'select') {
            $tmpOption = '';
            $arrayTmp  = '';
            if ($key == 'os') {
                $arrayTmp = $arraySystemOs;
            }
            else if ($key == 'connexion') {
                $arrayTmp = $arrayConnection;
            }
            foreach ($arrayTmp as $keyConnect) {
               $tmpOption .= '<option value="'.$keyConnect.'">'.$keyConnect.'</option>';
            }
            $tmpHardware .= '   <div class="formRow">
                                    <div class="grid3">
                                        <label>'.$data['name'].'</label>
                                    </div>
                                    <div class="grid9 searchDrop">
                                        <select name="'.$keyConnect.'" data-placeholder="" class="select" tabindex="2">
                                            '.$tmpOption.'
                                        </select>
                                    </div>
                                    <div class="clear"></div>
                                </div>';
        }
        else {
            $tmpHardware .= '   <div class="formRow">
                                    <div class="grid3">
                                        <label>'.$data['name'].'</label>
                                    </div>
                                    <div class="grid9">
                                        <input '.$name.$type.$value.' maxlength="80" />
                                    </div>
                                    <div class="clear"></div>
                                </div> ';
        }
    }
?>
        <form method="post" action="index.php?file=Admin&amp;page=users&amp;op=sendUser">
            <fieldset>
                <div class="fluid">

                    <div class="widget grid6">
                        <div class="whead">
                            <h6>Profil Général</h6>
                            <div class="clear"></div>
                        </div>
                        <?php echo $tmpProfilGen; ?>
                        <div class="formRow">
                            <div class="grid3">
                                <label><?php echo COUNTRY; ?>:</label>
                            </div>
                            <div class="grid9 searchDrop">
                                <select name="country" data-placeholder="" class="select" tabindex="2">
<?php
                            $checked = "";
                            foreach ($directory as $id => $value) {
                                if ($value == $_CLEAN['country']) {

                                    $value = explode ('.', $value);
?>
                                    <option value="<?php echo $id; ?>" selected="selected"><?php echo $value[0]; ?></option>
<?php
                                }
                                else {
                                    $value = explode ('.', $value);
?>
                                    <option value="<?php echo $id; ?>"><?php echo $value[0]; ?></option>
<?php
                                }
                            }
?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>

                    </div>

                    <div class="widget grid6">
                        <div class="whead">
                            <h6>Infos Générales</h6>
                            <div class="clear"></div>
                        </div>
                        <?php echo $tmpInfosGen; ?>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <div class="fluid">

                    <div class="widget grid6">
                        <div class="whead">
                            <h6>Config matérielle</h6>
                            <div class="clear"></div>
                        </div>
                        <?php echo $tmpHardware; ?>
                    </div>
<?php
    if (!empty($arrayShowColoumn)):
?>
                    <div class="widget grid6">
                        <div class="whead">
                            <h6>Options du profil</h6>
                            <div class="clear"></div>
                        </div>
<?php
                    foreach ($arrayShowColoumn as $value):
?>
                        <div class="formRow">
                            <div class="grid3">
                                <label><?php echo $value; ?></label>
                            </div>
                            <div class="grid9">
                                <input type="text" name="<?php echo $value; ?>" size="30" maxlength="80" value="<?php echo $_CLEAN[$value]; ?>" />
                            </div>
                            <div class="clear"></div>
                        </div>
<?php
                    endforeach;
?>
                    </div>
<?php
    endif;
?>
                </div>
            </fieldset>
            <div class="formRow">
                <div class="body center">
                    <?php echo $hiddenId; ?>
                    <input class="buttonM bBlue" value="<?php echo $submitValue; ?>" type="submit">
                    <a class="buttonM bDefault" href="index.php?file=Admin&amp;page=users"><?php echo BACK; ?></a>
                </div>
            </div>
        </form>
<?php
    }

    function sendUser () {
        $arrayRequest = array('id', 'pseudo', 'passReg', 'passConf', 'private_Mail', 'public_Mail', 'website', 'avatar', 'country', 'firstName', 'birthday', 'sex', 'city', 'shot', 'signing', 'motherboard', 'cpu', 'ram', 'gpu', 'resolution', 'soundcard', 'screen', 'mouse', 'keyboard', 'connexion', 'os');
        foreach($arrayRequest as $key) {
            if(!array_key_exists($key, $_REQUEST))  {
                $_REQUEST[$key] = '';
            }
        }
        // Liste des colonnes complèmentaires
        $dbsShowColumn   = ' SHOW COLUMNS FROM '.USERS_PROFILS.' ';
        $dbeShowColumn   =   mysql_query($dbsShowColumn);
        // liste des colonnes a ne pas recuperer
        $arrayBase = array('id', 'user_id');
        while($showColumn = mysql_fetch_assoc($dbeShowColumn)) {
            if (!in_array($showColumn['Field'], $arrayBase, true)) {
                $arrayShowColoumn[] = $showColumn['Field'];
            }
        }
        if (!empty($arrayShowColoumn)) {
            $arrayRequest = array_merge($arrayRequest, $arrayShowColoumn);
        }
        foreach ($arrayRequest as $key => $value) {
            $_CLEAN[$value] = mysql_real_escape_string($_REQUEST[$value]);
        }
        // Unique user id
        do {
            $userId = substr(sha1(uniqid()), 0, 20);
            $sql = mysql_query('SELECT * FROM ' . USERS_TABLE . ' WHERE id=\'' . $userId . '\'');
        } while (mysql_num_rows($sql) != 0);
        // SQL
        $dbsTestName = 'SELECT count(id) AS countUser,
                            (
                                SELECT count(id)
                                FROM '.USERS_TABLE.'
                                WHERE mail = "'.$_CLEAN['private_Mail'].'"
                            ) AS countMail,
                            (
                                SELECT count(id)
                                FROM '.BANNED_TABLE.'
                                WHERE pseudo = "'.$_CLEAN['pseudo'].'"
                            ) AS countBan,
                            (
                                SELECT count(id)
                                FROM '.BANNED_TABLE.'
                                WHERE email = "'.$_CLEAN['private_Mail'].'"
                            ) AS countBanMail
                        FROM '.USERS_TABLE.'
                        WHERE pseudo = "'.$_CLEAN['pseudo'].'" ';
        $dbeTestName = mysql_query($dbsTestName) or die(mysql_error());
        $testName    = mysql_fetch_assoc($dbeTestName);
        // Sets errors to 0
        $errors = 0;
        // test if user exists
        if ($testName['countUser'] != 0) {
            $errors++;
            $errorMsg     =  ERROR_REG_LOGIN;
            $errorClass   = 'Failure';
        }
        // Tests if the user is not Banned
        else if ($testName['countBan'] > 0) {
            $errors++;
            $errorMsg     =  ERROR_PSEUDO_BANNED;
            $errorClass   = 'Failure';
        }
        // Test if the email is not banned
        else if ($testName['countBanMail'] > 0) {
            $errors++;
            $errorMsg     =  ERROR_MAIL_BANNED;
            $errorClass   = 'Failure';
        }
        // Tests whether the username and password are not empty
        else if (empty($_CLEAN['pseudo']) &&
                 empty($_CLEAN['passReg']) &&
                 empty($_CLEAN['passConf'])
                ) {
            $errors++;
            $errorMsg     =  ERROR_EMPTY_FIELD;
            $errorClass   = 'Failure';
        }
        // Tests the size of the pseudo (25 character limit)
        else if (strlen($_CLEAN['pseudo']) > 25) {
            $errors++;
            $errorMsg     =  NICK_TO_LONG;
            $errorClass   = 'Failure';
        }
        // Tested if the two passwords are identical
        else if ($_CLEAN['passReg'] != $_CLEAN['passConf']) {
            $errors++;
            $errorMsg     =  ERROR_TWO_PASS_FAIL;
            $errorClass   = 'Failure';
        }
        // Check if email is already used
        else if ($testName['countMail'] > 0) {
            $errors++;
            $errorMsg     =  ERROR_MAIL_USE;
            $errorClass   = 'Failure';

        }
        // Error Empty
        else if ($errors == 0) {


/*            $arraySecurity = array(
                            'pseudo'          => $_REQUEST['pseudo'],
                            'password'        => $_REQUEST['password'],
                            'passwordConfirm' => $_REQUEST['passwordConfirm'],
                            'privateMail'     => $_REQUEST['privateMail']
                            );*/
            /*
            foreach ($arraySecurity as $key => $value) {
                $value        = mysql_real_escape_string(stripslashes($value));
                $_CLEAN[$key] = nkHtmlEntities($value);
            }
            */

            // password crypte
            $cryptPass = nk_hash($_CLEAN['passConf']);

            /*

            $dbiUserInsert  = ' INSERT INTO '.USERS_TABLE.'
                                                (
                                                    `id` , `team` , `team2` , `team3` , `rang` ,
                                                    `ordre` , `pseudo` , `mail` , `email` , `url` ,
                                                    `pass` , `niveau` , `date` , `avatar` , `signature` ,
                                                    `user_theme` , `user_langue` , `game` , `country` , `count` ,
                                                    `erreur` , `token` , `token_time` , `ids_group` , `main_group` ,
                                                    `visitedProfil` , `oldPseudo`
                                                )
                                VALUES          (
                                                    "'.$userId.'" , "" , "" , "" , "" ,
                                                    "" , "'.$_CLEAN['pseudo'].'" , "'.$_CLEAN['mail'].'" , "" , "'.$_CLEAN['url'].'" ,
                                                    "'.$cryptPass.'" , "9" , "'.time().'" , "'.$_CLEAN['avatar'].'" , "'.$_CLEAN['signature'].'" ,
                                                    "" , "" , "" , "'.$_CLEAN['country'].'" , "" ,
                                                    "" , "" , "" , "1" , "1" ,
                                                    "" , ""
                                                )';
            $dbeUserInsert  = mysql_query($dbiUserInsert);

            $dbiUserDetail  = ' INSERT INTO '.USERS_DETAIL_TABLE.'
                                                (
                                                    `user_id`, `prenom`, `age`, `sexe`, `ville`,
                                                    `photo`, `motherboard`, `cpu`, `ram`, `video`,
                                                    `resolution`, `son`, `ecran`, `souris`, `clavier`,
                                                    `connexion`, `system`, `pref_1`, `pref_2`, `pref_3`,
                                                    `pref_4`, `pref_5`
                                                )
                                VALUES          (
                                                    "'.$userId.'" , "" , "" , "" , "",
                                                    "" , "" , "" , "" , "",
                                                    "" , "" , "" , "" , "",
                                                    "" , "" , "" , "" , "",
                                                    "" , ""
                                                )';
            $dbeUserDetail  = mysql_query($dbiUserDetail);

            */

            $actionMsg = 'ajout avec succes le ';

            $texteaction = $actionMsg.': '.$_CLEAN['pseudo'];
            mysql_query('INSERT INTO '.ACTIONS_TABLE.' VALUES ("", "'.time().'", "'.$GLOBALS['user']['id'].'", "'.$texteaction.'") ') or die (mysql_error());

            $errorMsg     =  REG_PROGRESS;
            $errorClass   = 'Success';
        }

        printMessage($errorMsg, $errorClass); // Success or Failure
        //redirect("index.php?file=Admin&amp;page=users", 2);

    }

    function MainValidatedUser() {

        $date      = time();
        $counter   = 0;

        $dbsUser   = ' SELECT count(id) AS countBan, id, pseudo, mail, date
                       FROM '.USERS_TABLE.'
                       WHERE ids_group = 3
                       ORDER BY date ';
        $dbeUser   =   mysql_query($dbsUser);
        $data      = mysql_fetch_assoc($dbeUser);

        // les colonnes
        $arrayColumns = array(NICK_USER, MAIL, DATE_USER, TIME_REMNANT, ACTIONS);
        // les variables
        $tmpColumns   = null;
        foreach ($arrayColumns as $value) {
            $tmpColumns .= '<td class="center">
                                <strong>'.$value.'</strong>
                            </td>';
        }
?>
        <script type="text/javascript">
        <!--
            function delUser(pseudo, id) {
                if (confirm("<?php echo _DELBLOCK; ?> " + pseudo +" ! <?php echo _CONFIRM; ?>")) {
                    document.location.href = "index.php?file=Admin&page=user&op=deleteUser&id="+id;
                }
            }
        // -->
        </script>

        <div class="widget">
            <div class="whead">
                <h6><?php echo USERS_MANAGEMENT; ?></h6>
                <div class="clear"></div>
            </div>

            <table class="tDefault">
                <thead>
                    <tr>
                        <?php echo $tmpColumns; ?>
                    </tr>
                </thead>
                <tbody>
<?php
                        if($data['countBan'] != 0) {
                            while($data = mysql_fetch_assoc($dbeUser)) {

                            if ($nuked['validation'] == "admin") {
                                $timeLimit = $data['date'] + 864000;
                            }
                            else {
                                $timeLimit = $data['date'] + 86400;
                            }

                            $data['date'] = nkDate($data['date']);

                            $_CLEAN['pseudo']  = mysql_real_escape_string(stripslashes($data['pseudo']));

                            if ($timeLimit < $date) {
                                $counter++;
                                $dbdUser       = ' DELETE FROM '.USERS_TABLE.'
                                                   WHERE id = "'.$_REQUEST['id'].'"
                                                   AND id = "'.$data['id'].'" ';
                                $dbeUser       =   mysql_query($dbdUser);
                            }
    ?>
                        <tr>
                            <td><?php echo $_CLEAN['pseudo']; ?></td>
                            <td><a href="mailto:<?php echo $data['mail']; ?>"><?php echo $data['mail']; ?></a></td>
                            <td><?php echo $data['date']; ?></td>
                            <td>
                                <a href="index.php?file=Admin&amp;page=user&amp;op=formValidatedUser&amp;id=<?php echo $data['id']; ?>">
                                    <img src="images/edit.gif" alt="<?php echo _VALIDTHISUSER; ?>" title="<?php echo _VALIDTHISUSER; ?>" />
                                </a>
                            </td>
                            <td>
                                <a href="index.php?file=Admin&amp;page=user&amp;op=formUser&amp;id=<?php echo $data['id']; ?>">
                                    <img src="images/edit.gif" alt="<?php echo _EDITUSER; ?>" title="<?php echo _EDITUSER; ?>" />
                                </a>
                            </td>
                            <td>
                                <a href="javascript:delUser('<?php echo $_CLEAN['pseudo']; ?>',' <?php echo $data['id']; ?>');">
                                    <img  src="images/del.gif" alt="" title="<?php echo _DELETEUSER; ?>" />
                                </a>
                            </td>
                        </tr>
<?php
                            }
                        }
                        else {
?>
                            <tr><td class="center" colspan="6"><?php echo NO_USER_VALID; ?></td></tr>
<?php
                        }
                        if ($counter > 0) {
                            if($counter == 1) {
                                $text = "".$counter." "._1USNOTACTION."";
                            }
                            else {
                                $text = "".$counter." "._USNOTACTION."";
                            }
                        $dbiNotifInsert = ' INSERT INTO '.$nuked['prefix'].'_notification
                                             (`date`, `type`, `texte` )
                                             VALUES ("'.$date.'" , "3" , "'.$text . '") ';
                        $dbeNotifInsert = mysql_query($dbiNotifInsert);
                        }
?>

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="body center">
                            <a class="buttonM bDefault" href="index.php?file=Admin"><?php echo BACK; ?></a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
<?php
    }

    function formPref () {
        // Menu
        adminMenu($GLOBALS['arrayMenuMain']);
        // Variables
        $tmpProfilGen  = null;
        $tmpTextaera   = null;
        $tmpActive     = null;
        $countTextaera = 1;
        // Array
        $arrayConfigGen =   array(
                                'registrations'          => array('type' => 'select',   'value'  => '',  'name' => REGISTRATIONS),
                                'validation'             => array('type' => 'select',   'value'  => '',  'name' => VALIDATION),
                                'registrationMail'       => array('type' => 'checkbox', 'value'  => '',  'name' => REGISTRATION_MAIL),
                                'delThemselves'          => array('type' => 'checkbox', 'value'  => '',  'name' => DELETE_THEMSELVES),
                                'allowAvatarUpload'      => array('type' => 'checkbox', 'value'  => '',  'name' => ALLOW_AVATAR_UPLOAD),
                                'allowExtAvatar'         => array('type' => 'checkbox', 'value'  => '',  'name' => ALLOW_EXTERNAL_AVATAR)
                                );
        $arrayTextaera  =   array(
                                'registrationDisclaimer' => array('type' => 'textarea', 'value'  => '',  'name' => REGISTRATION_DISCLAIMER),
                                'registrationTextMail'   => array('type' => 'textarea', 'value'  => '',  'name' => REGISTRATION_TEXT_MAIL),
                                );
        $arrayActive    =   array(
                                'infosGeneral'           => array('type' => 'checkbox', 'value'  => '',  'name' => INFOS_GEN),
                                'configMateriel'         => array('type' => 'checkbox', 'value'  => '',  'name' => CONFIG_MATERIEL),
                                'infosExtra'             => array('type' => 'checkbox', 'value'  => '',  'name' => INFOS_EXTRA),
                                );
        $arrayFormOpt   =   array(
                                'registrations' => array('optValue' =>  array('value' => 'on',
                                                                              'name'  => OPEN
                                                                             ),
                                                                        array('value' => 'off',
                                                                              'name'  => CLOSE
                                                                             ),
                                                                        array('value' => 'mail',
                                                                              'name'  => BY_MAIL
                                                                             )
                                                        ),
                                'validation'    => array('optValue' =>  array('value' => 'admin',
                                                                              'name'  => ADMINISTRATOR
                                                                             ),
                                                                        array('value' => 'auto',
                                                                              'name'  => AUTO
                                                                             ),
                                                                        array('value' => 'mail',
                                                                              'name'  => BY_MAIL
                                                                             )
                                                        )

                                );

        foreach ($arrayConfigGen as $key => $data) {
            $formType   = null;
            $formOption = null;
            $name       = 'name="'.$key.'" ';
            $value      = 'value="'.$data['value'].'" ';
            if (in_array('select', $data)) {
                foreach ($arrayFormOpt[$key] as $keyOpt => $valueOpt) {
                    $formOption .= ' <option value="'.$valueOpt['value'].'">'.$valueOpt['name'].'</option> ';
                }
                $formType .= ' <select '.$name.'>'.$formOption.'</select> ';
            }
            else if (in_array('checkbox', $data)) {
                $formType .= '  <input type="checkbox" '.$name.' /> ';
            }
            else if (in_array('textarea', $data)) {
                $formType = '<textarea class="editor" '.$name.' rows="5" cols="50">'.$data['value'].'</textarea>';
            }
            else {
                $formType .= '<input type="text" '.$name.$value.' /> ';
            }
            $tmpProfilGen .= '  <div class="formRow">
                                    <div class="grid4">
                                        <label>'.$data['name'].'</label>
                                    </div>
                                    <div class="grid8">
                                        '.$formType.'
                                    </div>
                                    <div class="clear"></div>
                                </div> ';
            unset($formType);
        }

        foreach ($arrayTextaera as $key => $value) {
            if ($countTextaera%2 == 1) {
                $tmpTextaera .= '   <fieldset>
                                      <div class="fluid"> ';
            }
            $tmpTextaera .= '   <div class="widget grid6">
                                    <div class="whead">
                                        <h6>'.$value['name'].'</h6>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <div class="grid12">
                                            <textarea class="editor" cols="50" rows="5" name="'.$key.'">
                                                '.$value['value'].'
                                            </textarea>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div> ';
            if ($countTextaera%2 != 1 OR count($arrayTextaera) == $countTextaera) {
                $tmpTextaera .= '       </div>
                                    <fieldset> ';
            }
            $countTextaera++;
        }
        foreach ($arrayActive as $key => $data) {
            $formType   = null;
            $name       = 'name="'.$key.'" ';
            if (in_array('checkbox', $data)) {
                $formType .= '  <input type="checkbox" '.$name.' /> ';
            }
            $tmpActive .= '  <div class="formRow">
                                    <div class="grid4">
                                        <label>'.$data['name'].'</label>
                                    </div>
                                    <div class="grid8">
                                        '.$formType.'
                                    </div>
                                    <div class="clear"></div>
                                </div> ';
        }
        unset($formType);
?>
            <form class="main" action="">
                <?php echo $tmpTextaera; ?>
                <fieldset>
                    <div class="fluid">
                        <div class="widget grid6">
                            <div class="whead">
                                <h6>Config General</h6>
                                <div class="clear"></div>
                            </div>
                            <?php echo $tmpProfilGen; ?>
                        </div>
                        <div class="widget grid6">
                            <div class="whead">
                                <h6>Activations</h6>
                                <div class="clear"></div>
                            </div>
                            <?php echo $tmpActive; ?>
                        </div>
                    </div>
                </fieldset>
            </form>
<?php
    }

    function mainProfil () {
        // les colonnes
        $arrayColumns = array(NAME, TYPE, ACTIVATE, ACTIONS);
        // les variables
        $tmpColumns   = null;
        foreach ($arrayColumns as $value) {
            $tmpColumns .= '<td class="center">
                                <strong>'.$value.'</strong>
                            </td>';
        }
        ?>
        <script type="text/javascript">
            function delColomn(name) {
                if (confirm('<?php echo DELETE_COLUMN; ?> '+name+' ! <?php echo CONFIRM; ?>')) {
                    document.location.href = 'index.php?file=Admin&page=users&op=delColomn&name='+name;
                }
            }
        </script>
        <?php
        $arrayTmp        = null;
        $dbsShowColumn   = ' SHOW COLUMNS FROM '.USERS_PROFILS.' ';
        $dbeShowColumn   =   mysql_query($dbsShowColumn);
        // liste des colonnes a ne pas recuperer
        $arrayBase = array('id', 'user_id');

        while($showColumn = mysql_fetch_assoc($dbeShowColumn)) {
            if (!in_array($showColumn['Field'], $arrayBase, true)) {
                $arrayShowColoumn[] = $showColumn['Field'];
            }
        }
        $tmpColumnsData = null;
        foreach ($arrayShowColoumn as $key => $value) {
           $tmpColumnsData .= ' <tr>
                                    <td><strong>'.$value.'</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td class="center">
                                        <a class="tablectrl_medium bDefault tipS nkIcons icon-edit" href="index.php?file=Admin&amp;page=users&amp;op=formProfil&amp;name='.$value.'" original-title="'.EDIT.'"></a>
                                        <a class="tablectrl_medium bDefault tipS nkIcons icon-delete" href="javascript:delColomn(\''.mysql_real_escape_string($value).'\');" original-title="'.DELETE.'"></a>
                                    </td>
                                </tr>';
        }
        $arrayMenu =    array(
                                array(
                                    'link'      => 'index.php?file=Admin&amp;page=users',
                                    'classIcon' => 'icon-add',
                                    'title'     =>  'Main'
                                ),
                                array(
                                    'link'      => 'index.php?file=Admin&amp;page=users&amp;op=formProfil',
                                    'classIcon' => 'icon-add',
                                    'title'     =>  OPTION_ADD
                                ),
                        );
        adminMenu ($arrayMenu);
?>
        <div class="widget">
            <div class="whead">
                <h6>Option du profil</h6>
                <div class="clear"></div>
            </div>

            <table class="tDefault">
                <thead>
                    <tr>
                        <?php echo $tmpColumns; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $tmpColumnsData; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="body center">
                            <a class="buttonM bDefault" href="index.php?file=Admin"><?php echo BACK; ?></a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
<?php
    }

    function formProfil () {
        $arrayRequest = array('name');
        foreach($arrayRequest as $key) {
            if(!array_key_exists($key, $_REQUEST))  {
                $_REQUEST[$key] = '';
            }
        }
        if (empty($_REQUEST['name'])) {
            $title          = ADD;
            $_CLEAN['name'] = null;
            $submitValue    = ADD;
            $hidden         = '<input type="hidden" name="send" />';
        }
        else {
            $title          = EDIT;
            $_CLEAN['name'] = mysql_real_escape_string($_REQUEST['name']);
            $submitValue    = MODIFY;
            $hidden         = '<input type="hidden" name="send" value="'.$_CLEAN['name'].'" />';
        }
?>
        <div class="widget fluid">
            <div class="whead">
                <h6><?php echo $title; ?> une informations de profil</h6>
                <div class="clear"></div>
            </div>
            <form method="post"  action="index.php?file=Admin&amp;page=users&amp;op=sendProfil">
                <div class="formRow">
                    <div class="grid2"><label><?php echo NAME; ?></label></div>
                    <div class="grid10"><input type="text" name="name" value="<?php echo $_CLEAN['name']; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="formRow">
                    <div class="grid2"><label><?php echo TYPE; ?></label></div>
                    <div class="grid10">
                        <select name="type">
                            <option value="text">color</option>
                            <option value="text">date</option>
                            <option value="text">text</option>
                            <option value="text">datetime</option>
                            <option value="text">email</option>
                            <option value="text">month</option>
                            <option value="text">number</option>
                            <option value="text">tel</option>
                            <option value="text">time</option>
                            <option value="text">url</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="formRow">
                    <div class="grid2"><label><?php echo ACTIVATE; ?></label></div>
                    <div class="grid10"><input type="checkbox" name="activate" /></div>
                    <div class="clear"></div>
                </div>
                 <div class="body center">
                    <?php echo $hidden; ?>
                    <input class="buttonM bBlue" type="submit" value="<?php echo $submitValue; ?>" />
                    <a class="buttonM bDefault" href="index.php?file=Admin&amp;page=users"><?php echo BACK; ?></a>
                </div>
            </form>
        </div>
<?php
    }

    function sendProfil () {
        $arrayRequest = array('name', 'type', 'activate');
        foreach ($arrayRequest as $key) {
            if (!array_key_exists($key, $_REQUEST))  {
                $_REQUEST[$key] = '';
            }
        }
        $false          = array('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[ ]@i','@[^a-zA-Z0-9_]@');
        $replace        = array('e','a','i','u','o','c','_','');
        $_CLEAN['name'] = preg_replace($false, $replace, strtolower($_REQUEST['name']));

        if (isset($_REQUEST['send'])) {
            $dbiUsersProfil = 'ALTER TABLE '.USERS_PROFILS.' ADD COLUMN ('.$_CLEAN['name'].' varchar(80) NOT NULL)';
            $dbeUsersProfil = mysql_query($dbiUsersProfil);
            $successMsg = COLUMN_ADDED;
            $actionMsg  = ACTION_ADD_COLUMN;

        }
        if (isset($_REQUEST['send']) AND !empty($_REQUEST['send'])) {
            $dbiUsersProfil = 'ALTER TABLE '.USERS_PROFILS.' CHANGE ('.$_REQUEST['send'].' '.$_CLEAN['name'].' varchar(80) NOT NULL)';
            $dbeUsersProfil = mysql_query($dbiUsersProfil);
            $successMsg = COLUMN_EDITED;
            $actionMsg  = ACTION_EDIT_COLUMN;
        }

        $texteaction = $actionMsg.': '.$_CLEAN['name'];
        mysql_query('INSERT INTO '.ACTIONS_TABLE.' VALUES ("", "'.time().'", "'.$GLOBALS['user']['id'].'", "'.$texteaction.'") ') or die (mysql_error());

        printMessage($successMsg, 'Success');
        redirect("index.php?file=Admin&amp;page=users&amp;op=mainProfil", 2);
    }

    function adminMenu ($data) {
?>
        <ul class="middleNavR">
<?php
            foreach ($data as $key => $value):
?>
            <li>
                <a class="tipN" href="<?php echo $value['link']; ?>" original-title="<?php echo $value['title']; ?>">
                    <span class="nkIcons <?php echo $value['classIcon']; ?>"></span>
                </a>
            </li>
<?php
            endforeach;
?>
        </ul>
<?php
    }

    $arrayMenuMain =    array(
                            array(
                                'link'      => 'index.php?file=Admin&amp;page=users',
                                'classIcon' => 'icon-add',
                                'title'     =>  'Main'
                            ),
                            array(
                                'link'      => 'index.php?file=Admin&amp;page=users&amp;op=formUser',
                                'classIcon' => 'icon-add',
                                'title'     =>  USER_ADD
                            ),
                            array(
                                'link'      => 'index.php?file=Admin&amp;page=users&amp;op=MainValidatedUser',
                                'classIcon' => 'icon-add',
                                'title'     => 'Membres non validés'
                            ),
                            array(
                                'link'      => 'index.php?file=Admin&amp;page=users&amp;op=mainBan',
                                'classIcon' => 'icon-add',
                                'title'     => 'Gestion des Bannissements'
                            ),
                            array(
                                'link'      => 'index.php?file=Admin&amp;page=users&amp;op=mainProfil',
                                'classIcon' => 'icon-add',
                                'title'     => 'Option du profil'
                            ),
                            array(
                                'link'      => 'index.php?file=Admin&amp;page=users&amp;op=formPref',
                                'classIcon' => 'icon-add',
                                'title'     => 'Preference'
                            )
                    );

    switch ($_REQUEST['op']) {
        case "main":
              main();
        break;

        case "formUser":
            formUser();
        break;

        case "sendUser":
              sendUser();
        break;

        case "MainValidatedUser":
              MainValidatedUser();
        break;

        case "formPref":
              formPref();
        break;

        case "mainProfil":
              mainProfil();
        break;

        case "formProfil":
              formProfil();
        break;

        case "sendProfil":
              sendProfil();
        break;

        default:
            main();
        break;
    }

}
else {
?>
    <div class="notification error png_bg">
        <div>
            <div style="text-align: center;">
                <?php echo ZONEADMIN; ?>
            </div>
        </div>
    </div>
    <div style="text-align:center;">
        <a class="button" href="javascript:history.back()">
            <b><?php echo BACK; ?></b>
        </a>
    </div>
<?php
}
adminFooter();

?>
