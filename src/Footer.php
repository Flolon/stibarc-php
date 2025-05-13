<?php

namespace STiBaRC\STiBaRC;

class Footer
{

	public function __construct() {}

	public function footer()
	{
		$footerHTML = '
		    <footer>
				<span>&copy; 2025 STiBaRC</span>
			</footer>
        ';
		return $footerHTML;
	}
}
