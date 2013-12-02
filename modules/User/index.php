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
                    <a class="tipE" href="#" title="Pr&eacute;f&eacute;rence"><span class="icon-cog"></span></a>
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
                $tmpDivGen .= ' <div><span>'.constant(strtoupper($key)).'</span><span>'.$arrayAllDataUser[$key].'</span></div> '."\n";
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
                    $tmpDivMaterial .= '  <div><span>'.$name.'</span><span>'.$arrayAllDataUser[$data['name']].'</span></div> '."\n";
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
                $tmpForumName .= '    <span>
                                            <a href="'.$data['id'].'" title="'.$data['title'].'">
                                                '.$data['title'].'
                                            </a>
                                        </span> '."\n";
                $tmpForumValue .= '   <span>'.nkDate($data['date'], true).'></span> '."\n";
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
                        <div class="full">
                            <ul>
                                <li>
                                    <a class="tipE" href="#" title="Menu">
                                        <i class="icon-menu"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="tipS" href="#" title="Template">
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

                            <div class="icon-info"><?php echo DD_CLICK_TO_EDIT; ?></div>
                        </div>

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
                                <p>'.$data['name'].'</p>
                            </div> '."\n";
        }
?>
                    <div id="usersContent">
                        <div class="full">
                            <ul>
                                <li>
                                    <a class="tipE" href="#" title="Menu">
                                        <i class="icon-menu"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="tipS" href="#" title="Template">
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

                            <div class="icon-info"><?php echo DD_CLICK_TO_EDIT; ?></div>
                        </div>

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
                                            <span>
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

    function privateMsg () {

    }

    // -> backward compatibility for Nk < 1.8
    if (!array_key_exists('nuked_nude', $_REQUEST)) { opentable(); }

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

        case"privateMsg":
            privateMsg();
            break;

        default:
            index();
            break;
    }

    if (!array_key_exists('nuked_nude', $_REQUEST)) { closetable(); }
?>
