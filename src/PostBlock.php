<?php

namespace STiBaRC\STiBaRC;

class PostBlock
{

    public $post;

    public function __construct($postData)
    {
        $this->post = $postData;
    }

    public function post()
    {

        $poster = $this->post->poster;
        $date = strtotime($this->post->date);

        return '
        <div class="postblock">
            <div class="title">' . $this->post->title . '</div>
            <a class="userlink" title="' . ($poster->verified ? "Verified" : $poster->username) . '">
                <img class="pfp" width="25px" src="' . $poster->pfp . '">
                <span class="username">' . $poster->username . '
                <span class="pronouns">' . ($poster->pronouns ? '(' . $poster->pronouns . ')' : "") . '</span>
                </span>
            </a>
            <div class="date" title="' . $this->post->date . '">
                ' . date("m/d/y h:i:s A", $date) . '
            </div>
            <div class="meta">
                <span class="upvote"><span class="icon">&#8679</span>
                ' . $this->post->upvotes .'</span>
                <span class="downvote"><span class="icon">&#8681</span>
                ' . $this->post->downvotes . '</span>
                <span class="comments"><span class="icon">&#128488</span>
                ' . $this->post->comments . '</span>
            </div>
        </div>';
    }
}
