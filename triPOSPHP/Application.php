<?php
	include_once("Model/SaleRequest.php");
	include_once("Model/Address.php");
	include_once("Model/Configuration.php");
	include_once("View/AppDoc.php");
	include_once("Helpers/triPOSServiceHelper.php");
	include_once("Helpers/ObjectHelper.php");
	include_once("Helpers/ObjectAndXML.php");
	include_once("Model/Context.php");

	$context = new Context();
	$context->pathToTriPOSConfig = "C:\\Program Files (x86)\\Vantiv\\triPOS Service\\triPOS.config";
	$context->serviceAddress = "http://localhost:8080/api/v1/sale";
	$context->tpAuthorizationVersion = "1.0";
	$context->xmlNameSpaceName = "ns2";
	$context->xmlNameSpaceURL = "http://tripos.vantiv.com/2014/09/TriPos.Api";
	$context->submitted = False;

	$model = new SaleRequest();
	$model->address = new Address();
	$model->address->BillingAddress1 = "123 Sample Street";
	$model->address->BillingAddress2 = "Suite 101";
	$model->address->BillingCity = "Chandler";
	$model->address->BillingPostalCode = "85224";
	$model->address->BillingState = "AZ";
	
	$model->cashbackAmount = 0.0;
	$model->confenienceFeeAmount = 0.0;
	$model->emvFallbackReason = "None";
	$model->tipAmount = 0.0;
	$model->transactionAmount = 3.25;
	$model->clerkNumber = "Clerk101";
	
	$model->configuration = new Configuration();
	$model->configuration->allowPartialApprovals = false;
	$model->configuration->checkForDuplicateTransactions = true;
	$model->configuration->currencyCode = "Usd";
	$model->configuration->marketCode = "Retail";

	$model->laneId = 9999;
	$model->referenceNumber = "Ref000001";
	$model->shiftId = "ShiftA";
	$model->ticketNumber = "T0000001";

	$context->model = $model;
	$context->useJSON = True;
	$context->request = ObjectHelper::toJson($context->model);

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if(isset($_POST['laneId'])) {
			$context->model->laneId = (int) $_POST['laneId'];
		}

		if(isset($_POST['useJSON']) && $_POST['useJSON'] == 'on') {
			$context->useJSON = True;
			$context->request = ObjectHelper::toJson($context->model);
		} else {
			$context->useJSON = False;
			$xmlHelper = new ObjectAndXML();	
			$context->request = $xmlHelper->objToXML($context->model, $context->xmlNameSpaceName, $context->xmlNameSpaceURL);
		}

		$context->submitted = True;

		if (file_exists($context->pathToTriPOSConfig)) {
      $xml = simplexml_load_file($context->pathToTriPOSConfig); 
      $dev_key = $xml->developers[0]->developer[0]->developerKey[0];
      $dev_secret = $xml->developers[0]->developer[0]->developerSecret[0];
			$context->tpAuthorizationCredential = $dev_key;
			$context->tpAuthorizationSecret = $dev_secret;
		} else {
			echo "File does not exist";
		}
	
		$context = triPOSServiceHelper::callTriPOS($context);
	}
	
	$doc = new AppDoc($context);

	echo $doc->buildDocument();
	
?>
