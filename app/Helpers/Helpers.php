<?php

use Carbon\Carbon;
use App\Models\Review;
use App\Models\Company;
use App\Models\Visitor;
use App\Models\Customer;
use Illuminate\Http\Request;
//https://rajivverma.me/blog/tech/global-helper-function-laravel-8/

function getCompany($inID){
  return  Company::where('users_id',$inID)->get();
}
/**
 * Convert a phone input to E164
 * @param string $inPh
 * @param string|int $inCC DEFAULT 1
 * @return string
 */
function toE164(string $inPh, string|int $inCC = '1'){
  return "+$inCC" . preg_replace('/\D+/', '', $inPh);
}
/**
 * Format an E164 phone number to (NNN) NNN-NNNN
 * @param string|int $inNumber
 * @return string
 */
function fromE164(string|int $inNumber){
  preg_match('/\d?(\d{3})(\d{3})(\d{4})/', $inNumber, $matches);
 // dump($matches);
  return '(' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
}
/**
 * MWS Link Shortner
 * for twsk.link
 * @copyright Mojo Impact Marketing Solutions 2010 - 2024
 */
function getShorty(string $inURL, string $inKW){
    if($inKW == ''):
        ray('No short url given');
    else:
        //$signature = config()->get('yourls.token');          
        $signature = '8bcc2e5d29';          
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
        ray($result);
        curl_close($ch);  
        return $result;
    endif;
}

function tracker(){
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}"; 
    $page .= iif(!empty($_SERVER['QUERY_STRING']), "?{$_SERVER['QUERY_STRING']}", "");
    $referrer = $_SERVER['HTTP_REFERER'];
    //$datetime = mktime();
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $remotehost = @getHostByAddr($ipaddress);
}