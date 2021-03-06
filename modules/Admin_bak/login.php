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
global $user, $nuked, $language;

nkTranslate('modules/Admin/lang/'.$GLOBALS['language'].'.lang.php');

include_once(dirname(__FILE__) . '/../../Includes/hash.php');

$url = 'index.php';
$message = '';

if(nkHasVisitor()){
    header('location: index.php?file=404');
    exit();
}

$hasModAccess = nkAccessModule('Admin');

function open_admin(){
    global $nuked;
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
            <title>' . $nuked['name'] . ' - ' . ADMINISTRATION . '</title>
            <link rel="stylesheet" href="modules/Admin/css/reset.css" type="text/css" media="screen" />
            <link rel="stylesheet" href="modules/Admin/css/style.css" type="text/css" media="screen" />
            <link rel="stylesheet" href="modules/Admin/css/invalid.css" type="text/css" media="screen" />
        </head>
        <body id="login">
            <div id="login-wrapper" class="png_bg">
                <div id="login-top">
                    <h1>' . $nuked['name'] . ' - ' . ADMINISTRATION_LOGIN . '</h1>
                    <img id="logo" src="modules/Admin/images/logo.png" alt="NK Logo" />
                </div>
                <div id="login-content">';
}

function close_admin(){
    echo '</div>
            </div>
        </body>
    </html>';
}

if ($hasModAccess === true)
{
    if ($user && isset($_POST['admin_password']) && $_POST['admin_password'] != '')
    {
        // $GLOBALS['cookieAdmin'] = $nuked['cookiename'] . '_admin_session';

        $sql = mysql_query("SELECT pseudo, pass FROM " . USER_TABLE . " WHERE id = '" . $GLOBALS['user']['id'] . "'");
        list($pseudo, $hash) = mysql_fetch_array($sql);
        $check = mysql_num_rows($sql);

        if ($check == 1 && Check_Hash($_POST['admin_password'], $hash))
        {
            list($pseudo) = mysql_fetch_array($sql);

            if ($_POST['formulaire'] == 0 && $_SERVER['HTTP_REFERER'] != '')
            {
                list($url_ref, $redirect) = explode('?', $_SERVER['HTTP_REFERER']);
                if ($redirect == 'file=Admin&page=login') $redirect = 'file=Admin';
                $url = 'index.php?' . $redirect;
            }
            else $url = 'index.php?file=Admin';

            $_SESSION['admin'] = true;
            // Action
            $texteaction = mysql_real_escape_string(ACTION_CONNECT);
            $acdate = time();
            $action = mysql_query("INSERT INTO ". $nuked['prefix'] ."_action  (`date` , `pseudo` , `action`)  VALUES ('".$acdate."', '".$GLOBALS['user']['id']."', '".$texteaction."')");
            //Fin action

            $message = (isset($_COOKIE[$GLOBALS['cookieSession']])) ? LOGIN_IN_PROGRESS : _ERRORCOOKIE;
        }
        else
        {
            $message = BAD_LOGIN;
            $url = 'index.php?file=Admin';
        }

            open_admin();
            echo '<div style="text-align: center">' . $message . '</div>';
            close_admin();

            redirect($url, 2);
    }
    else
    {
        if (!$user) redirect('index.php?file=User&op=login_screen', 0);
        else if (array_key_exists('nuked_nude', $_REQUEST) && $_REQUEST['nuked_nude'] == 'admin') redirect('index.php?file=Admin', 0);
        else
        {
            $check = $_POST ? 1 : 0;

            open_admin();

            echo '<form action="index.php?file=Admin&amp;nuked_nude=login" method="post">
                    <p>
                        <label>' . NICKNAME . '</label>
                        <input class="text-input" type="text" name="admin_pseudo" value="' . $GLOBALS['user']['nickName'] . '" maxlenght="180" />
                    </p>
                    <div class="clear"></div>
                    <p>
                        <label>' . PASSWORD . '</label>
                        <input class="text-input" type="password" name="admin_password" maxlength="40" />
                    </p>

                    <div class="clear"></div>
                    <p>
                        <input class="button" type="submit" value="' . LOGIN . '" />
                        <input class="button" type="button" value="' . BACK . '" style="margin-right: 10px" onclick="javascript:history.back()" />
                        <input type="hidden" name="formulaire" value="' . $check  . '" />
                    </p>
                </form>';

            close_admin();
        }
    }
}
else{
    open_admin();
?>
    <div style="text-align: center;">
        <p><?php echo ZONEADMIN; ?></p>
        <a class="button" href="javascript:history.back()">
            <b><?php echo BACK; ?></b>
        </a>
    </div>
<?php
    close_admin();
}

?>
