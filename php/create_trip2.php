<?php
/**
 * @author Rance Aaron
 * Aug 2022
 */
include 'functions.php';

//set globals
setGlobalVariable();

//set default values for troubleshooting
$_POST['firstname'] = 'John';
$_POST['lastname'] = 'Doe';
$_POST['email'] = 'jdoe@testing.com';
$_POST['telephone'] = 2134567890;
$_POST['checkin'] = '2022-06-01';
$_POST['checkout'] = '2022-06-07';
$_POST['villaselect'] = 3;
$_POST['excursions'] = 4;
$_POST['catering'] = 8;
$_POST['salon'] = 13;

//trip params
$qty = 1;
$product_ids = ["excurs"=>$_POST['excurs'], "villa"=>$_POST['villaselect'], "catering"=>$_POST['catering']];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$rental_qty = get_number_days($checkin, $checkout);
$document_params = ['modulepart'=>'proposal'];

foreach($product_ids as $l=>$p){
    $product_info[$l][] = json_decode(get_product_info($endpoint_base, $p, $api_key));
}

foreach($product_info as $l=>$j){
    foreach($j as $k){
        if($l == 'villa'){
            $qty = $rental_qty;           
        }else{
            $qty = 1;                   
        }        
    
        $proposal_line_data[] = [
            'label'=>$k->label,
            'desc'=>$k->description,
            'subprice'=>$k->price_ttc,
            'total_ttc'=>$k->total_ttc,
            'qty'=>$qty,
            'fk_product_type'=>1,
            'multicurrency_total_ttc'=>$qty*$k->price_ttc
        ];
    }
}

//use params to create a new third party prospect
$prospect_data = [
    'name' => $_POST['firstname'].' '.$_POST['lastname'],
    'name_alias' => '',
    'address' => $_POST['address'],
    'zip' => $_POST['zip'],
    'town' => $_POST['city'],
    'status' => 1,
    'prospect'=>0,
    'client'=>2,
    'state_id' => 551,
    'phone' =>$_POST['telephone'],
    'email' => $_POST['email']
];

$new_prospect_id = create_prospect($endpoint_base, $api_key, $prospect_data);

//use params to create a proposal
$proposal_params = [ 
    'socid'=>$new_prospect_id,   
    'date'=>"$date_today",
    'datep'=>"$date_today",
    'datev'=>"$date_today",
    'date_validation'=>"$date_today",
    'fin_validite'=>"2022-06-16",   
    'array_options'=>[
        'options_checkin'=>"$checkin",
        'options_checkout'=>"$checkout"
    ]    
];

$new_proposal_id = create_proposal($endpoint_base, $api_key, $proposal_params);

//add products & services to proposal
foreach($proposal_line_data as $pld){
    update_proposal($endpoint_base, $new_proposal_id, $pld, $api_key);
}

validate_proposal($endpoint_base, $api_key, $new_proposal_id);

echo 'Entities created...';
include 'create_docs.php';