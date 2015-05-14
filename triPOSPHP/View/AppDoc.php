<?php
	include_once('Model/Context.php');
	include_once('View/_initialFormView.php');
	include_once('View/_displayResultsView.php');
				
	class AppDoc{
		function __construct($context){
			$this->context = $context;
		}
	
		private $context;

		public function buildDocument(){
			$body = $this->buildBody();

			return "<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='utf-8' />
		<title>triPOS.PHP Integration</title>
		<meta name='viewport' content='width=device-width' />
	</head>
$body
</html>";
		}

		private function buildBody(){			

			if ($this->context->submitted) {
				$data = _displayResultsView::buildBody($this->context);
			} else {
				$data = _initialFormView::buildBody($this->context);
			}
				
			return "<body>

	<div id='body'>
$data
	</div>
	
	<footer> 
		<div class='content-wrapper'>
			<div class='float-left'>
				<p>&copy; 2015 - Element</p>
			</div>
		</div>
	</footer>
</body>
";
		}
		
	}
?>
