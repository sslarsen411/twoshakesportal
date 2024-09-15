@php

use App\Helpers\Helpers;
function getShorty(string $inURL, string $inKW){
    if($inKW == ''):
        dd('No short url given');
    else:
        $signature = config()->get('yourls.token');          
        $format = 'json';			// output format: 'json', 'xml' or 'simple'
        $api_url = 'https://twsk.link/yourls-api.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
        curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request                  
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(     // Data to POST
            'url'      => $inURL,
            'keyword'  => $inKW,
            'format'   => $format,
            'action'   => 'shorturl',		
            'signature' => $signature
        ));
    // Fetch and return content
        $result = (json_decode(curl_exec($ch), true));
        curl_close($ch);  
        return $result;
    endif;
}   
    $loc = $getRecord()->id;
    $url = "https://www.twoshakes.review?loc=$loc";
    echo $url
    $short =  getShorty($url , 'boop');
    //$short =  'https://www.twoshakes.review?loc=' . $getRecord()->id;
@endphp
<div>
    {{ $short }}
</div>