<?php
class SaleRequest {
		/**
		 * 
		 * @var Address
		 * @requiredWhen\All
		 * @displayName\address
		 * 
		 */
		public $address = NULL;

		/**
		 * 
		 * @var double
		 * @requiredWhen\All
		 * @displayName\cashbackAmount
		 * 
		 */
		public $cashbackAmount = NULL;

		/**
		 * 
		 * @var double
		 * @requiredWhen\All
		 * @displayName\convenienceFeeAmount
		 * 
		 */
		public $convenienceFeeAmount = NULL;

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\emvFallbackReason
		 * 
		 */
		public $emvFallbackReason = NULL;

		/**
		 * 
		 * @var double
		 * @requiredWhen\All
		 * @displayName\tipAmount
		 * 
		 */
		public $tipAmount = NULL;

		/**
		 * 
		 * @var double
		 * @requiredWhen\All
		 * @displayName\transactionAmount
		 * 
		 */
		public $transactionAmount = NULL;

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\clerkNumber
		 * 
		 */
		public $clerkNumber = NULL;

		/**
		 * 
		 * @var Configuration
		 * @requiredWhen\All
		 * @displayName\configuration
		 * 
		 */
		public $configuration = NULL;

		/**
		 * 
		 * @var int
		 * @requiredWhen\All
		 * @displayName\laneId
		 * 
		 */
		public $laneId = NULL;

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\referenceNumber
		 * 
		 */
		public $referenceNumber = NULL;

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\shiftId
		 * 
		 */
		public $shiftId = NULL;

		/**
		 * 
		 * @var String
		 * @requiredWhen\All
		 * @displayName\ticketNumber
		 * 
		 */
		public $ticketNumber = NULL;
}
?>