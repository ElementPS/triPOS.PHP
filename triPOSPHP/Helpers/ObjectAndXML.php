 
<?php
class ObjectAndXML {
  private static $xml;
 
	// Constructor
	public function __construct() {
		$this->xml = new XmlWriter();
		$this->xml->openMemory();
		$this->xml->setIndent(true);
	}
 
	// Method to convert Object into XML string
	public function objToXML($obj, $ns, $nsurl) {
		$this->xml->startElementNS($ns, get_class($obj), $nsurl);

		$this->getObject2XML($this->xml, $obj);
 
		$this->xml->endElement();

		$this->xml->endElement();
 
		return $this->xml->outputMemory(true);
	}
 
 
	private function getObject2XML(XMLWriter $xml, $data) {
		foreach($data as $key => $value) {
			if(is_object($value)) {   
				$xml->startElement($key);
				$this->getObject2XML($xml, $value);
				$xml->endElement();
				continue;
			}
			else if(is_array($value)) {
				$this->getArray2XML($xml, $key, $value);
			}
 
			else if (is_string($value)) {
				$xml->writeElement($key, $value);
			}
			else {
				$xml->writeElement($key, $value);
			}
		}
	}
 
	private function getArray2XML(XMLWriter $xml, $keyParent, $data) {
		foreach($data as $key => $value) {
			if (is_string($value)) {
				$xml->writeElement($keyParent, $value);
				continue;
			}
 
			if (is_numeric($key)) {
				$xml->startElement($keyParent);
			}
 
			if(is_object($value)) {
				$this->getObject2XML($xml, $value);
			}
			else if(is_array($value)) {
				$this->getArray2XML($xml, $key, $value);
				continue;
			}
 
			if (is_numeric($key)) {
				$xml->endElement();
			}
		}
	}
}
?>