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
        	<li><a href="./">Home</a></li>
        	<li>
				<form action="search.php">
    	    	    <input type="search" name="q" placeholder="Search" value="'
			. ($this->searchQuery ? $this->searchQuery : '') . '">
	            	<button type="submit">Search</button>
        		</form>
			</li>
			';
		if (empty($_SESSION['sess'])) {
			$navHTML .= '<li><a href="./login.php">Login</a></li>';
		} else {
			$navHTML .= '<li><a href="./logout.php" title="Logged in as ' . $_SESSION['username'] . '">Logout</a></li>';
		}
		$navHTML .= '
          </ul>
        </nav>';
		return $navHTML;
	}
}
