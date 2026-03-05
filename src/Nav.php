<?php

namespace STiBaRC\STiBaRC;

class Nav
{

	private $searchQuery;

	public function __construct($searchQuery = false)
	{
		$this->searchQuery = $searchQuery;
	}

	public function nav()
	{
		$navHTML = '
		<nav class="mainNav">
			<ul>
				<li class="logo"><a href="./"><img src="./img/logo_32.png" height="25px" alt="STiBaRC Home"></a></li>
				<li>
					<form action="search.php">
					<input type="search" name="q" placeholder="Search" value="'
			. ($this->searchQuery ? $this->searchQuery : '') . '">
					<button type="submit">Search</button>
				</form>
				</li>
			</ul>
			<ul class="right">';
		if (empty($_SESSION['sess'])) {
			$navHTML .= '<li><a href="./login.php">Login</a></li>';
		} else {
			$navHTML .= '<li class="navUser" title="Logged in as '
				. htmlspecialchars($_SESSION['username']) . '"><a href="./user.php?username='
				. htmlspecialchars($_SESSION['username']) . '"><img class="pfp" src="'
				. $_SESSION['pfp'] . '" height="26px" width="26px"><span>'
				. htmlspecialchars($_SESSION['username']) . '</span></a></li>
			<li><a href="./logout.php">Logout</a></li>';
		}
		$navHTML .= '
			</ul>
		</nav>';
		return $navHTML;
	}
}
