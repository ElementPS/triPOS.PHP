<?php
include_once("Model/SaleRequest.php");
include_once("Model/Context.php");
include_once("Helpers/ObjectHelper.php");
include_once("Helpers/ObjectAndXML.php");

class triPOSServiceHelper   {

  public static function callTriPOS($context){

    $payloadFormat = "application/json";
    if ($context->useJSON) {
      $payloadFormat = "application/json";
    } else {
      $payloadFormat = "application/xml";
    }

    $ch = curl_init($context->serviceAddress);

    $tpAuthorization = "Version=".$context->tpAuthorizationVersion.", Credential=".$context->tpAuthorizationCredential;

    // Set an array for the base required headers.
    $headers = array(
        "Content-Type: " . $payloadFormat,
        "Accept: " . $payloadFormat,
        "tp-application-id: 1234",
        "tp-application-name: triPOS.PHP",
        "tp-application-version: 1.0.0",
        //"tp-authorization: " . $tpAuthorization, -- use can use this for test, but we are doing the hmac example below
        "tp-return-logs: false",
        );

    if($context->request != null){
      curl_setopt($ch, CURLOPT_POSTFIELDS, $context->request);
      array_push($headers, "Content-Length: " . strlen($context->request));
    }

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    //create the auth header
    $auth_header = triPOSServiceHelper::createAuthHeader(
      $context->serviceAddress, "POST", $context->request, $headers,
      $context->tpAuthorizationCredential, $context->tpAuthorizationSecret
    );

    //set the auth header
    array_push($headers, "tp-authorization: " . $auth_header);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Send the request.
    $callResponse = curl_exec($ch);

    // If there was an error, gather the error number and the error text message.
    $err = curl_errno($ch);
    $errMsg = curl_error($ch);

    curl_close($ch);

    $context->status = "Error";
    $context->response = "An unexpected error occurred";

    if($err==0){
      $context->status = "Success";
      $context->response = $callResponse;
    }
    else{
      $context->status = "Error";
      $context->response = "An unexpected error occurred: $err - $errMsg";
    }

    return $context;
  }

  private function createAuthHeader($url, $method, $body, $headers, $dev_key, $dev_secret) {
    $algorithm = "tp-hmac-md5";
    $nonce = uniqid();
    $request_date = date("c");
    $parsed_url = parse_url($url);
    $canonical_uri = $parsed_url["path"];
    $canonical_query_str = $parsed_url["query"];
    $body_hash = triPOSServiceHelper::getBodyHash($body);

    //1. Get the header information
    $canonical_header_info = triPOSServiceHelper::getCanonicalHeaderInfo($headers);
    $canonical_signed_headers = $canonical_header_info["canonical_signed_headers"];
    $canonical_header_str = $canonical_header_info["canonical_header_str"];

    // 2. Calculate the request hash
    $request_hash = triPOSServiceHelper::getCanonicalRequestHash(
      $method, $canonical_uri, $canonical_query_str,
      $canonical_header_str, $canonical_signed_headers, $body_hash
    );

    // 3. Get the signature hash
    $key_signature_hash = triPOSServiceHelper::getKeySignatureHash($request_date, $nonce . $dev_secret);
    $unhashed_signature = strtoupper($algorithm) . "\n" . $request_date .  "\n" . $dev_key . "\n" . $request_hash;

    // 4. Get the actual auth signature
    $signature = triPOSServiceHelper::getKeySignatureHash($key_signature_hash, $unhashed_signature);

    // 5. Create the auth header
   return "Version=1.0,Algorithm=".strtoupper($algorithm).",Credential=".$dev_key.",SignedHeaders=".$canonical_signed_headers.",Nonce=".$nonce.",RequestDate=".$request_date.",Signature=".$signature;
  }

  private function getBodyHash($body) {
    return md5(utf8_encode($body));
  }

  private function getCanonicalHeaderInfo($headers) {
    $canonical_signed_headers = array();
    $canonical_headers = array();
    $unique_headers = array();
    foreach($headers as $k => $v) {
      $header_val = explode(":", $v, 2);
      if(strrpos($header_val[0], "tp-") === 0) continue;
      $canonical_signed_headers[] = $header_val[0];
      if(!array_search($header_val[0], $unique_headers)) {
        //unique
        $unique_headers[] = $header_val[0];
        $header_holder = array();
        $header_holder[$header_val[0]] = $header_val[1];

        $v = explode(":", $v, 2);
        if(sizeof($canonical_headers[$v[0]])) {
          array_push($canonical_headers[$v[0]], $v[1]);
        } else {
          $canonical_headers[$v[0]] = array($v[1]);
        }
      } else {
        array_push($canonical_headers[$header_val[0]], explode(":", $header_val[1], 2));
      }
    }

    asort($canonical_signed_headers);
    //each canonical header is its own line in a multi-line string
    $canonical_header_str = "";
    ksort($canonical_headers);
    foreach($canonical_headers as $k => $vals) {
      $val_str = implode(", ", $vals);
      $canonical_header_str .= trim($k).":".trim($val_str)."\n";
    }

    //remove last \n char
    $canonical_header_str = rtrim($canonical_header_str, "\n");

    //header titles joined by ;
    $canonical_signed_headers = join($canonical_signed_headers, ";");

    return array("canonical_signed_headers" => $canonical_signed_headers, "canonical_header_str" => $canonical_header_str);
  }

  private function getCanonicalRequestHash($method, $uri, $query, $header_str, $signed_header_str, $body_hash) {
    $canonical_request = $method . "\n";
    $canonical_request .= $uri . "\n";
    if($query == NULL) $query = "";
    $canonical_request .= $query . "\n";
    $canonical_request .= $header_str . "\n";
    $canonical_request .= $signed_header_str . "\n";
    $canonical_request .= $body_hash;

    return md5(utf8_encode($canonical_request));
  }

  private function getKeySignatureHash($key, $data) {
    $enc_key = utf8_encode($key);
    $enc_data = utf8_encode($data);
    return hash_hmac("md5", $enc_data, $enc_key);
  }
}
?>
