<?php
/**
 * @author Rance Aaron
 * 
 */
include('../../master.inc.php');
include_once(DOL_DOCUMENT_ROOT."/core/modules/propale/doc/pdf_azur.modules.php");
include_once(DOL_DOCUMENT_ROOT."/comm/propal/class/propal.class.php");
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
$_POST['villaselect'] = 17;
$_POST['excursions'] = 2;
$_POST['catering'] = 2;
$_POST['salon'] = 3;

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

$x = new Propal($db,  (int)$new_prospect_id, (int)$new_proposal_id);
$x->fetch($new_proposal_id);

$p = new pdf_azur($db);
$p->write_file($x,array(),"write_file_input.pdf");

$f = array_shift(json_decode(get_proposal_file($endpoint_base, $new_proposal_id,  $api_key)));

// Store the path of source file
$source = $f->fullname;

// Store the path of destination file
$destination = DOL_DOCUMENT_ROOT.'/prospects/'.$f->name;


if( !copy($source, $destination) ) {
	echo "File can't be copied! \n";
}
else {
    $loc = "https://localhost/".$f->name;
    $c = array();
    $y = $x->lines;
    
    $line_titles = ['desc','qty','subprice','label','multicurrency_subprice','multicurrency_total_ttc'];
    foreach($y[1] as $i=>$j){
        if(in_array($i, $line_titles)){
            if(is_array($j)){
                print_r($j);
               }elseif(!is_object($j) && !is_null($j) && ($j>0 || $i == 'desc')){
                $c[] = $j;
               }
        }
    }
    sleep(10);
    echo '';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>';
    echo '';
    echo '<div class="container"';
            echo '<br>';
            echo '<h2 style="margin-top:30px">Estimated Trip Cost</h2>';
            echo '<hr>'; 
            echo '<p>'.$c[0].'</p>';
            echo '<p><b>Dates</b>: '.$checkin.' to '.$checkout.'</p>';
            echo '<p><b>Duration</b>: '.$c[1].' Nights</p>';
            echo '<p><b>Subtotal</b>: $'.round($c[2],2).' per night stay</p>';
            echo '<p><b>Total Cost</b>: $'.round($c[5],2).'</p>';
            echo '<a type="button" class="btn btn-info action-button-lg btn-block" style="color:#ffffff;background:#1C4669">Reserve this Trip</a>';           
            echo '<br><a type="button" class="btn btn-info action-button-lg btn-block" target="_self" href="https://ofc.quickfixtrips.fun/prospects/'.$f->name.'">Download Quote</a>';
        
    echo '</div>'; 
    echo '';
}