<?php
	class Configuration{
		/**
		 * 
		 * @var Boolean
		 * @requiredWhen\All
		 * @displayName\allowPartialApprovals
		 * 
		 */
		public $allowPartialApprovals = NULL;		

		/**
		 * 
		 * @var Boolean
		 * @requiredWhen\All
		 * @displayName\checkForDuplicateTransactions
		 * 
		 */
		public $checkForDuplicateTransactions = NULL;		

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\currencyCode
		 * 
		 */
		public $currencyCode = NULL;		

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\marketCode
		 * 
		 */
		public $marketCode = NULL;		
	}
?>