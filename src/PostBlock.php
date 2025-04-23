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

        $postBlockHMTML = '
        <div class="postblock">
            <a class="title" href="post.php?id=' . $this->post->id . '">
            ' . htmlspecialchars($this->post->title) . '</a>
            <a class="userlink" title="' . htmlspecialchars($poster->username) . '">
                <img class="pfp" width="25px" src="' . $poster->pfp . '">
                <span class="username">' . htmlspecialchars($poster->username) . '</span>
                ' . ($poster->verified ? '<span class="verified">&#10004;</span>' : '') . '
                <span class="pronouns">
                ' . ($poster->pronouns ? '(' . htmlspecialchars($poster->pronouns) . ')' : "") . '
                </span>
            </a>
            <div class="date" title="' . $this->post->date . '">
                ' . date("m/d/y h:i:s A", $date) . '
            </div>
            <div class="meta">
                <span class="upvote" title="Upvotes"><span class="icon">&#8679;</span>
                ' . $this->post->upvotes . '</span>
                <span class="downvote" title="Downvotes"><span class="icon">&#8681;</span>
                ' . $this->post->downvotes . '</span>
                <span class="comments" title="Comments"><span class="icon">&#128488;</span>
                ' . $this->post->comments . '</span>
                ' . ($this->post->attachments ? '<span class="attachments" title="Attachemnts"><span class="icon">&#128206;</span>
                ' . count($this->post->attachments) : '') . '</span>
            </div>
        </div>';
        
        return $postBlockHMTML;
    }
}
