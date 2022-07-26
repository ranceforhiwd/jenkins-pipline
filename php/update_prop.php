<?php
include 'functions.php';

$call_type = 'PUT';
$api_user_key = '4O2snT4z28SQWAsy3HS84BBst0ahRw8t';
$endpoint = "http://0.0.0.0/api/index.php/proposals/47";
$data = '{"label":"3 Bed Villa 766","desc":"Video about this villa All the privacy of a private home mixed with the services of a resort.\u00a0\u00a0 \tFree shuttle bus \tOnsite restaurants \u2013 delivery \tOnsite mini-market with delivery \tWaterpark with lazy river, kids area, swim against the current machine, and swim up bar.\u00a0\u00a0 \tTennis, bocce ball, pickleball, shuffleboard \t24-hour front desk\u00a0 \tBeach Clubs on Sosua and Cabarete Beaches. \tPrivate pools, outstanding service, endless fun and new adventures. \tMinutes to several local beaches, shopping & restaurants","subprice":"0.00000000","total_ttc":null,"qty":"6","fk_product_type":1,"multicurrency_total_ttc":0}';
$output = json_decode(callAPI($call_type, $api_user_key, $endpoint, $data));

exit('done');