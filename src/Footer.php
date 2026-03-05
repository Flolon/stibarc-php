<?php

namespace STiBaRC\STiBaRC;

class Footer
{

	public function __construct() {}

	public function footer()
	{
		$footerHTML = '
			<footer>
				<span>&copy; ' . date("Y") . ' STiBaRC</span>
			</footer>
		';
		return $footerHTML;
	}
}
