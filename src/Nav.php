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
		return '
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
          </ul>
        </nav>';
	}
}
