<?php
	
	class _initialFormView{
		public static function buildBody($context){
			
			return "
		<form action='Application.php' method='post'>
			<p>Use JSON:</p><p><input type='checkbox' name='useJSON' checked='Yes' /></p><br>
			<p>Request:</p><p><textarea name='request' rows='10' cols='60'>$context->request</textarea></p><br/>
			<p><input type='submit' value='Process triPOS Request'></p>
		 </form>
			";			
		} 				
	}
?>
