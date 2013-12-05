<?php
/**
 * [Index.php Module User]
 *
 * @version 1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2013 Nuked-Klan (Registred Trademark)
 */

if (!defined("INDEX_CHECK")) exit('You can\'t run this file alone.');

nkTranslate('modules/User/lang/'.$GLOBALS['language'].'.lang.php');

    function index () {
        if (!nkHasVisitor()):
?>
        <section id="modsUser">
            <aside>
                <nav>
                    <ul>
                        <li class="pseudo">
                            <img src="http://www.palacewar.eu/Stive/avatar_stive2.png" alt="Stive">
                            <span>Stive</span>
                        </li>
                        <li>
                            <a class="icon-home jqueryLinksSwtich" data-icon="icon-home" data-title="Accueil" href="#home">Accueil</a>
                        </li>
                        <li>
                            <a class="icon-envelope jqueryLinksSwtich" data-icon="icon-envelope" data-title="Messagerie Priv&eacute;" href="#privateMsg">Messagerie Priv&eacute;
                                <span>14</span>
                            </a>
                            <ul id="userbox">
                                <li><a class="icon-arrow-right" href="#">Boîte de r&eacute;ception</a></li>
                                <li><a class="icon-arrow-right" href="#">Envoy&eacute;(s)</a></li>
                                <li><a class="icon-arrow-right" href="#">Corbeille</a></li>
                            </ul>
                        </li>
                        <li id="jqueryListFriends"s>
                            <a class="icon-users" href="#">Liste d'amis (3)<span>0</span></a>
                            <ul id="listFriends">
                                <li><a class="friend" href="#">Liste d'amis (3)</a></li>
                                <li>
                                    <a href="#" class="onlineGreen">
                                        <img src="http://www.nuked-klan.org/upload/User/1370708754.jpg" alt="Homax">
                                        <span>Homax</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="onlineRed">
                                        <img src="http://images.samoth.fr/photos/sam.png" alt="Samoth">
                                        <span>Samoth</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="onlineRed">
                                        <img src="http://www.nuked-klan.org/upload/Forum/d868bc8969.png" alt="Zdav">
                                        <span>Zdav</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>

            </aside>

            <article>

                <header>
                    <h1>Compte</h1>
                    <a class="tipE jqueryLinksSwtich" title="Option du compte" data-icon="icon-cog" href="#accountOption" title="Option du compte"><span class="icon-cog"></span></a>
                    <a class="tipE" href="#" title="D&eacute;connexion" id="jqueryClose"><span class="icon-power-outline"></span></a>
                </header>
                <nav id="breadcrumb">
                    <ul>
                        <li>
                            <span id="jqueryIcon" class="icon-home"></span>
                        </li>
                        <li id="jqueryTitle">Accueil</li>
                    </ul>
                </nav>
                <section id="jquerySections">
                    <?php home(); ?>
                </section>
            </article>

        </section>
<?php
        else:
            redirect("index.php?file=User&op=login_screen", 0);
        endif;
    }
    function home () {
        if (!nkHasVisitor()):
            // ->   variables
            $arrayListLabel   = array();
            $arrayListSocial  = array();
            $arrayListGaming  = array();
            $arrayAllDataUser = array();
            $nameGroups       = array();
            $tmpDivSocial     = '';
            $tmpDivMaterial   = '';
            $tmpDivPref       = '';
            $tmpDivGen        = '';
            $tmpDivGaming     = '';
            $tmpCommentName   = '';
            $tmpCommentValue  = '';
            $tmpStatsName     = '';
            $tmpStatsValue    = '';
            $tmpForumName     = '';
            $tmpForumValue    = '';
            $tmpTitleGroup    = '';
            $tmpClassForum    = '';
            $tmpClassComment  = '';
            // ->   array pref
            $arrayPref          =   array(
                                        'first_name',
                                        'date_of_birth',
                                        'gender',
                                        'country',
                                    );
            // ->   array infos General
            $arrayInfosGen      =   array(
                                        'date_of_arrival',
                                        'last_visit',
                                        'private_mail',
                                        'website',
                                        'password',
                                    );
            $arrayStats         =   array(
                                        'date_of_arrival',
                                        'ip',
                                        'administrators',
                                        'friend_numbers' ,
                                        'groups',
                                        'numbers_connect',
                                        'numbers_forum',
                                        'numbers_comment',
                                        'numbers_download',
                                        'numbers_sugg'
                                    );
            // ->   sql all data user
            $dbsUser            = ' SELECT  pseudo,
                                            mail AS private_mail,
                                            email AS public_mail,
                                            url AS website,
                                            pass AS password,
                                            UT.date AS date_of_arrival,
                                            avatar,
                                            signature,
                                            country,
                                            ids_group AS groups,
                                            ip,
                                            prenom AS first_name,
                                            age AS date_of_birth,
                                            sexe AS sex,
                                            ville AS city,
                                            photo,
                                            motherboard,
                                            cpu,
                                            ram,
                                            video AS gpu,
                                            resolution,
                                            son AS soundcard,
                                            ecran AS screen,
                                            souris AS mouse,
                                            clavier AS keyboard,
                                            connexion AS connection,
                                            system AS os,
                                            count(ST.id) AS numbers_sugg
                                    FROM    '.USERS_TABLE.' AS UT
                                    LEFT    OUTER JOIN '.USERS_DETAIL_TABLE.' AS UDT ON UT.id = UDT.user_id
                                    LEFT    OUTER JOIN '.SUGGEST_TABLE.' AS ST ON UT.id = ST.user_id
                                    WHERE   UT.id = "'.$GLOBALS['user']['id'].'" ';
            $dbeUser            =   mysql_query($dbsUser);
            unset($dbsUser);
            $arrayAllDataUser   =   mysql_fetch_assoc($dbeUser);
            $arrayAllDataUser['date_of_arrival_large'] = nkDate($arrayAllDataUser['date_of_arrival']);
            $arrayAllDataUser['date_of_arrival_small'] = nkDate($arrayAllDataUser['date_of_arrival']);
            // ->   sql select infos groups
            $dbsGroup = ' SELECT id, nameGroup, color
                          FROM '.GROUPS_TABLE.' ';
            $dbeGroup = mysql_query($dbsGroup) or die(mysql_error());
            while ($data = mysql_fetch_assoc($dbeGroup)) {
                $data['nameGroup'] = (isset($data['nameGroup'])) ? constant($data['nameGroup']) : $data['nameGroup'];
                $nameGroups[$data['id']] =  array('name'  => $data['nameGroup'],
                                                  'color' => $data['color']
                                                 );
            }
            // ->   sql select label (on-off)
            $dbsLabel       = ' SELECT name, status
                                FROM '.$GLOBALS['nuked']['prefix'].'_users_label ';
            $dbeLabel       =   mysql_query($dbsLabel);
            unset($dbsLabel);
            while ($data        = mysql_fetch_assoc($dbeLabel)) {
                $arrayListLabel[$data['name']]   = $data['status'];
            }
            // ->   infos general
            foreach ($arrayInfosGen as $key) {
                $arrayAllDataUser['date_of_arrival'] = $arrayAllDataUser['date_of_arrival_large'];
                $arrayAllDataUser[$key] = (isset($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : POA;
                $arrayAllDataUser[$key] = (!empty($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : POA;
                $tmpDivGen .= ' <div data-title="'.constant(strtoupper($key)).'" data-name="'.$key.'">
                                    <span>'.constant(strtoupper($key)).'</span>
                                    <span>'.$arrayAllDataUser[$key].'</span>
                                </div> '."\n";
            }
            // ->   preference
            foreach ($arrayPref as $key) {
                $arrayAllDataUser[$key] = (isset($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : POA;
                $arrayAllDataUser[$key] = (!empty($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : POA;
                $tmpDivPref .= ' <div><span>'.constant(strtoupper($key)).'</span><span>'.$arrayAllDataUser[$key].'</span></div> '."\n";
            }
            // ->   hardware config
            if ($arrayListLabel['HARDWARE_CONFIG'] == 'on') {
                $dbsConfig  = ' SELECT name
                                FROM '.$GLOBALS['nuked']['prefix'].'_users_hardware
                                WHERE status = "on" ';
                $dbeConfig  =   mysql_query($dbsConfig);
                $tmpSelect = array();
                unset($dbsConfig);
                while ($data = mysql_fetch_assoc($dbeConfig)) {
                    $arrayAllDataUser[$data['name']] = (isset($arrayAllDataUser[$data['name']])) ? $arrayAllDataUser[$data['name']] : POA;
                    $arrayAllDataUser[$data['name']] = (!empty($arrayAllDataUser[$data['name']])) ? $arrayAllDataUser[$data['name']] : POA;
                    $name = (defined(strtoupper($data['name']))) ? constant(strtoupper($data['name'])) : $data['name'];
                    $tmpDivMaterial .= '    <div data-title="'.$name.'" data-name="'.$data['name'].'">
                                                <span>'.$name.'</span>
                                                <span>'.$arrayAllDataUser[$data['name']].'</span>
                                            </div> '."\n";
                }
            }
            // ->   social networks
            if ($arrayListLabel['SOCIAL_NETWORKS'] == 'on') {
                $dbsSocial  = ' SELECT name
                                FROM '.$GLOBALS['nuked']['prefix'].'_users_social
                                WHERE status = "on" ';
                $dbeSocial  =   mysql_query($dbsSocial);
                unset($dbsSocial);
                while ($data = mysql_fetch_assoc($dbeSocial)) {
                    $tmpSelect[$data['name']] = $data['name'];
                }
                $tmpImplodeSelect  =   implode(', ', $tmpSelect);
                $dbsDataSN  = ' SELECT '.$tmpImplodeSelect.'
                                FROM '.$GLOBALS['nuked']['prefix'].'_users_profils
                                WHERE user_id = "'.$GLOBALS['user']['id'].'" ';
                $dbeDataSN  =   mysql_query($dbsDataSN);
                $dataSN = mysql_fetch_assoc($dbeDataSN);
                unset($dbsDataSN);
                $arrayAllDataUser = array_merge($arrayAllDataUser, $dataSN);
                foreach ($tmpSelect as $key) {
                    $arrayAllDataUser[$key] = (isset($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : POA;
                    $arrayAllDataUser[$key] = (!empty($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : POA;
                    $name = (defined(strtoupper($key))) ? constant(strtoupper($key)) : $key;
                    $tmpDivSocial .= '  <div><span>'.$name.'</span><span>'.$arrayAllDataUser[$key].'</span></div> '."\n";
                }
            }
            // ->   gaming / esport
            if ($arrayListLabel['GAMING'] == 'on') {
                $dbsGaming  = ' SELECT name
                                FROM '.$GLOBALS['nuked']['prefix'].'_users_gaming
                                WHERE status = "on" ';
                $dbeGaming  =   mysql_query($dbsGaming);
                unset($dbsGaming);
                while ($data = mysql_fetch_assoc($dbeSocial)) {
                    $tmpSelect[$data['name']] = $data['name'];
                }
                $tmpImplodeSelect  =   implode(', ', $tmpSelect);
                $dbsDataSN  = ' SELECT '.$tmpImplodeSelect.'
                                FROM '.$GLOBALS['nuked']['prefix'].'_users_profils
                                WHERE user_id = "'.$GLOBALS['user']['id'].'" ';
                $dbeDataSN  =   mysql_query($dbsDataSN);
                $dataSN = mysql_fetch_assoc($dbeDataSN);
                unset($dbsDataSN);
                $arrayAllDataUser = array_merge($arrayAllDataUser, $dataSN);
                foreach ($tmpSelect as $key) {
                    $arrayAllDataUser[$key] = (isset($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : POA;
                    $arrayAllDataUser[$key] = (!empty($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : POA;
                    $name = (defined(strtoupper($key))) ? constant(strtoupper($key)) : $key;
                    $tmpDivGaming .= '  <div><span>'.$name.'</span><span>'.$arrayAllDataUser[$key].'</span></div> '."\n";
                }
            }
            // ->   sql comment
            $dbsCommentLast     = ' SELECT im_id AS id, titre as title, module, date
                                    FROM '.COMMENT_TABLE.'
                                    WHERE autor_id = "'.$GLOBALS['user']['id'].'"
                                    ORDER BY id DESC LIMIT 0, 10 ';
            $dbeCommentLast     =   mysql_query($dbsCommentLast);
            $arrayAllDataUser['numbers_comment'] = mysql_num_rows($dbeCommentLast);
            unset($dbsCommentLast);
            while ($data = mysql_fetch_assoc($dbeCommentLast)) {
                $data['title'] = (empty($data['title'])) ?  $data['module'] : $data['title'];
                $tmpCommentName .= '    <span>
                                            <a href="'.$data['id'].'" title="'.$data['title'].'">
                                                '.$data['title'].'
                                            </a>
                                        </span> '."\n";
                $tmpCommentValue .= '   <span>'.nkDate($data['date'], true).'></span> '."\n";
            }
            if(empty($tmpCommentName)) {
                $tmpCommentName .= '<span>
                                        '.NO_COMMENT_IN_DATABASE.'
                                    </span> '."\n";
                $tmpClassComment = 'nkDisplayNone';
            }
            // ->   sql forum
            $dbsForumLast     = '   SELECT id, titre AS title, date, thread_id, forum_id
                                    FROM '.FORUM_MESSAGES_TABLE.'
                                    WHERE auteur_id = "'.$GLOBALS['user']['id'].'"
                                    ORDER BY id DESC LIMIT 0, 10 ';
            $dbeForumLast     =   mysql_query($dbsForumLast);
            $arrayAllDataUser['numbers_forum'] = mysql_num_rows($dbeForumLast);
            unset($dbsForumLast);
            while ($data = mysql_fetch_assoc($dbeForumLast)) {
                $data['title'] = (empty($data['title'])) ?  $data['module'] : $data['title'];
                $tmpForumName .= '  <span>
                                        <a href="'.$data['id'].'" title="'.$data['title'].'">
                                            '.$data['title'].'
                                        </a>
                                    </span> '."\n";
                $tmpForumValue .= ' <span>'.nkDate($data['date'], true).'></span> '."\n";
            }
            if(empty($tmpForumName)) {
                $tmpForumName .= '  <span>
                                        '.NO_POST_IN_DATABASE.'
                                    </span> '."\n";
                $tmpClassForum = 'nkDisplayNone';
            }
            // ->   sql stats
            foreach ($arrayStats as $key) {
                $arrayAllDataUser['date_of_arrival'] = $arrayAllDataUser['date_of_arrival_small'];
                $name = (defined(strtoupper($key))) ? constant(strtoupper($key)) : $key;
                $arrayAllDataUser[$key] = (isset($arrayAllDataUser[$key])) ? $arrayAllDataUser[$key] : UNKNOWN;
                $tmpStatsName .= '  <span>'.$name.'</span> '."\n";
                if ($key == 'ip') {
                    $tmpStatsValue .= ' <a class="tipE" href="#" original-title="'.$arrayAllDataUser[$key].'"><span class="icon-cog"></span></a> '."\n";
                }
                else if ($key == 'administrators') {
                    if (nkHasAdmin()) {
                        $tmpStatsValue .= ' <span>'.YES.'</span> '."\n";
                    }
                    else {
                        $tmpStatsValue .= ' <span>'.NO.'</span> '."\n";
                    }
                }
                else if ($key == 'groups') {
                    //$nameGroups
                    $tmpGroups = explode('|', $arrayAllDataUser[$key]);
                    foreach ($tmpGroups as $key) {
                        $tmpTitleGroup .= $nameGroups[$key]['name'].'<br> '."\n";
                    }
                    $tmpStatsValue .= ' <a class="tipE" href="#" original-title="'.$tmpTitleGroup.'"><span class="icon-cog"></span></a> '."\n";
                } else {
                    $tmpStatsValue .= ' <span>'.$arrayAllDataUser[$key].'</span> '."\n";
                }
            }
?>
                    <div id="usersContent">
                        <?php subMenu(true, true); ?>
                        <div class="column">
                            <div>
                                <form id="infosGen">
                                    <h3>Informations générales</h3>
                                    <div class="title">Administrateur</div>
                                    <img src="http://www.palacewar.eu/Stive/avatar_stive2.png" alt="#">
                                    <div class="contentInfos">
                                        <?php echo $tmpDivGen; ?>
                                    </div>
                                </form>
                                <form id="config">
                                    <h3>Préférence</h3>
                                    <div class="contentInfos">
                                        <?php echo $tmpDivPref; ?>
                                    </div>
                                </form>
<?php
                                if ($arrayListLabel['HARDWARE_CONFIG'] == 'on'):
?>
                                <form id="config">
                                    <h3><?php echo HARDWARE_CONFIG; ?></h3>
                                    <div class="contentInfos">
                                        <?php echo $tmpDivMaterial; ?>
                                    </div>
                                </form>
<?php
                                endif;
?>

                            </div>

                            <div>
                                <div>
<?php
                                if ($arrayListLabel['SOCIAL_NETWORKS'] == 'on'):
?>
                                    <form id="config">
                                        <h3><?php echo SOCIAL_NETWORKS; ?></h3>
                                        <div class="contentInfos">
                                            <?php echo $tmpDivSocial; ?>
                                        </div>
                                    </form>
<?php
                                endif;

                                if ($arrayListLabel['GAMING'] == 'on'):
?>
                                    <form id="gaming">
                                        <h3><?php echo GAMING; ?></h3>
                                        <div class="contentInfos">
                                            <?php echo $tmpDivGaming; ?>
                                        </div>
                                    </form>
<?php
                                endif;
?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <aside>
                        <div id="tab-container" class="tab-container">
                            <ul>
                                <li><a href="#Stats">Statistiques</a></li>
                                <li><a href="#Msgs">Messages</a></li>
                                <li><a href="#Comms">Commentaires</a></li>
                            </ul>
                            <div id="Stats">
                                <div class="name">
                                    <?php echo $tmpStatsName; ?>
                                </div>
                                <div class="value">
                                    <?php echo $tmpStatsValue; ?>
                                </div>
                            </div>
                            <div id="Msgs">
                                <div class="name">
                                    <?php echo $tmpForumName; ?>
                                </div>
                                <div class="value <?php echo $tmpClassForum; ?>">
                                    <?php echo $tmpForumValue; ?>
                                </div>
                            </div>
                            <div id="Comms">
                                <div class="name">
                                    <?php echo $tmpCommentName; ?>
                                </div>
                                <div class="value <?php echo $tmpClassComment; ?>">
                                    <?php echo $tmpCommentValue; ?>
                                </div>
                            </div>
                        </div>
                    </aside>
<?php
        else:
            redirect("index.php?file=User&op=login_screen", 0);
        endif;
    }

    function accountOption () {
        $tmpOnglet = '';
        // ->   sql select label (on-off)
        $dbsLabel       = ' SELECT name, status
                            FROM '.$GLOBALS['nuked']['prefix'].'_users_label ';
        $dbeLabel       =   mysql_query($dbsLabel);
        unset($dbsLabel);
        while ($data        = mysql_fetch_assoc($dbeLabel)) {
            $data['name'] = (defined(strtoupper($data['name'] ))) ? constant(strtoupper($data['name'] )) : $data['name'] ;
            $tmpOnglet .= ' <div>
                                <span>
                                    <input type="checkbox" id="'.$data['name'].'" class="regular-checkbox" />
                                    <label for="'.$data['name'].'">
                                </span>
                                <span>'.$data['name'].'</span>
                            </div> '."\n";
        }
?>
                    <div id="usersContent">
                        <?php subMenu(false, true); ?>
                        <div class="column">
                            <div>
                                <form id="config">
                                    <h3>Option du compte</h3>
                                    <div class="contentInfosSubpage">
                                        <div>
                                            <span>
                                                <input type="checkbox" id="checkbox-1-1" class="regular-checkbox" />
                                                <label for="checkbox-1-1">
                                            </span>
                                            <span>Garder mon courriel privé</span>
                                        </div>
                                        <div>
                                            <span>
                                                <input type="checkbox" id="checkbox-1-2" class="regular-checkbox" />
                                                <label for="checkbox-1-2">
                                            </span>
                                            <span>Mode invisible</span>
                                            <p>Le mode invisible vous permet de naviguer sur le forum sans apparaitre</p>
                                            <p>dans la liste des utilisateurs connectés.</p>
                                            <p>Cependant vous resterais visible pour les administrateurs et modérateurs.</p>
                                        </div>
                                    </div>
                                </form>
                                <form id="config">
                                    <h3>Messagerie privé</h3>
                                    <div class="contentInfosSubpage">
                                        <div>
                                            <span>
                                                <input type="checkbox" id="checkbox-1-2" class="regular-checkbox" />
                                                <label for="checkbox-1-2">
                                            </span>
                                            <span>Messagerie privé</span>
                                            <p>Active ou désactive la messagerie privé.</p>
                                        </div>
                                        <div>
                                            <span>
                                                <input type="checkbox" id="checkbox-1-2" class="regular-checkbox" />
                                                <label for="checkbox-1-2">
                                            </span>
                                            <span>Messagerie privé restreint</span>
                                            <p>Recevoir uniquement des messages privés de mes amis et des modérateur.</p>
                                        </div>
                                        <div>
                                            <span>
                                                <input type="checkbox" id="checkbox-1-2" class="regular-checkbox" />
                                                <label for="checkbox-1-2">
                                            </span>
                                            <span>Notifications nouveaux messages privés</span>
                                            <p>Le site peut vous envoyer un message sur votre adresse email afin de vous</p>
                                            <p>informer losque quelqu'un vous à envoyé un message privé</p>
                                        </div>
                                        <div>
                                            <span>
                                                <input type="checkbox" id="checkbox-1-2" class="regular-checkbox" />
                                                <label for="checkbox-1-2">
                                            </span>
                                            <span>Supprimer automatiquement les messages</span>
                                            <p>Les messages seront supprimer automatiquement au bout de 90 jours</p>
                                        </div>
                                    </div>
                                </form>
                                <form id="config">
                                    <h3>Informations personnelles</h3>
                                    <div class="contentInfosSubpage">
                                        <div>
                                            <span>
                                                <input type="checkbox" id="checkbox-1-1" class="regular-checkbox" />
                                                <label for="checkbox-1-1">
                                            </span>
                                            <span>Visibilité profil</span>
                                            <p>Afficher les informations de votre profil uniquement à vos amis</p>
                                        </div>
                                        <div>
                                            <span>
                                                <input type="checkbox" id="checkbox-1-2" class="regular-checkbox" />
                                                <label for="checkbox-1-2">
                                            </span>
                                            <span>Profil restreint</span>
                                            <p>Afficher les informations de votre profil uniquement à vos amis</p>
                                            <p>et aux utilisateurs enregistrés</p>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div>
                                <form id="config">
                                    <h3>Onglets</h3>
                                    <div class="contentInfosSubpage">
                                        <div>
                                            <span class="marginNull">
                                                <p>Décidez quels onglets vous voulez faire apparaître quand un utilisateur</p>
                                                <p>consulté votre compte</p>
                                                <p>Les onglets que vous avez décocher resteront visibles quand vous editez</p>
                                                <p>votre compte</p>
                                            </span>
                                        </div>
                                        <?php echo $tmpOnglet; ?>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
<?php
    }
    function template ($page = null) {
        // ->   sql select template
        $dbsTpl       = '   SELECT user_theme AS template
                            FROM '.USERS_TABLE.' ';
        $dbeTpl       =     mysql_query($dbsTpl);
        unset($dbsTpl);
        $data         =     mysql_fetch_assoc($dbeTpl);

        $currentTemplate = (empty($data['template'])) ? $GLOBALS['nuked']['theme'] : $data['template'];
        //$dom = new DomDocument();
        //$dom->load('fichier.xml');

        $folderTheme     = 'themes';
        $folderTheme     = opendir($folderTheme) or die(ERROR_DIRECTORY);
        $arrayTheme      = array(); // on déclare le tableau contenant le nom des fichiers
        $folder          = array(); // on déclare le tableau contenant le nom des dossiers
        $optionTheme     = array();
        $i               = 1;
        $tmpClassMax     = '';
        $tmpClassCurrent = '';

        while ($TpmFolderTheme = readdir($folderTheme)):
            if ($TpmFolderTheme != '.' && $TpmFolderTheme != '..') {
                if (!is_dir($folderTheme.'/'.$TpmFolderTheme)) {
                    $arrayTheme[] = $TpmFolderTheme;
                }
                else {
                    $folder[] = $TpmFolderTheme;
                }
            }
        endwhile;
        closedir($folderTheme);
        if (!empty($arrayTheme)):
            sort($arrayTheme); // pour le tri croissant, rsort() pour le tri décroissant
            foreach ($arrayTheme as $nameTheme) {
                $optionTheme[$i++]  =  $nameTheme;
            }
        endif;
        $maxPage  = $i-1;
        $prevPage = $i - 2;
        if ($i > $maxPage) {
            $i = null;
        }
/**
A REVOIR ! la function page et le reste !
*/
        if (in_array($currentTemplate, $optionTheme)) {
            $currentPage = $optionTheme[$currentTemplate];
        }
        if ($currentPage >= $maxPage) {
            $tmpClassMax = 'off';
        }
        if ($currentPage <= 0) {
            $tmpClassCurrent = 'off';
        }
        if ($page === null) {
            debug($optionTheme);
        }
?>
                    <div id="usersContent">
                        <?php subMenu(false, false); ?>
                        <div class="full page">
                            <p>Page <?php echo $currentPage; ?> sur <?php echo $maxPage; ?></p>
                            <div id="page">
                                <a class="tipE icon-chevron-left <?php echo $tmpClassCurrent; ?>" data-id="<?php echo $prevPage; ?>" href="#template" title="Précédant"></a>
                                <a class="tipW icon-chevron-right <?php echo $tmpClassMax; ?>" data-id="<?php echo $i; ?>" href="#template" title="Suivant"></a>
                            </div>
                        </div>
                        <div class="columnTemplate">
                            <div>
                                <span>Theme</span>
                                <span>Restless</span>
                                <span>Auteur</span>
                                <span>Homax</span>
                            </div>
                            <div>
                                <span>Date</span>
                                <span>03/12/2013</span>
                                <span>Version</span>
                                <span>1.0</span>
                            </div>
                        </div>
                    </div>
<?php
    }
    function privateMsg () {
?>
            <aside class="userbox">
                <div>
                    <p>Espace utilisé 12%</p>
                    <div class="progressBar">
                        <span data-pourcent="12"></span>
                    </div>
                </div>
            </aside>

            <article class="userbox">
                test
            </article>
<?php
    }

    function subMenu ($oldName, $ddclick) {
?>
                        <div class="full">
                            <ul>
                                <li>
                                    <a class="tipE jqueryLinksSwtich" data-icon="icon-home" href="#home" data-title="Accueil" title="Accueil">
                                        <i class="icon-menu"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="tipS jqueryLinksSwtich" title="Template" data-icon="icon-insert-template" data-title="Template" href="#template">
                                        <i class="icon-insert-template"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="tipN" href="#" title="User">
                                        <i class="icon-users"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="tipW jqueryLinksSwtich" title="Option du compte" data-icon="icon-cog" data-title="Option du compte" href="#accountOption">
                                        <i class="icon-cog"></i>
                                    </a>
                                </li>
                            </ul>
<?php
                            if ($oldName === true):
?>
                            <ul>
                                <li><?php echo HISTORY_NICKNAME_USED; ?></li>
<?php
                                if (empty($GLOBALS['arrayUserInfos']['oldPseudo'])):
?>
                                <li><?php echo NO_OLD_NICKNAME; ?></li>
<?php
                                else:
                                foreach ($GLOBALS['arrayUserInfos']['oldPseudo'] as $key):
?>
                                    <li><?php echo $key; ?></li>
<?php
                                endforeach;
                                endif;
?>
                            </ul>
<?php
                            endif;
?>
<?php
                            if ($ddclick === true):
?>
                            <div class="icon-info"><?php echo DD_CLICK_TO_EDIT; ?></div>
<?php
                            endif;
?>
                        </div>
<?php
    }
    function saveJquery () {
        // variables
        $optTmp = '';
        // liste les request autorisé
        $arrayRequest = array('name');
        foreach ($arrayRequest as $key) {
            if (!array_key_exists($key, $_REQUEST)) {
                $_REQUEST[$key] = '';
            }
        }
        if (!nkHasVisitor()) { require_once'modules/User/array.php'; }

        if (array_key_exists($_REQUEST['name'], $arrayListMaterial)) {
            $select = $arrayListMaterial[$_REQUEST['name']];
            // ->   sql select data $_REQUEST['name']
            $dbsUser            = ' SELECT '.$select.'
                                    FROM   '.USERS_DETAIL_TABLE.'
                                    WHERE  user_id  = "'.$GLOBALS['user']['id'].'" ';
            $dbeUser            =   mysql_query($dbsUser);
            unset($dbsUser);
            $value   =   mysql_fetch_assoc($dbeUser);
            $placeholder = (defined(strtoupper($_REQUEST['name']))) ? constant(strtoupper($_REQUEST['name'])) : '' ;
            if ($_REQUEST['name'] == 'os') {
                foreach ($arraySystemOs as $key) {
                    $optTmp .= '<option value="'.$key.'">'.$key.'</option> '."\n";
                }
                $input = '  <select data-placeholder="Chosisez votre OS" class="jQueryChosen">
                                <option value="'.$value[$select].'">'.$value[$select].'</option>
                                '.$optTmp.'
                            </select>';
            }
            else {
                $input = '<input name="pseudo" placeholder="'.$placeholder.'" type="text" value="'.$value[$select].'">';
            }
        }
?>
        <script src="assets/scripts/jquery.chosen.min.js"></script>
        <form type="post" id="formUsers" action="index.php?file=User&amp;nuked_nude=index&amp;op=sendLogin">
            <fieldset>
                <div class="inputSaveJquery">
                    <?php echo $input; ?>
                </div>
            </fieldset>
            <div>
                <input class="ui-button ui-button-blue" type="submit" value="Sauvegarder" />
            </div>
        </form>
<?php
    }
    function formLogin () {
        if (nkHasVisitor()) {
?>
                <form type="post" id="formUsers" action="index.php?file=User&amp;nuked_nude=index&amp;op=sendLogin">
                    <fieldset>
                        <legend>Login Information</legend>
                        <div>
                            <span class="icon user"></span>
                            <input required="required" name="pseudo" placeholder="Votre Pseudo" type="text" />
                        </div>
                        <div>
                            <span class="icon password"></span>
                            <input name="password" required="required" placeholder="Votre Mot De Passe" type="password" />
                        </div>
                    </fieldset>
                    <div>
                        <input type="checkbox" id="rememberMe" name="rememberMe" value="true" />
                        <label data-value="&#8730;" for="rememberMe">
                            <i>Se souvenir de moi</i>
                        </label>
                        <label class="nkFloatRight">
                            <i id="passLost" class="nkFloatRight" data-href="index.php?file=User&anmp;op=oubli_pass">Mot de passe oublié</i>
                        </label>
                    </div>
                    <div>
                        <input class="ui-button ui-button-blue" type="submit" value="Ok" />
                        <input type="hidden"  name="jQuery" value="jQuery" />
                    </div>
                </form>
<?php
        }
    }

    function sendLogin () {
        // Liste les request autorisé
        $arrayRequest = array('pseudo', 'password', 'rememberMe', 'errorLogin', 'type', 'jQuery');
        foreach ($arrayRequest as $key) {
            if (!array_key_exists($key, $_REQUEST)) {
                $_REQUEST[$key] = '';
            }
        }
        // Retro compatiblité
        if(isset($_REQUEST['pass'])) {
            $_REQUEST['password'] = $_REQUEST['pass'];

        }
        if(isset($_REQUEST['remember_me'])) {
            $_REQUEST['rememberMe'] = $_REQUEST['remember_me'];
        }
        // Sécurité
        $_CLEAN['pseudo'] = nkHtmlEntities($_REQUEST['pseudo']);
        // Rester Logué
        if ($_REQUEST['rememberMe'] == 'true') {
            $_CLEAN['rememberMe'] = true;
        }
        else {
            $_CLEAN['rememberMe'] = false;
        }
        // SQL
        $dbsTestName = 'SELECT id AS idUser, count(id) AS countUser, pass AS userPassword,
                               user_theme AS userTheme, user_langue AS userLang, erreur AS error,
                            (
                                SELECT count(id)
                                FROM '.BANNED_TABLE.'
                                WHERE pseudo = "'.$_CLEAN['pseudo'].'"
                            ) AS countBan
                        FROM '.USERS_TABLE.'
                        WHERE pseudo = "'.$_CLEAN['pseudo'].'" ';
        $dbeTestName = mysql_query($dbsTestName) or die(mysql_error());
        $testName    = mysql_fetch_assoc($dbeTestName);
        // Définit les erreurs a 0
        $errors = 0;
        // test si user existe
        if ($testName['countUser'] == 0) {
            $errors++;
            $errorMsg     = nkUtf8Encode(ERROR_LOGIN);
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = '';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Teste si l'user est pas Banni
        else if ($testName['countBan'] > 0) {
            $errors++;
            $errorMsg     = ERROR_PSEUDO_BANNED;
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = 'index.php';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Teste si le pseudo & pass ne sont pas vide
        else if (empty($_CLEAN['pseudo']) &&
                 empty($_REQUEST['password'])
           ) {
            $errors++;
            $errorMsg     = nkUtf8Encode(ERROR_EMPTY_FIELD);
            $bgClass      = 'nkSiteEmptyLogged';
            $redirectLink = '';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        /**
        ** @todo
        ** -> Rajouter la verification sur la validation [Ancien level 0]
        */
        // Si il a aucune erreur
        else if ($errors == 0) {
            // Verifie le mot de passe ( Mauvais )
            if (!Check_Hash($_REQUEST['password'], $testName['userPassword'])) {
                // ajoute +1 a la variable error
                $testName['error'] = $testName['error'] + 1;
                // SQL
                $dbuError = ' UPDATE '.USERS_TABLE.'
                              SET erreur = '.$testName['error'].'
                              WHERE pseudo = '.$_CLEAN['pseudo'].' ';
                $dbeError =   mysql_query($dbuError);

                $errorMsg     =  nkUtf8Encode(ERROR_LOGIN);
                $bgClass      = 'nkSiteErrorLogged';
                $data = array(
                   'errorMsg'       => $errorMsg,
                   'bgClass'        => $bgClass,
                   'redirectLink'   => '',
                   'redirectedName' => REDIRECTED
                );
            }
            // Verifie le mot de passe ( Bon )
            else {
                // SQL
                $dbuUser  = ' UPDATE '.USERS_TABLE.'
                              SET erreur = 0
                              WHERE pseudo = '.$_CLEAN['pseudo'].' ';
                $dbeUser  =   mysql_query($dbuUser);
                // Crée une nouvelle session
                sessionNew($testName['idUser'], $_CLEAN['rememberMe']);

                if (!empty($testName['userTheme'])) {
                    setcookie($GLOBALS['cookieTheme'], $testName['userTheme'], $GLOBALS['timelimit']);
                }

                if (!empty($testName['userLang'])) {
                    setcookie($GLOBALS['cookieLang'], $testName['userLang'], $GLOBALS['timelimit']);
                }

                $_SESSION['admin'] = false;

                $redirectLink = 'index.php';
                $bgClass      = 'nkSiteLoginOk';
                $data = array(
                   'errorMsg'       => nkUtf8Encode(LOGIN_PROGRESS),
                   'bgClass'        => $bgClass,
                   'redirectLink'   => $redirectLink,
                   'redirectedName' => REDIRECTED
                );
            }
        }

        if (isset($_REQUEST['jQuery']) && !empty($_REQUEST['jQuery'])):
            echo json_encode($data);
        else:
?>
            <!doctype html>
            <html lang="fr">
                <head>
                    <meta charset="Windows-1252">
                    <title><?php echo $GLOBALS['nuked']['name']; ?> - Login</title>
                    <link  rel="stylesheet" href="assets/css/nkDefault.css" />
                    <link  rel="stylesheet" href="themes/<?php echo $GLOBALS['theme']; ?>/style.css" />
                </head>
                <body style="background:<?php echo $GLOBALS['bgcolor1']; ?>;">
                    <div id="nkSiteClosedWrapper" style=" border: 1px solid <?php echo $GLOBALS['bgcolor3']; ?>; background:<?php echo $GLOBALS['bgcolor2']; ?>;">
                        <h1><?php echo $GLOBALS['nuked']['name']; ?> - <?php echo $GLOBALS['nuked']['slogan']; ?></h1>
                        <p><?php echo $data['errorMsg']; ?></p>
                    </div>
                </body>
            </html>
<?php
        redirect("index.php", 2);
        endif;
    }

    function formLogout () {
?>
                <form type="post" id="formUsers" action="index.php?file=User&amp;nuked_nude=index&amp;op=sendLogout">
                    <fieldset>
                        <span>Confimer votre déconnexion</span>
                    </fieldset>
                    <div>
                        <input class="ui-button ui-button-blue" type="submit" value="Je confirme" />
                    </div>
                </form>
<?php
    }

    function sendLogout () {

        $dbuLogOut = '  UPDATE '.SESSIONS_TABLE.'
                        SET ip = ""
                        WHERE userId = "'.$GLOBALS['user']['id'].'"';
        $dbeLogOut = mysql_query($dbuLogOut);

        setcookie($GLOBALS['cookieSession'], '', time() - 3600);
        setcookie($GLOBALS['cookieUserId'],  '', time() - 3600);
        setcookie($GLOBALS['cookieTheme'],   '', time() - 3600);
        setcookie($GLOBALS['cookieLang'],    '', time() - 3600);
        setcookie($GLOBALS['cookieForum'],   '', time() - 3600);
        $_SESSION['admin'] = false;

        $redirectLink = 'index.php';
        $bgClass      = 'nkSiteClose';
        $data = array(
           'errorMsg'       => nkUtf8Encode(USERLOGOUTINPROGRESS), // function a faire utf8_encode + nkHtmlEntityDecode
           'bgClass'        => $bgClass,
           'redirectLink'   => $redirectLink,
           'redirectedName' => REDIRECTED
        );
        echo json_encode($data);
    }

    function formRegister() {
        if (!empty($GLOBALS['nuked']['inscription_charte']) && isset($GLOBALS['nuked']['inscription_charte'])) {
            $_CLEAN['disclaimer'] = nkHtmlEntityDecode($GLOBALS['nuked']['inscription_charte']);
            $viewDisclaimer = ' <fieldset>
                                    <legend>Disclaimer</legend>
                                    <div class="textarea">
                                        '.$_CLEAN['disclaimer'].'
                                    </div>
                                </fieldset>';
            $valueSubmit    = 'Accepter et s\'inscrire';
        } else {
            $viewDisclaimer = '';
            $valueSubmit    = 'Inscription';
        }
        if ($GLOBALS['nuked']['inscription'] != "mail") {
            $viewPassword   = ' <div>
                                    <span class="icon password"></span>
                                    <input name="password" required="required" placeholder="Votre Mot De Passe" type="password" />
                                </div>
                                <div>
                                    <span class="icon password"></span>
                                    <input name="passwordConfirm" required="required" placeholder="Confirmer Votre Mot De Passe" type="password" />
                                </div>';
        }
        else {
            $viewPassword    = '';
        }
?>
                <form type="post" id="formUsers" action="index.php?file=User&amp;nuked_nude=index&amp;op=sendRegister">
                    <?php echo $viewDisclaimer; ?>
                    <fieldset>
                        <legend>Personal Information</legend>
                        <div>
                            <span class="icon user"></span>
                            <input required="required" name="pseudo" placeholder="Votre Pseudo" type="text" />
                        </div>
                        <?php echo $viewPassword; ?>
                        <div><span class="icon mail"></span>
                            <input name="privateMail" required="required" placeholder="Votre courriel privé" type="email" />
                        </div>
                    </fieldset>
                    <div>
                        <input class="ui-button ui-button-blue" type="submit" value="<?php echo $valueSubmit; ?>" />
                    </div>
                </form>
<?php
    }

    function sendRegister() {
        // Liste les request autorisé
        $arrayRequest = array('pseudo', 'password', 'privateMail');
        foreach ($arrayRequest as $key) {
            if (!array_key_exists($key, $_REQUEST)) {
                $_REQUEST[$key] = '';
            }
        }

        $arraySecurity = array(
                        'pseudo'          => $_REQUEST['pseudo'],
                        'password'        => $_REQUEST['password'],
                        'passwordConfirm' => $_REQUEST['passwordConfirm'],
                        'privateMail'     => $_REQUEST['privateMail']
                        );
        foreach ($arraySecurity as $key => $value) {
            $value        = mysql_real_escape_string(stripslashes($value));
            $_CLEAN[$key] = nkHtmlEntities($value);
        }
        // unique user id
        do {
            $userId = substr(sha1(uniqid()), 0, 20);
            $sql = mysql_query('SELECT * FROM ' . USERS_TABLE . ' WHERE id=\'' . $userId . '\'');
        } while (mysql_num_rows($sql) != 0);
        // validation automatique
        if ($GLOBALS['nuked']['validation'] == "auto"){
            $ids_group = 2;
            $main_group = 2;
        }
        else {
            $ids_group = 3;
            $main_group = 3;
        }
        // Validation mail
        if ($GLOBALS['nuked']['inscription'] == "mail") {
            $_CLEAN['password']        = generatePasswd();
            $_CLEAN['passwordConfirm'] = $_CLEAN['password'];
        }
        // Date
        $date = time();
        // SQL
        $dbsTestName = 'SELECT count(id) AS countUser,
                            (
                                SELECT count(id)
                                FROM '.USERS_TABLE.'
                                WHERE mail = "'.$_CLEAN['privateMail'].'"
                            ) AS countMail,
                            (
                                SELECT count(id)
                                FROM '.BANNED_TABLE.'
                                WHERE pseudo = "'.$_CLEAN['pseudo'].'"
                            ) AS countBan,
                            (
                                SELECT count(id)
                                FROM '.BANNED_TABLE.'
                                WHERE email = "'.$_CLEAN['privateMail'].'"
                            ) AS countBanMail
                        FROM '.USERS_TABLE.'
                        WHERE pseudo = "'.$_CLEAN['pseudo'].'" ';
        $dbeTestName = mysql_query($dbsTestName) or die(mysql_error());
        $testName    = mysql_fetch_assoc($dbeTestName);
        // Définit les erreurs a 0
        $errors = 0;
        // test si user existe
        if ($testName['countUser'] != 0) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_REG_LOGIN);
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = '';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Teste si l'user est pas Banni
        else if ($testName['countBan'] > 0) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_PSEUDO_BANNED);
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = 'index.php';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Test si l'email est pas banni
        else if ($testName['countBanMail'] > 0) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_MAIL_BANNED);
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = 'index.php';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Teste si le pseudo & pass ne sont pas vide
        else if (empty($_CLEAN['pseudo']) &&
                 empty($_CLEAN['password']) &&
                 empty($_CLEAN['passwordConfirm'])
                ) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_EMPTY_FIELD);
            $bgClass      = 'nkSiteEmptyLogged';
            $redirectLink = '';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Teste le taille du pseudo (limite 25 caractère)
        else if (strlen($_CLEAN['pseudo']) > 25) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(NICK_TO_LONG);
            $bgClass      = 'nkSiteEmptyLogged';
            $redirectLink = '';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Teste si les deux pass sont identique
        else if ($_CLEAN['password'] != $_CLEAN['passwordConfirm']) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_TWO_PASS_FAIL);
            $bgClass      = 'nkSiteEmptyLogged';
            $redirectLink = '';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Teste si email est déjà utilise
        else if ($testName['countMail'] > 0) {
            $errors++;
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = '';

            $data = array(
               'errorMsg'       => nkUtf8Encode(ERROR_MAIL_USE),
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Aucune erreur
        else if ($errors == 0) {
        // password crypte
            $cryptPass = nk_hash($_CLEAN['passwordConfirm']);
            /**
            * @todo
            * a delete $niveau
            */
            $niveau = 9;
            /**
            * @todo
            * a delete $niveau
            */
            $dbiUser = '    INSERT INTO '.USERS_TABLE.' (
                                                            `id` , `team` , `team2` ,`team3` , `rang` ,
                                                            `ordre` , `pseudo` , `mail` , `email` ,
                                                            `url` , `pass` ,
                                                            `niveau` , `date` , `avatar` , `signature` , `user_theme` ,
                                                            `user_langue` , `game` , `country` , `count`, `erreur` ,
                                                            `token` , `token_time` , `ids_group` , `main_group` , `visitedProfil` ,
                                                            `oldPseudo`
                                                        )
                            VALUES                      (
                                                            "'.$userId.'" , "" , "" , "" , "" ,
                                                            "" , "'.$_CLEAN['pseudo'].'" , "'.$_CLEAN['privateMail'].'" , "" ,
                                                            "" , "'.$cryptPass.'" ,
                                                            "'.$niveau.'" , "'.$date.'" , "" , "" , "" ,
                                                            "" , "" , "Belgium.gif" , "" , "",
                                                            "" , "" , "'.$ids_group.'" , "'.$main_group.'" , "" ,
                                                            ""
                                                        )';
            $dbeUser =   mysql_query($dbiUser);

            $dbiUserDetail = '  INSERT INTO '.USERS_DETAIL_TABLE.'  (
                                                                        `user_id`, `prenom`, `age`, `sexe`, `ville`,
                                                                        `photo`, `motherboard`, `cpu`, `ram`, `video`,
                                                                        `resolution`, `son`, `ecran`, `souris`, `clavier`,
                                                                        `connexion`, `system`, `pref_1`, `pref_2`, `pref_3`,
                                                                        `pref_4`, `pref_5`)
                                VALUES                              (
                                                                        "'.$userId.'", "", "", "", "",
                                                                        "", "", "", "", "",
                                                                        "", "", "", "", "",
                                                                        "", "", "", "", "",
                                                                        "", ""
                                                                    )';
/*
            $dbeUserDetail =   mysql_query($dbiUserDetail);


            $tmpTableUsersProfils = '';
            $i = 0;

            $arrayUserProfil = explode(",", $GLOBALS['tableShowColoumn']);

            $nbProfilFields = count($arrayUserProfil);

            $tmpTableUsersProfils = substr($tmpTableUsersProfils, 0, -1);

            debug($tmpTableUsersProfils);
            debug($i);

            $dbiUserDetail = '  INSERT INTO '.USERS_PROFILS.'
                                VALUE                          ("'.$userId.'", "", )';

*/
            // Login automatique
            if ($GLOBALS['nuked']['inscription'] != 'mail' && $GLOBALS['nuked']['validation'] == 'auto') {
                $dbsUser = '    SELECT id AS idUser, user_theme AS userTheme, user_langue AS userLang
                                FROM '.USERS_TABLE.'
                                WHERE pseudo = "'.$_CLEAN['pseudo'].'" ';
                $dbeUser =      mysql_query($dbsUser) or die(mysql_error());
                $arrayUser =    mysql_fetch_assoc($dbeUser);

                sessionNew($arrayUser['idUser'], true);

                if (!empty($arrayUser['userTheme'])) {
                    setcookie($GLOBALS['cookieTheme'], $arrayUser['userTheme'], $GLOBALS['timelimit']);
                }

                if (!empty($arrayUser['userLang'])) {
                    setcookie($GLOBALS['cookieLang'], $arrayUser['userLang'], $GLOBALS['timelimit']);
                }
            }

            $redirectLink = 'index.php';
            $errorMsg     =  nkUtf8Encode(REG_PROGRESS);
            $bgClass      = 'nkSiteLoginOk';
            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        // Renvoye au format json les données
        echo json_encode($data);

    }

    function formLostPassword () {
?>
                <form type="post" id="formUsers" action="index.php?file=User&amp;nuked_nude=index&amp;op=sendLostPassword">
                    <fieldset>
                        <legend>Mot de passe oublié</legend>
                        <div>
                            <span class="icon mail"></span>
                            <input name="mail" required="required" placeholder="Votre courriel" type="email" />
                        </div>
                    </fieldset>
                    <div>
                        <input class="ui-button ui-button-blue" type="submit" value="Envoyer" />
                    </div>
                </form>
<?php
    }

    function sendLostPassword () {
        // Liste les request autorisé
        $arrayRequest = array('mail');
        foreach ($arrayRequest as $key) {
            if (!array_key_exists($key, $_REQUEST)) {
                $_REQUEST[$key] = '';
            }
        }
        // Sécurity
        $_CLEAN['mail'] = mysql_real_escape_string($_REQUEST['mail']);
        // SQL
        $dbsTestName = 'SELECT token, token_time AS tokenTime,
                            (
                                SELECT count(id)
                                FROM '.USERS_TABLE.'
                                WHERE mail = "'.$_CLEAN['mail'].'"
                            ) AS countMail,
                            (
                                SELECT count(id)
                                FROM '.BANNED_TABLE.'
                                WHERE email = "'.$_CLEAN['mail'].'"
                            ) AS countBan
                        FROM '.USERS_TABLE.'
                        WHERE mail = "'.$_CLEAN['mail'].'" ';
        $dbeTestName = mysql_query($dbsTestName) or die(mysql_error());
        $testName    = mysql_fetch_assoc($dbeTestName);
        // Variables
        $errors  = 0;
        $pattern = '#^[a-z0-9]+[a-z0-9._-]*@[a-z0-9.-]+.[a-z0-9]{2,3}$#';
        $msg     = '';
        // Errors
        if (empty($_CLEAN['mail'])) {
            $errors++;
            $errorMsg     =  nkHtmlEntities(REG_PROGRESS);
            $bgClass      = 'nkSiteLoginError';
            $data = array(
               'errorMsg'       => nkUtf8Encode(ERROR_EMPTY_FIELD),
               'bgClass'        => $bgClass,
               'redirectLink'   => '',
               'redirectedName' => REDIRECTED
            );
        }
        else if (!preg_match($pattern, $_CLEAN['mail']) OR $testName['countMail'] == 0) {
            $errors++;
            $errorMsg     =  nkHtmlEntities(REG_PROGRESS);
            $bgClass      = 'nkSiteLoginError';
            $data = array(
               'errorMsg'       => nkUtf8Encode(ERROR_WRONG_MAIL),
               'bgClass'        => $bgClass,
               'redirectLink'   => '',
               'redirectedName' => REDIRECTED
            );
        }
        else if ($testName['countBan'] > 0) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_MAIL_BANNED);
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = 'index.php';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }

        else if($testName['token'] != null && (time() - $testName['tokenTime']) < 3600) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_TOKEN_ACTIVE);
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = 'index.php';
            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }

        else if ($errors == 0) {
            $newToken = uniqid();

            $dbsName = '    SELECT pseudo
                            FROM '.USERS_TABLE.'
                            WHERE mail = "'.$_CLEAN['mail'].'" ';
            $dbeName = mysql_query($dbsName) or die(mysql_error());
            $user    = mysql_fetch_assoc($dbeName);

            $dbuLogOut = '  UPDATE '.USERS_TABLE.'
                            SET token = "'.$newToken.'", token_time = "'.time().'"
                            WHERE mail = "'.$_CLEAN['mail'].'" ';
            $dbeLogOut = mysql_query($dbuLogOut);
            $link = '<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?file=User&op=sendPass&mail='.$_CLEAN['mail'].'&token='.$newToken.'">http://'.$_SERVER['SERVER_NAME'].'/index.php?file=User&op=sendPass&mail='.$_CLEAN['mail'].'&token='.$newToken.'</a>';

            $msg = '    <html>
                            <body>
                                <div style="background:#EEEEEE;">
                                    <table align="center" style="background:#ffffff;width:100%;" border="0" cellspacing="10" cellpadding="0">
                                        <tr style="background:#f2f2f2;color:#666666;text-align:center;border-bottom:1px solid #ccc;font-size:16px;">
                                            <td><strong>'.TITLE_MAIL.'</strong></td>
                                        </tr>
                                        <tr style="margin-top:5px;margin-bottom:5px;"><td>
                                            <table align="center" style="width:85%; line-height:24px; padding:5px; border-radius:3px; margin:15px auto;border:1px solid #DADADA" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <p>'.TEXT_HELLO.' <strong>'.$user['pseudo'].',</strong></p>
                                                        <p>'.LINK_TO_NEW_PASS.'</p>
                                                        <p align="center">'.$link.'</p>
                                                        <p>'.TEXT_TIME_INFOS.'</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td></tr>
                                        <tr style="margin-top:5px;margin-bottom:5px;"><td>
                                            <table align="center" style="width:85%; line-height:24px; padding:5px; border-radius:3px; margin:15px auto;border:1px solid #DADADA" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td style="text-align: center;"><strong>Ip:</strong></td>
                                                    <td>'.$_SERVER["REMOTE_ADDR"].'</td>
                                                    <td><strong>Heure:</strong></td>
                                                    <td>'.nkDate(time()).'</td>
                                                </tr>
                                            </table>
                                        </td></tr>
                                        <tr style="background:#f2f2f2;color:#666666;text-align:center;border-top:1px solid #ccc; font-size:16px;padding:2px 0;">
                                            <td>'.$GLOBALS['nuked']['name'].'</td>
                                        </tr>
                                    </table>
                                </div>
                            </body>
                        </html> ';

            if(nkMail($GLOBALS['nuked']['name'], $GLOBALS['nuked']['mail'], $_CLEAN['mail'], LOST_PASSWORD, $msg) === true) {
                $data = array(
                   'errorMsg'       => nkUtf8Encode(SEND_MAIL_OK),
                   'redirectLink'   => 'index.php',
                   'redirectedName' => REDIRECTED
                );
            }
            else {
                $data = array(
                   'errorMsg'       => nkUtf8Encode(SEND_MAIL_FAIL),
                   'redirectLink'   => '',
                   'redirectedName' => REDIRECTED
                );
            }
        }

        echo json_encode($data);
    }

    function sendPass () {
        // Liste les request autorisé
        $arrayRequest = array('mail', 'token');
        foreach ($arrayRequest as $key) {
            if (!array_key_exists($key, $_REQUEST)) {
                $_REQUEST[$key] = '';
            }
        }
        // Sécurity
        $_CLEAN['mail'] = mysql_real_escape_string($_REQUEST['mail']);
        // SQL
        $dbsTestName = 'SELECT pseudo, token, token_time AS tokenTime,
                            (
                                SELECT count(id)
                                FROM '.USERS_TABLE.'
                                WHERE mail = "'.$_CLEAN['mail'].'"
                            ) AS countMail,
                            (
                                SELECT count(id)
                                FROM '.BANNED_TABLE.'
                                WHERE email = "'.$_CLEAN['mail'].'"
                            ) AS countBan
                        FROM '.USERS_TABLE.'
                        WHERE mail = "'.$_CLEAN['mail'].'" ';
        $dbeTestName = mysql_query($dbsTestName) or die(mysql_error());
        $testName    = mysql_fetch_assoc($dbeTestName);
        // Variables
        $patternMail  = '#^[a-z0-9]+[a-z0-9._-]*@[a-z0-9.-]+.[a-z0-9]{2,3}$#';
        $patternToken = '#^[a-z0-9]{13}$#';
        // Errors
        if (empty($_CLEAN['mail'])) {
            $errors++;
            $errorMsg     =  nkHtmlEntities(REG_PROGRESS);
            $bgClass      = 'nkSiteLoginError';
            $data = array(
               'errorMsg'       => nkUtf8Encode(ERROR_EMPTY_FIELD),
               'bgClass'        => $bgClass,
               'redirectLink'   => '',
               'redirectedName' => REDIRECTED
            );
        }
        else if (!preg_match($patternMail, $_CLEAN['mail']) OR $testName['countMail'] == 0) {
            $errors++;
            $errorMsg     =  nkHtmlEntities(REG_PROGRESS);
            $bgClass      = 'nkSiteLoginError';
            $data = array(
               'errorMsg'       => nkUtf8Encode(ERROR_WRONG_MAIL),
               'bgClass'        => $bgClass,
               'redirectLink'   => '',
               'redirectedName' => REDIRECTED
            );
        }
        else if ($testName['countBan'] > 0) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_MAIL_BANNED);
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = 'index.php';

            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        else if($testName['token'] != null && (time() - $testName['tokenTime']) < 3600) {
            $errors++;
            $errorMsg     =  nkUtf8Encode(ERROR_TOKEN_ACTIVE);
            $bgClass      = 'nkSiteErrorLogged';
            $redirectLink = 'index.php';
            $data = array(
               'errorMsg'       => $errorMsg,
               'bgClass'        => $bgClass,
               'redirectLink'   => $redirectLink,
               'redirectedName' => REDIRECTED
            );
        }
        else if (!preg_match($patternToken, $_REQUEST['token']) OR $testName['token'] != $data['token']) {
            $errors++;
            $data = array(
               'errorMsg'       => nkUtf8Encode(ERROR_WRONG_TOKEN),
               'redirectLink'   => 'index.php',
               'redirectedName' => REDIRECTED
            );
        }
        else if ($errors == 0) {
            $dbsName  = '   SELECT pseudo
                            FROM '.USERS_TABLE.'
                            WHERE mail = "'.$_CLEAN['mail'].'" ';
            $dbeName  = mysql_query($dbsName) or die(mysql_error());
            $user     = mysql_fetch_assoc($dbeName);
            $newPass = makePass();

            $msg = '    <html>
                            <body>
                                <div style="background:#EEEEEE;">
                                    <table align="center" style="background:#ffffff;width:85%;border:1px solid #ccc;border-top:0;border-bottom:0;" border="0" cellspacing="10" cellpadding="0">
                                        <tr style="background:#f2f2f2;color:#666666;text-align:center;border-bottom:1px solid #ccc;font-size:16px;">
                                            <td><strong>'.TITLE_MAIL.'</strong></td>
                                        </tr>
                                        <tr style="margin-top:5px;margin-bottom:5px;"><td>
                                            <table align="center" style="width:85%; line-height:24px; padding:5px; border-radius:3px; margin:15px auto;border:1px solid #ccc" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <p>'.TEXT_HELLO.' <strong>'.$user['pseudo'].',</strong></p>
                                                        <p>'.LINK_NEW_PASS.'</p>
                                                        <p align="center">'.$newPass.'</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td></tr>
                                        <tr style="margin-top:5px;margin-bottom:5px;"><td>
                                            <table align="center" style="width:85%; line-height:24px; padding:5px; border-radius:3px; margin:15px auto;border:1px solid #cccccc" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td style="text-align: center;"><strong>Ip:</strong></td>
                                                    <td>'.$_SERVER["REMOTE_ADDR"].'</td>
                                                    <td><strong>Heure:</strong></td>
                                                    <td>'.nkDate(time()).'</td>
                                                </tr>
                                            </table>
                                        </td></tr>
                                        <tr style="background:#f2f2f2;color:#666666;text-align:center;border-top:1px solid #ccc; font-size:16px;padding:2px 0;">
                                            <td>'.$GLOBALS['nuked']['name'].'</td>
                                        </tr>
                                    </table>
                                </div>
                            </body>
                        </html> ';

            if(nkMail($GLOBALS['nuked']['name'], $GLOBALS['nuked']['mail'], $_CLEAN['mail'], YOUR_NEW_PASS, $msg) === true) {

                $dbuUser   = '  UPDATE '.USERS_TABLE.'
                                SET pass = "'.$newPass.'", token  = "NULL", token_time = "0"
                                WHERE mail = "'.$_CLEAN['mail'].'" ';
                $dbeUser   =    mysql_query($dbuUser);

                $data = array(
                   'errorMsg'       => nkUtf8Encode(SEND_MAIL_OK),
                   'redirectLink'   => 'index.php',
                   'redirectedName' => REDIRECTED
                );
            }
            else {
                $data = array(
                   'errorMsg'       => nkUtf8Encode(SEND_MAIL_FAIL),
                   'redirectLink'   => '',
                   'redirectedName' => REDIRECTED
                );
            }

        }

    }

    function generatePasswd ($numAlpha=6, $numNonAlpha=2) {
       $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
       $listNonAlpha = ',;:!?.$/*-+&@_+;./*&?$-!,';
       return str_shuffle(
          substr(str_shuffle($listAlpha),0,$numAlpha) .
          substr(str_shuffle($listNonAlpha),0,$numNonAlpha)
        );
    }

    function makePass() {
        $makepass = "";
        $syllables = "er,in,tia,wol,fe,pre,vet,jo,nes,al,len,son,cha,ir,ler,bo,ok,tio,nar,sim,ple,bla,ten,toe,cho,co,lat,spe,ak,er,po,co,lor,pen,cil,li,ght,wh,at,the,he,ck,is,mam,bo,no,fi,ve,any,way,pol,iti,cs,ra,dio,sou,rce,sea,rch,pa,per,com,bo,sp,eak,st,fi,rst,gr,oup,boy,ea,gle,tr,ail,bi,ble,brb,pri,dee,kay,en,be,se";
        $syllable_array = explode(",", $syllables);
        srand((double)microtime() * 1000000);
        for ($count = 1;$count <= 4;$count++) {
            if (rand() % 10 == 1) {
                $makepass .= sprintf("%0.0f", (rand() % 50) + 1);
            }
            else{
                $makepass .= sprintf("%s", $syllable_array[rand() % 62]);
            }
        }
        return($makepass);
    }
    // -> backward compatibility for Nk < 1.8
    if (!array_key_exists('nuked_nude', $_REQUEST)) { opentable(); }
    $_REQUEST['op'] = ($_REQUEST['op'] == 'login_screen') ? $_REQUEST['op'] = 'formLogin'         : $_REQUEST['op'] = $_REQUEST['op'];
    $_REQUEST['op'] = ($_REQUEST['op'] == 'login')        ? $_REQUEST['op'] = 'sendLogin'         : $_REQUEST['op'] = $_REQUEST['op'];

    switch ($_REQUEST['op']) {
        case"index":
             index();
             break;

        case"home":
            home();
            break;

        case"accountOption":
            accountOption();
            break;

        case"template":
            template();
            break;

        case"privateMsg":
            privateMsg();
            break;

        case"saveJquery":
            saveJquery();
            break;

        case"formLogin":
             formLogin();
             break;

        case"sendLogin":
             sendLogin();
             break;

        case"formLogout":
             formLogout();
             break;

        case"sendLogout":
             sendLogout();
             break;

        case"formRegister":
             formRegister();
             break;

        case"sendRegister":
             sendRegister();
             break;

        case"formLostPassword":
             formLostPassword();
             break;

        case"sendLostPassword":
             sendLostPassword();
             break;

        default:
            index();
            break;
    }

    if (!array_key_exists('nuked_nude', $_REQUEST)) { closetable(); }
?>
