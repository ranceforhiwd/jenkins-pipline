<?php
function setGlobalVariable()
{
    $GLOBALS['endpoint_base'] = "http://localhost/api/index.php/";
    $GLOBALS['api_key'] = 'A2aQNKKo68SIG666dvVMgyG95zmii27p';
    $GLOBALS['date_today'] = date("Y-m-d");
}

// Example of function to call a REST API
function callAPI($method, $apikey, $url, $data = false)
{
    $curl = curl_init();
    $httpheader = ['DOLAPIKEY: '.$apikey];

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            $httpheader[] = "Content-Type:application/json";

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            break;
        case "PUT":

	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            $httpheader[] = "Content-Type:application/json";

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    //    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);

    $result = curl_exec($curl);
  
    curl_close($curl);

    return $result;
}

function create_prospect($ep, $z, $y)
{  
    $endpoint = $ep.'thirdparties'; 
    return callAPI('POST', $z, $endpoint, json_encode($y));
}

function create_proposal($ep, $z, $y)
{
    $endpoint = $ep.'proposals';   
    return callAPI('POST', $z, $endpoint, json_encode($y));  
}

function download_proposal($ep, $z, $y)
{
    $endpoint = $ep."download?modulepart=proposal&original_file=$y";   
    return callAPI('GET', $z, $endpoint);  
}

function get_proposal_file($ep, $x, $y)
{
    $endpoint = $ep."documents?modulepart=proposal&id=$x";     
    return callAPI('GET', $y, $endpoint);
}
function validate_proposal($ep, $z, $y)
{
    $endpoint = $ep."proposals/$y/validate";   
    return callAPI('POST', $z, $endpoint);  
}

function get_product_info($ep, $x, $y)
{
    $endpoint = $ep."products/$x";     
    return callAPI('GET', $y, $endpoint);
}

function get_product_list($ep, $y)
{
    $endpoint = $ep."products?sortfield=t.ref&sortorder=ASC&limit=100";     
    return callAPI('GET', $y, $endpoint);
}

function get_product_cat($ep, $y, $c)
{
    $endpoint = $ep."products/$c/categories";     
    $cat = callAPI('GET', $y, $endpoint);
    return $cat;
}


function update_proposal($ep, $x, $y, $z){   
    $endpoint = $ep."proposals/$x/lines";
    callAPI('POST', $z, $endpoint, json_encode($y));  
}

function get_products(){
    //get products & services details
    $product_list = json_decode(get_product_list($endpoint_base, $api_key));

    foreach($product_list as $i=>$j){  
        $y = [];      
        $x = array_shift(json_decode(get_product_cat($endpoint_base, $api_key, $j->id)));
        
        if(isset($x)){
            $y[$x->label] = $j->label; 
            //print $j->label." ".$x->label."<br>";
        }else{
            $y['none'] = $j->label;
            //print $j->label." None<br>";
        }        
    }
    exit(json_encode($y));
}

//Utilties
function get_number_days($x, $y)
{
    // Creates DateTime objects
    $datetime1 = date_create($x);
    $datetime2 = date_create($y);
    
    // Calculates the difference between DateTime objects
    $interval = date_diff($datetime1, $datetime2);
    
    // Printing result in days format
    return $interval->format('%d');
}
