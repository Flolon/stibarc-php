<?php

namespace STiBaRC\STiBaRC;

class Footer
{

	public function __construct() {}

	public function footer()
	{
		$footerHTML = '
			<footer class="footer">

				';
		if (!empty($_SESSION['sess'])) {
			$footerHTML .= '
				<ul style="margin-bottom: 12px;">
					<li><a class="button" href="./logout.php" title="Logout STiBaRC session">Logout</a></li>
				</ul>';
		}
		$footerHTML .= '
				<ul>
					<li>&copy; ' . date("Y") . ' <a href="https://stibarc.com">STiBaRC</a></li>
					<li class="spacer">|</li>
					<li><a href="https://stibarc.com/privacy.html">Privacy</a></li>
					<li class="spacer">|</li>
					<li><a href="https://stibarc.com/tos.html">Terms</a></li>
				</ul>
			</footer>
		';
		return $footerHTML;
	}
}
