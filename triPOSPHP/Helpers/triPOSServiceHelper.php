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
				'Content-Type: ' . $payloadFormat,
				'Accept: ' . $payloadFormat,
				'tp-application-id: 1234',
				'tp-application-name: triPOS.PHP',
				'tp-application-version: 1.0.0',
				'tp-authorization: ' . $tpAuthorization,
				'tp-return-logs: false',
				);

		if($context->request != null){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $context->request);
			array_push($headers, 'Content-Length: ' . strlen($context->request));
		}
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
	
	public static function postSubmissionToTriPOS($context){

		$context = triPOSServiceHelper::callTriPOS($context);
		
		return $context;
	}	
}
?>
