<?php

/**
 * View class
 *
 * @author RemyG
 * @license MIT
 */
class View {

	private $pageVars = array();
	private $template;

	public function __construct($template)
	{
		$this->template = APP_DIR .'views/'. $template .'.php';
	}

	public function set($var, $val)
	{
		$this->pageVars[$var] = $val;
	}

	public function render()
	{
		extract($this->pageVars);
		ob_start();
		include APP_DIR.'views/header.php';
		require($this->template);
		include APP_DIR.'views/footer.php';
		echo ob_get_clean();		
	}

	public function renderString()
	{
		extract($this->pageVars);
		ob_start();
		require($this->template);
		return ob_get_clean();
	}
}

?>