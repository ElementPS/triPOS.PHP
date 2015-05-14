<?php
	class Address {
		
		function __construct(){
		}

		
		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\BillingAddress1
		 * 
		 */
		public $BillingAddress1 = NULL;
		
		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\BillingAddress2
		 * 
		 */
		public $BillingAddress2 = NULL;

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\BillingCity
		 * 
		 */
		public $BillingCity= NULL;

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\BillingPostalCode
		 * 
		 */
		public $BillingPostalCode = NULL;

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\BillingState
		 * 
		 */
		public $BillingState = NULL;
		
	}
?>