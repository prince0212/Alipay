<?php

//$apiUrl = "http://118.140.3.194:8081/eopg_testing_env/ForexTradeRecetion.jsp?";
//$mid = '852202102090001';
//$merchentSercretKey = 'c25bd6fb6183e8f12a112b5fad2fd0d2b05450e0';
//$notifyUrl = 'http://www.seeifpaymentsuccess.com';
//$returnUrl = 'https://fpstest.eftsolutions.com:7443/196300';
////$merch_ref_no = '0000000000000025';
//echo 'Merch : '.$merch_ref_no = $mid+1235;
//echo ' --- : '.($merch_ref_no - $mid);
//die;
//
//
//$string = $merchentSercretKey."merch_ref_no=$merch_ref_no"."&mid=$mid"."&payment_type=ALIPAY&service=SALE&trans_amount=0.01";
//
//$signature = hash('sha256', $string);
//
//$dataParam = "service=SALE&payment_type=ALIPAY&mid=$mid&return_url=https://fpstest.eftsolutions.com:7443/196300&signature=$signature&merch_ref_no=$merch_ref_no&goods_subject=mobile%20phone&goods_body=description&trans_amount=0.01&api_version=2.9&wallet=HK&app_pay=APP&active_time=60000&notify_url=http://www.seeifpaymentsuccess.com";
//
//$curl = curl_init();
//
//curl_setopt_array($curl, array(
//  CURLOPT_URL => $apiUrl.$dataParam,
//  CURLOPT_RETURNTRANSFER => true,
//  CURLOPT_ENCODING => '',
//  CURLOPT_MAXREDIRS => 10,
//  CURLOPT_TIMEOUT => 0,
//  CURLOPT_FOLLOWLOCATION => true,
//  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//  CURLOPT_CUSTOMREQUEST => 'GET'
//));
//
//$response = curl_exec($curl);
//
//curl_close($curl);
//echo $response;

$string = "c25bd6fb6183e8f12a112b5fad2fd0d2b05450e0merch_ref_no=852203755384568&mid=852202102090001&order_id=20220523162927038008&payment_type=ALIPAY&service=SALE&trade_no=2022052322001340591452005849&trans_amount=1.00&trans_status=SUCCESS";
echo hash('sha256', $string);