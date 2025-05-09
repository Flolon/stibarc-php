<?php

namespace STiBaRC\STiBaRC;

class Nav
{

	public function __construct()
	{
		// starting state
	}

	public function nav()
	{
		return '
        <nav class="mainNav">
          <ul>
        	<li><a href="./">Home</a></li>
        	<li>
				<form action="search.php">
    	    	    <input type="search" name="q" placeholder="Search">
	            	<button type="submit">Search</button>
        		</form>
			</li>
          </ul>
        </nav>';
	}
}
