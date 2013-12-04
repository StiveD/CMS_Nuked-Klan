<?php
/**
 * french.lang.php
 *
 * User french language constants
 *
 * @version 1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2013 Nuked-Klan (Registred Trademark)
 */

if (!defined("INDEX_CHECK")) exit('You can\'t run this file alone.');

$arrayModLang = array(
    #####################################
    # USER - function Commun
    #####################################
    'POA'                    => 'Non renseign&eacute;',
    'UNKNOWN'                => 'Inconnu',
    'DD_CLICK_TO_EDIT'       => 'Double cliquer sur un &eacute;l&eacute;ments pour l\'&eacute;diter',
    'REDIRECTED'             => 'Redirection en cours...',
    'LOGIN_PROGRESS'         => 'Login en cours...',
    'REGISTER_PROGRESS'      => 'Enregistrement cours...',
    'USERLOGOUTINPROGRESS'   => 'D&eacute;connexion en cours...',
    #####################################
    # USER - Error Global
    #####################################
    'ERROR_PSEUDO_BANNED'    => 'Utilisateurs est banni',
    'ERROR_EMPTY_FIELD'      => 'Un champ obligatoire est vide',
    'ERROR_LOGIN'            => 'un ou plusieurs champs ont mal &eacute;t&eacute; renseign&eacute;s',
    'ERROR_WRONG_MAIL'       => 'L\'adresse email que vous avez saisie est incorrect.',
    'ERROR_TOKEN_ACTIVE'     => 'Un token vous a d&eacute;j&agrave; &eacute;t&eacute; envoy&eacute;, veuillez v&eacute;rifier votre bo&icirc;te email.',
    'ERROR_WRONG_TOKEN'      => 'Le token est incorrect.',
    #####################################
    # USER - SendRegister
    #####################################
    'ERROR_REG_LOGIN'        => 'Utilisateurs existe d&eacute;j&agrave;',
    'ERROR_MAIL_BANNED'      => 'Ce courriel est banni',
    'ERROR_TWO_PASS_FAIL'    => 'Les deux mots de passe saisis ne sont pas identiques',
    'REG_PROGRESS'           => 'Enregistrement en cours...',
    'ERROR_MAIL_USE'         => 'Ce courriel est d&eacute;j&agrave; utilis&eacute;',
    'NICK_TO_LONG'           => 'Votre pseudo est trop long',
    #####################################
    # USER - SendLostPassword
    #####################################
    'WRONG_MAIL'             => 'L\'adresse email que vous avez saisie est incorrect.',
    'LOST_PASSWORD'          => 'Mot de passe perdu',
    'SEND_MAIL_OK'           => 'Votre mail a bien &eacute;t&eacute; envoy&eacute;',
    'SEND_MAIL_FAIL'         => 'Erreur d\'envoi de l\'e-mail',
    'TEXT_TIME_INFOS'        => 'Ce lien est valide 1 heure, passer ce délai il faudra recommencer la procédure de mot de passe oublié',
    'TEXT_HELLO'             => 'Bonjour',
    'LINK_TO_NEW_PASS'       => 'Veuillez cliquer sur le lien suivant pour réinitialiser votre mot de passe',
    'TITLE_MAIL'             => 'Récupération de mot de passe',
    'LINK_NEW_PASS'          => 'Veuillez trouver ci-dessous votre nouveau mot de passe.',
    'YOUR_NEW_PASS'          => 'Votre nouveau mot de passe',
    #####################################
    # USER - function Home H3
    #####################################
    'HISTORY_NICKNAME_USED'  => 'Historique pseudo(s) utilis&eacute;(s)',
    'NO_OLD_NICKNAME'        => 'Vous n&rsquo;avez utilis&eacute; aucun autre pseudo.',
    'NO_COMMENT_IN_DATABASE' => 'Aucun commentaire dans la base de donn&eacute;es',
    'NO_POST_IN_DATABASE'    => 'Aucun message dans la base de donn&eacute;es',
    #####################################
    # USER - function Home H3
    #####################################
    'SOCIAL_NETWORKS'        => 'R&eacute;seaux Sociaux',
    'GAMING'                 => 'Gaming / Esport',
    'HARDWARE_CONFIG'        => 'Configuration Materiel',
    #####################################
    # USER - function Pref General
    #####################################
    'DATE_OF_ARRIVAL'        => 'Date d\'arriv&eacute;e',
    'LAST_VISIT'             => 'Dernière visite',
    'PRIVATE_MAIL'           => 'Courriel priv&eacute;',
    'WEBSITE'                => 'Site web',
    'PASSWORD'               => 'Mot de passe',
    #####################################
    # USER - function Home Pref
    #####################################
    'FIRST_NAME'             => 'Pr&eacute;nom',
    'DATE_OF_BIRTH'          => 'Date de naissance',
    'GENDER'                 => 'Sexe',
    'COUNTRY'                => 'Pays',
    'CITY'                   => 'Ville',
    #####################################
    # USER - function Home Social
    #####################################
    'FACEBOOK'               => 'Facebook',
    'TWITTER'                => 'Twitter',
    'YOUTUBE'                => 'Youtube',
    'SKYPE'                  => 'Skype',
    #####################################
    # USER - function Home Social
    #####################################
    'STEAM'                  => 'Steam',
    'XFIRE'                  => 'Xfire',
    'ORIGIN'                 => 'Origin',
    'ID_PLAYSTATION_NETWORK' => 'ID Playstation Network',
    'XBOX_LIVE'              => 'XBOX Live',
    'ID_BATTLE_NET'          => 'ID Battle Net',
    #####################################
    # USER - function Home Config
    #####################################
    'MOTHERBOARD'            => 'Carte m&egrave;re',
    'CPU'                    => 'Processeur',
    'RAM'                    => 'M&eacute;moire',
    'GPU'                    => 'Carte Vid&eacute;o',
    'RESOLUTION'             => 'R&eacute;solution',
    'SOUNDCARD'              => 'Carte son',
    'SCREEN'                 => 'Ecran',
    'MOUSE'                  => 'Souris',
    'KEYBOARD'               => 'Clavier',
    'CONNECTION'             => 'Connexion',
    'OS'                     => 'Systeme OS',
    #####################################
    # USER - function Home Stats
    #####################################
    'IP'                     => 'IP',
    'ADMINISTRATORS'         => 'Administrateurs',
    'FRIEND_NUMBERS'         => 'Nombre ami(es)',
    'GROUPS'                 => 'Groupe(s)',
    'NUMBERS_CONNECT'        => 'Nombres de connexion',
    'NUMBERS_FORUM'          => 'Messages dans la forum',
    'NUMBERS_COMMENT'        => 'Commentaires post&eacute;s',
    'NUMBERS_DOWNLOAD'       => 'Nombre de t&eacute;l&eacute;chargements',
    'NUMBERS_SUGG'           => 'Nombres de suggestions',
);
?>
