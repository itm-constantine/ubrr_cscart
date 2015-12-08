<?php

use Tygh\Payments\Processors\Ubrir;

if (defined('PAYMENT_NOTIFICATION')) {
    if ($mode=="ok") {
        $order_id= $_REQUEST['order_id'];
        $order_info = fn_get_order_info($order_id);
        $twpg_id = $order_info['payment_info']['TWPG ID'];
        $twpg_session = $order_info['payment_info']['TWPG SESSION'];
        $xml_string = base64_decode($_REQUEST['xmlmsg']);
        $parse_it = simplexml_load_string($xml_string);
        $orderstatus = $parse_it->OrderStatus[0];
        $order_status ="";
        $ubrir = new Ubrir(
            Array(
                'shopId' => $order_info['payment_method']['processor_params']['twpg_id'],
                'sert' => $order_info['payment_method']['processor_params']['twpg_pass'],
                'twpg_order_id' => $order_info['payment_info']['TWPG ID'],
                'twpg_session_id' => $order_info['payment_info']['TWPG SESSION']
            )
        );
        if($ubrir->check_status($orderstatus)) {
            if ($orderstatus =="APPROVED") {
                $order_status = "P";
            } else {
                $order_status = "F";    
            }
        }
    }

    if ($mode == "admin_button") {
        switch ($_REQUEST['action']) {
            case 'orderstatus':
                if (!empty($_REQUEST['order_id'])) {
                    $order_info = fn_get_order_info($_REQUEST['order_id']);
                    if (count($order_info)!=0 && isset($order_info['payment_info']['TWPG ID'])) {
                        $ubrir = new Ubrir(
                            Array(
                                'shopId' => $order_info['payment_method']['processor_params']['twpg_id'],
                                'sert' => $order_info['payment_method']['processor_params']['twpg_pass'],
                                'twpg_order_id' => $order_info['payment_info']['TWPG ID'],
                                'twpg_session_id' => $order_info['payment_info']['TWPG SESSION']
                            )
                        );
                        echo $ubrir->check_status();
                    } else {
                        echo "Неверный номер заказа";
                        die;
                    }

                } else {
                    echo 'Неверный номер заказа';
                }
            break;
            case 'orderdetailstatus':
                if (!empty($_REQUEST['order_id'])) {
                    $order_info = fn_get_order_info($_REQUEST['order_id']);
                    if (count($order_info)!=0 && isset($order_info['payment_info']['TWPG ID'])) {
                        $ubrir = new Ubrir(
                            Array(
                                'shopId' => $order_info['payment_method']['processor_params']['twpg_id'],
                                'sert' => $order_info['payment_method']['processor_params']['twpg_pass'],
                                'twpg_order_id' => $order_info['payment_info']['TWPG ID'],
                                'twpg_session_id' => $order_info['payment_info']['TWPG SESSION']
                            )
                        );
                        echo $ubrir->detailed_status();
                    } else {
                        echo "Неверный номер заказа";
                        die;
                    }

                } else {
                    echo 'Неверный номер заказа';
                }
            break;
            case 'reverse':
                if (!empty($_REQUEST['order_id'])) {
                    $order_info = fn_get_order_info($_REQUEST['order_id']);
                    if (count($order_info)!=0 && isset($order_info['payment_info']['TWPG ID'])) {
                        $ubrir = new Ubrir(
                            Array(
                                'shopId' => $order_info['payment_method']['processor_params']['twpg_id'],
                                'sert' => $order_info['payment_method']['processor_params']['twpg_pass'],
                                'twpg_order_id' => $order_info['payment_info']['TWPG ID'],
                                'twpg_session_id' => $order_info['payment_info']['TWPG SESSION']
                            )
                        );
                        echo $ubrir->reverse_order();
                    } else {
                        echo "Неверный номер заказа";
                        die;
                    }

                } else {
                    echo 'Неверный номер заказа';
                }
            break;
            case 'reconcile':
                    $ubrir = new Ubrir(
                        Array(
                            'shopId' => $_REQUEST['twpg_id'],
                            'sert' => $_REQUEST['twpg_pass'],
                        )
                    );
                    echo $ubrir->reconcile();
            break;
            case 'journal':
                    $ubrir = new Ubrir(
                        Array(
                            'shopId' => $_REQUEST['twpg_id'],
                            'sert' => $_REQUEST['twpg_pass'],
                        )
                    );
                    echo $ubrir->extract_journal();
            break;
            case 'uni_journal':
                    $ubrir = new Ubrir(
                        Array(
                            'uni_login' => $_REQUEST['uni_login'],
                            'uni_pass' => $_REQUEST['uni_pass'],
                        )
                    );
                    echo $ubrir->uni_journal();
            break;
            case 'send_mail':
                    if(isset($_REQUEST['mailsubject'])) {       
                        if($_REQUEST['mailsubject'] == 'Выберите тему') {
                            echo "Не выбрана тема обращения";
                            die;
                          } elseif (empty($_REQUEST['mailem'])) {
                            echo "Не заполнен номер телефона";
                            die;
                          } elseif(empty($_REQUEST['maildesc'])) {
                            echo "Не заполнена причина обращения";
                            die;
                          }
                            $to = 'ibank@ubrr.ru';
                             $subject = htmlspecialchars($_REQUEST['mailsubject'], ENT_QUOTES);
                             $message = 'Отправитель: '.htmlspecialchars($_REQUEST['mailem'], ENT_QUOTES).' | '.htmlspecialchars($_REQUEST['maildesc'], ENT_QUOTES);
                             $headers = 'From: '.$_SERVER["HTTP_HOST"];
                             if (mail($to, $subject, $message, $headers)) {
                                echo "Письмо отправлено";
                             } else {
                                echo "Письмо временно не может быть доставлено"; 
                            }
                    }
            break;
        }
        die;
    }

    if ($mode=="cancel" | $mode == "decline") {
        $order_id= $_REQUEST['order_id'];
        $order_status = "F"; 
    }

    if ($mode == "uni_ok") {
        $order_id= $_REQUEST['?ORDER_ID'];
        $order_info = fn_get_order_info($_REQUEST['?ORDER_ID']);
        if ($order_info['status'] == 'N') {
            $order_status ='A';
        } else {
            $order_status = $order_info['status'];
        }
    }

    if ($mode == "uni_cancel") {
        $order_id= $_REQUEST['?ORDER_ID'];
        $order_status = "F";
    }

    if ($mode == "uni_resp") {
        switch ($_REQUEST['STATE']) {
            case 'authorized':
                break;
            case 'paid':
                $order_status = "P";
                break;
        }
    }

    $pp_response = array('order_status' => $order_status);

    fn_finish_payment($order_id,$pp_response);
    fn_order_placement_routines('route', $order_id, false);

    exit;

} else {
    // print_r($_GET);
    // print_r($_POST);
    $amount = $order_info['total'];
    $order_id = $order_info['order_id'];
    $twpg_id = $order_info['payment_method']['processor_params']['twpg_id'];
    $twpg_pass = $order_info['payment_method']['processor_params']['twpg_pass'];
    $approve_url = fn_url("payment_notification.ok&payment=ubrir&order_id=".$order_id);
    $cancel_url = fn_url("payment_notification.cancel&payment=ubrir&order_id=".$order_id);
    $decline_url = fn_url("payment_notification.decline&payment=ubrir&order_id=".$order_id);
    switch ($order_info['payment_info']['pc_type']) {
        case 'visa':
            $ubrir = new Ubrir(
                Array(
                    'shopId' => $twpg_id,
                    'sert' => $twpg_pass,
                    'amount' => $amount,
                    'approve_url' => htmlentities($approve_url),
                    'cancel_url' => htmlentities($cancel_url),
                    'decline_url' => htmlentities($decline_url)
                    ));
            $response = $ubrir->prepare_to_pay();
            $url = $response->URL[0];
            $twpg_id = $response->OrderID[0];
            $sessionid = $response->SessionID[0];
            $twpg_params = array('orderid' => $twpg_id, 'sessionid' => $sessionid );
            $order_params = array('TWPG ID' => (string)$twpg_id, 'TWPG SESSION' => (string)$sessionid);

            fn_update_order_payment_info($order_id, $order_params);
            fn_create_payment_form($url, $twpg_params, 'ubrir',false, 'GET', true);
            break;
        case 'mc':
            $url = "https://91.208.121.201/estore_listener.php";
            $uni_ok = fn_url("payment_notification.uni_ok&payment=ubrir%26");

            $uni_cancel = fn_url("payment_notification.uni_cancel&payment=ubrir%26");
            $uni_id = $order_info['payment_method']['processor_params']['uni_id'];
            $uni_login = $order_info['payment_method']['processor_params']['uni_login'];
            $uni_pass = $order_info['payment_method']['processor_params']['uni_pass'];
            $sign = strtoupper(md5(md5($uni_id).'&'.md5($uni_login).'&'.md5($uni_pass).'&'.md5($order_id).'&'.md5((int)$amount)));
            $uni_params = array(
                'SHOP_ID' => $uni_id,
                'LOGIN' => $uni_login,
                'ORDER_ID' => $order_id,
                'PAY_SUM' => (int)$amount,
                'VALUE_1' => $order_id,
                'URL_OK' => $uni_ok,
                'URL_NO' => $uni_cancel,
                'SIGN' => $sign,
                'LANG' => "RU"
                );
            // die;
            fn_create_payment_form($url, $uni_params, 'ubrir',false, 'POST');
            break;
    } 
    die;
}