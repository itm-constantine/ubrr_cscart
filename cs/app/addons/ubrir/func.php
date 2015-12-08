<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_ubrir_install()
{

    fn_ubrir_uninstall();

    $_data = array(
        'processor' => 'ubrir',
        'processor_script' => 'ubrir.php',
        'processor_template' => 'addons/ubrir/views/orders/components/payments/ubrir.tpl',
        'admin_template' => 'ubrir.tpl',
        'callback' => 'N',
        'type' => 'P',
        'addon' => 'ubrir'
    );

    db_query("INSERT INTO ?:payment_processors ?e", $_data);
}

function fn_ubrir_uninstall()
{
    db_query("DELETE FROM ?:payment_processors WHERE processor_script = ?s", "ubrir.php");
}


