<?php
include('../../master.inc.php');
include_once(DOL_DOCUMENT_ROOT."/core/modules/propale/doc/pdf_azur.modules.php");
include_once(DOL_DOCUMENT_ROOT."/comm/propal/class/propal.class.php");
//create pdf document
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
    echo $source;
    echo $destination;
	echo "File can't be copied! \n";
}
else {
    $loc = "http://localhost:8008/".$f->name;
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

    //display details on screen with options
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