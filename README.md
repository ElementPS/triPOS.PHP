# triPOS.PHP

* Questions?  certification@elementps.com
* **Feature request?** Open an issue.
* Feel like **contributing**?  Submit a pull request.

##Overview

This repository demonstrates an integration to the triPOS product using PHP.  The code was tested using PHP 5.3.28 installed with the Microsoft Web Platform Installer 5.0.  This allowed for testing the integration via Microsoft's IIS webserver.  After cloning the repository copy the triPOSPHP folder to your web root, then open a web browser and navigate to:  http://localhost/triPOSPHP/Application.php.

This sample application demonstrates sending a Credit Sale transaction using either XML and JSON payloads.

The first screenshot below shows the initial page where you can select to use JSON (checked) or XML (unchecked), modify the laneId, and see the hard coded JSON request that will be sent to triPOS for further processing.  

The second screenshot is the result of pressing the 'Process triPOS Request' button on the initial page.  Success/failure will be displayed along with the request sent to triPOS and the response received from triPOS.

![triPOS.PHP](https://github.com/ElementPS/triPOS.PHP/blob/master/screenshot1.PNG)

![triPOS.PHP](https://github.com/ElementPS/triPOS.PHP/blob/master/screenshot2.PNG)

##Prerequisites

Please contact your Integration Analyst for any questions about the below prerequisites.

* Register and download the triPOS application: https://mft.elementps.com/backend/plugin/Registration/ (once activated, login at https://mft.elementps.com)
* Create Express test account: http://www.elementps.com/Resources/Create-a-Test-Account
* Install and configure triPOS
* Optionally install a hardware peripheral and obtain test cards (but you can be up and running without hardware for testing purposes)
* Currently triPOS is supported on Windows 7

##Documentation/Troubleshooting

* To view the triPOS embedded API documentation point your favorite browser to: http://localhost:8080/help/ (for a default install).
* In addition to the help documentation above triPOS writes information to a series of log files located at:  C:\Program Files (x86)\Vantiv\triPOS Service\Logs (for a default install).

##Step 1: Generate a request package

You can generate either an XML request or a JSON request.  This example shows the JSON request.  Also notice that the value in laneId is 9999.  This is the 'null' laneId meaning a transaction will flow through the system without requiring hardware.  All lanes are configured in the triPOS.config file located at:  C:\Program Files (x86)\Vantiv\triPOS Service (if you kept the default installation directory).  If you modify this file make sure to restart the triPOS.NET service in the Services app to read in your latest triPOS.config changes.

```
{"address":{"BillingAddress1":"123 Sample Street","BillingAddress2":"Suite 101","BillingCity":"Chandler","BillingPostalCode":"85224","BillingState":"AZ"},"emvFallbackReason":"None","transactionAmount":3.25,"clerkNumber":"Clerk101","configuration":{"allowPartialApprovals":false,"checkForDuplicateTransactions":true,"currencyCode":"Usd","marketCode":"Retail"},"laneId":9999,"referenceNumber":"Ref000001","shiftId":"ShiftA","ticketNumber":"T0000001"}
```

##Step 2:Create message headers

The tp-authorization header below is only useful while testing as the full set of header information is not provided. If needed, refer to the integration guide for more information on constructing the headers needed for a production environment. To see an example, look in `Helpers/triPOSServiceHelper.php`.

```
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
```

##Step 3: Send request to triPOS

Use any http library to send a request to triPOS which is listening on a local address:  http://localhost:8080/api/v1/sale (if you kept the install default).

```
$ch = curl_init($context->serviceAddress);
curl_setopt($ch, CURLOPT_POSTFIELDS, $context->request);
array_push($headers, 'Content-Length: ' . strlen($context->request));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$callResponse = curl_exec($ch);
```

##Step 4: Receive response from triPOS

```
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
```

##Step 5: Parse response data

Some information from the response below has been removed for brevity. Currently the example code is not parsing the response but in a future version the XML response will be converted into a SaleResponse object.

```
{"cashbackAmount":0,"debitSurchargeAmount":0,"approvedAmount":3.25,"convenienceFeeAmount":0,"subTotalAmount":3.25,"tipAmount":0,"accountNumber":"************6781","binValue":"4003000000000000","cardHolderName":"GLOBAL PAYMENTS TEST CARD/","cardLogo":"Visa","currencyCode":"Usd","entryMode":"Swiped","paymentType":"Credit","signature":{},"terminalId":"0000009999","totalAmount":3.25,"approvalNumber":"000021","isApproved":true,"_processor":{"processorLogs":[],"processorRawResponse":"<CreditCardSaleResponse xmlns=\"https://transaction.elementexpress.com\"><Response><Address><BillingAddress1>123 Sample Street</BillingAddress1><BillingZipcode>85224</BillingZipcode></Address><Batch><HostBatchID>1</HostBatchID><HostItemID>131</HostItemID><HostBatchAmount>425.75</HostBatchAmount></Batch><Card><CardLogo>Visa</CardLogo></Card><ExpressResponseMessage>Approved</ExpressResponseMessage><ExpressTransactionDate>20150514</ExpressTransactionDate><ExpressTransactionTime>122139</ExpressTransactionTime><ExpressTransactionTimezone>UTC-05:00:00</ExpressTransactionTimezone><HostResponseCode>000</HostResponseCode><HostResponseMessage>AP</HostResponseMessage><Transaction><AcquirerData>aVb001234567810425c0425d5e00</AcquirerData><ApprovalNumber>000021</ApprovalNumber><ApprovedAmount>3.25</ApprovedAmount><ProcessorName>NULL_PROCESSOR_TEST</ProcessorName><ReferenceNumber>Ref000001</ReferenceNumber><TransactionID>2005013738</TransactionID><TransactionStatus>Approved</TransactionStatus><TransactionStatusCode>1</TransactionStatusCode></Transaction><ExpressResponseCode>0</ExpressResponseCode></Response></CreditCardSaleResponse>","processorReferenceNumber":"Ref000001","processorRequestFailed":false,"processorRequestWasApproved":true,"processorResponseCode":"Approved","processorResponseMessage":"Approved"},"statusCode":"Approved","transactionDateTime":"2015-05-14T10:21:39.0000000-07:00","transactionId":"2005013738","_errors":[],"_hasErrors":false,"_links":[],"_logs":[],"_type":"saleResponse","_warnings":[]}

```

##Other Examples
### triPOS Signature Output
See the file `signature.php` in `triPOS.PHP/triPOSPHP/Examples`

To run the example you can use the php built in web server:

`php -S localhost:8080 signature.php`

Then browse to `http://localhost:8080` and you should see "Hello" drawn from points in your browser.:

###©2014-2015 Element Payment Services, Inc., a Vantiv company. All Rights Reserved.

Disclaimer:
This software and all specifications and documentation contained herein or provided to you hereunder (the "Software") are provided free of charge strictly on an "AS IS" basis. No representations or warranties are expressed or implied, including, but not limited to, warranties of suitability, quality, merchantability, or fitness for a particular purpose (irrespective of any course of dealing, custom or usage of trade), and all such warranties are expressly and specifically disclaimed. Element Payment Services, Inc., a Vantiv company, shall have no liability or responsibility to you nor any other person or entity with respect to any liability, loss, or damage, including lost profits whether foreseeable or not, or other obligation for any cause whatsoever, caused or alleged to be caused directly or indirectly by the Software. Use of the Software signifies agreement with this disclaimer notice.


![Analytics](https://ga-beacon.appspot.com/UA-60858025-36/triPOS.PHP/readme?pixel)
