<?php
// -------------------------------------------------------------------------//
// Nuked-KlaN - PHP Portal                                                  //
// http://www.nuked-klan.org                                                //
// -------------------------------------------------------------------------//
// This program is free software. you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License.           //
// -------------------------------------------------------------------------//
defined('INDEX_CHECK') or die ('You can\'t run this file alone.');

global $nuked, $language, $user;
translate('modules/Contact/lang/' . $language . '.lang.php');

// Inclusion syst�me Captcha
include_once('Includes/nkCaptcha.php');

// On determine si le captcha est actif ou non
if (_NKCAPTCHA == 'off') $captcha = 0;
else if ((_NKCAPTCHA == 'auto' OR _NKCAPTCHA == 'on') && !nkHasVisitor())  $captcha = 0;
else $captcha = 1;

opentable();

function index(){
    global $captcha, $user;

    define('EDITOR_CHECK', 1);

    echo '<script type="text/javascript">
    <!--
    function verifchamps(){
        if (document.getElementById(\'ns_pseudo\').value.length == 0){
            alert(\'' . addslashes(_NONICK) . '\');
            return false;
        }
        if (document.getElementById(\'ns_email\').value.indexOf(\'@\') == -1){
            alert(\'' . addslashes(_BADMAIL) . '\');
            return false;
        }
        if (document.getElementById(\'ns_sujet\').value.length == 0){
            alert(\'' . addslashes(_NOSUBJECT) . '\');
            return false;
        }

        return true;
    }
    -->
    </script>';

    $input_user = (!nkHasVisitor()) ? '<input id="ns_pseudo" type="text" name="nom" value="' . $GLOBALS['user']['nickName'] . '" style="width: 50%" />' : '';

    echo '<div style="width: 80%; margin: auto">
    <form method="post" action="index.php?file=Contact&amp;op=sendmail" onsubmit="return verifchamps()">
    <p style="text-align: center; margin-bottom: 20px"><big><b>' . _CONTACT . '</b></big><br /><em>' . _CONTACTFORM . '</em></p>
    <p><label for="ns_pseudo" style="float: left; width: 20%; font-weight: bold">' . _YNICK . ' : </label>&nbsp;' . $input_user . '</p>
    <p><label for="ns_email" style="float: left; width: 20%; font-weight: bold">' . _YMAIL . ' : </label>&nbsp;<input id="ns_email" type="text" name="mail" value="" style="width: 50%" /></p>
    <p><label for="ns_sujet" style="float: left; width: 20%; font-weight: bold">' . _YSUBJECT . ' : </label>&nbsp;<input id="ns_sujet" type="text" name="sujet" value="" style="width: 50%" /></p>
    <p style="font-weight: bold; margin-top: 10px">' . _YCOMMENT . ' : <br /><textarea id="e_basic" name="corps" cols="60" rows="12"></textarea></p>';

    // Affichage du Captcha.
    echo '<div style="text-align: center">',"\n";
    if ($captcha == 1) create_captcha(3);
    echo '</div>',"\n";

    echo '<p style="text-align: center; clear: left"><br /><input type="submit" class="bouton" value="' . _SEND . '" /></p></form><br /></div>';
}

function sendmail(){
    global $nuked, $userIp, $captcha, $user;

    // Verification code captcha
    if ($captcha == 1){
        ValidCaptchaCode();
    }

    if (!$_REQUEST['mail'] || !$_REQUEST['sujet'] || !$_REQUEST['corps']){
        echo '<p style="text-align: center">' . _NOCONTENT . '<br /><br /><a href="javascript:history.back()">[ <b>' . _BACK . '</b> ]</a></p>';
        closetable();
        footer();
        exit();
    }

    $time = time();
    $date = nkDate($time);
    $contact_flood = $nuked['contact_flood'] * 60;

    $sql = mysql_query("SELECT date FROM " . CONTACT_TABLE . " WHERE ip = '" . $userIp . "' ORDER BY date DESC LIMIT 0, 1");
    $count = mysql_num_rows($sql);
    list($flood_date) = mysql_fetch_array($sql);
    $anti_flood = $flood_date + $contact_flood;

    if ($count > 0 && $time < $anti_flood){
        echo '<div style="text-align: center; padding: 20px 0;">' . _FLOODCMAIL . '</div>';
        redirect("index.php", 3);
    }
    else{
        $nom = trim($_REQUEST['nom']);
        $mail = trim($_REQUEST['mail']);
        $sujet = trim($_REQUEST['sujet']);
        $corps = $_REQUEST['corps'];
        if(!nkHasVisitor()) $nom = $GLOBALS['user']['nickName'];

        $subjet = stripslashes($sujet) . ", " . $date;
        $corp = $corps . "<p><em>IP : " . $userIp . "</em><br />" . $nuked['name'] . " - " . $nuked['slogan'] . "</p>";
        $from = "From: " . $nom . " <" . $mail . ">\r\nReply-To: " . $mail . "\r\n";
        $from .= "Content-Type: text/html\r\n\r\n";

        if ($nuked['contact_mail'] != "") $email = $nuked['contact_mail'];
        else $email = $nuked['mail'];
        $corp = secu_html(nkHtmlEntityDecode($corp));

        mail($email, $subjet, $corp, $from);

        $name = htmlentities($nom, ENT_QUOTES, 'ISO-8859-1');
        $email = htmlentities($mail, ENT_QUOTES, 'ISO-8859-1');
        $subject = htmlentities($sujet, ENT_QUOTES, 'ISO-8859-1');
        $text = secu_html(html_entity_decode($corps, ENT_QUOTES, 'ISO-8859-1'));
        if(!nkHasVisitor()) $name = $GLOBALS['user']['nickName'];

        $add = mysql_query("INSERT INTO " . CONTACT_TABLE . " ( `id` , `titre` , `message` , `email` , `nom` , `ip` , `date` ) VALUES ( '' , '" . $subject . "' , '" . $text . "' , '" . $email . "' , '" . $name . "' , '" . $userIp . "' , '" . $time . "' )");
        $upd = mysql_query("INSERT INTO ". $nuked['prefix'] ."_notification  (`date` , `type` , `texte`)  VALUES ('".$time."', '1', '"._NOTCON.": [<a href=\"index.php?file=Contact&page=admin\">lien</a>].')");

        echo '<div style="text-align: center; padding: 20px 0">' . _SENDCMAIL . '</div>';
        redirect("index.php", 3);
    }
}

switch($_REQUEST['op']){
    case 'sendmail':
    sendmail($_REQUEST);
    break;

    case 'index':
    index();
    break;

    default:
    index();
    break;
}

closetable();
?>
