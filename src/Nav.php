<?php

namespace STiBaRC\STiBaRC;

class Nav
{

    public $post;

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
			</ul>
        </nav>';
    }
}
