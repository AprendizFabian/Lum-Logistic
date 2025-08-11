<?php

function checkInactivity($timeout = 300)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (isset($_SESSION['LAST_ACTIVITY']) && time() > $_SESSION['LAST_ACTIVITY']) {
        session_unset();
        session_destroy();
        header('Location: /login?expired=1');
        exit;
    }

    $_SESSION['LAST_ACTIVITY'] = time() + $timeout;

}
