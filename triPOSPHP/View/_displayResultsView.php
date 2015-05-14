<?php
	include_once("Model/Context.php");
	
	class _displayResultsView{
		public static function buildBody($context){
			
			return "
			<form>
			<p><b>Status:</b> $context->status</p><br/>
			<p><b>Reqest:</b></p><br/>
			<p><textarea name='request' rows='10' cols='60'>$context->request</textarea></p><br/>
			<p><b>Response:</b></p><br/>
			<p><textarea name='response' rows='10' cols='60'>$context->response</textarea></p><br/>
			</form>
			";			
		} 				
	}
?>