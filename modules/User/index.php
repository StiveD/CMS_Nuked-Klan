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
                            <a class="icon-home jqueryLinksSwtich" data-icon="icon-home" data-title="Accueil" href="#Home">Accueil</a>
                        </li>
                        <li>
                            <a class="icon-profile jqueryLinksSwtich" data-icon="icon-profile" data-title="Mon Compte" href="#MyAccount">Mon Compte</a>
                        </li>
                        <li>
                            <a class="icon-envelope jqueryLinksSwtich" data-icon="icon-envelope" data-title="Messagerie Priv&eacute;" href="#PrivateMsg">Messagerie Priv&eacute;
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
                    <?php Home(); ?>
                </section>
            </article>

        </section>
<?php
        else:
            redirect("index.php?file=User&op=login_screen", 0);
        endif;
    }

    // -> backward compatibility for Nk < 1.8
    if (!array_key_exists('nuked_nude', $_REQUEST)) { opentable(); }

    switch ($_REQUEST['op']) {
        case"index":
             index();
             break;

        default:
            index();
            break;
    }

    if (!array_key_exists('nuked_nude', $_REQUEST)) { closetable(); }
?>
?>
