<?php
	class ObjectHelper {
		
		public static function toJson($source) {
			return json_encode((object)array_filter((array) $source));
		}
		
		public static function toJsonPretty($source) {
			return json_encode((object)array_filter((array) $source), JSON_PRETTY_PRINT);
		}
	}
?>
