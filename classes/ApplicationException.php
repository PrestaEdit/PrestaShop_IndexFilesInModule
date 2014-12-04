<?php

class ApplicationException extends Exception
{
	/**
	 * This method acts like an error handler, if dev mode is on, display the error else use a better silent way
	 */
	public function displayMessage()
	{
		header('HTTP/1.1 500 Internal Server Error');
		// Display error message
		echo '<style>
			#applicationException{font-family: Verdana; font-size: 14px}
			#applicationException h2{color: #F20000}
		</style>';
		echo '<div id="applicationException">';
		echo '<h2>['.get_class($this).']</h2>';
		echo $this->getMessage();
		echo '<br /><br /><a href="index.php">Back to form</a>';
		echo '</div>';
		exit;
	}
}