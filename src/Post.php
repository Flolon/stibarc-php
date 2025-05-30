<?php

namespace STiBaRC\STiBaRC;

require('Attachment.php');

class Post
{

    private $post;

    public function __construct($postData)
    {
        $this->post = $postData;
    }

    public function post()
    {

        $poster = $this->post->poster;
        $date = strtotime($this->post->date);

        $postHTML = '
        <div class="postBlock">
            <h1 class="title">' . htmlspecialchars($this->post->title) . '</h1>
            <a class="userlink" href="user.php?username=' . $poster->username . '" 
            title="' . htmlspecialchars($poster->username) . '">
                <img class="pfp" width="35px" src="' . $poster->pfp . '">
                <span class="username">' . htmlspecialchars($poster->username) . '</span>
                ' . ($poster->verified ? '<span class="verified">&#10004;</span>' : '') . '
                <span class="pronouns">
                ' . ($poster->displayPronouns && $poster->pronouns ? '(' . htmlspecialchars($poster->pronouns) . ')' : "") . '
                </span>
            </a>
            <div class="date" title="' . $this->post->date . '">
                ' . date("m/d/y h:i:s A", $date) . '
            </div>'
            . ($this->post->edited ? '<span class="badge" title="Edited Post"><i>Edited</i></span>' : "") . '
            <hr>
			<div class="content">' . htmlspecialchars($this->post->content) . '</div>
            ';

        if ($this->post->attachments) {
            foreach ($this->post->attachments as $attachment) {
                $attachmentObj = new Attachment($attachment, true);
                $postHTML .= $attachmentObj->attachmentBlock();
            }
        }


        $postHTML .= '
        <hr>
            <div class="meta">
                <span class="upvote" title="Upvotes"><span class="icon">&#8679;</span>'
            . $this->post->upvotes . '</span>
                <span class="downvote" title="Downvotes"><span class="icon">&#8681;</span>'
            . $this->post->downvotes . '</span>
                <span class="comments" title="Comments"><span class="icon">&#128488;</span>'
            . count($this->post->comments) . '</span>
                ' . ($this->post->attachments ?
                '<span class="attachments" title="Attachemnts"><span class="icon">&#128206;</span>'
                . count($this->post->attachments) . '</span>' : '')
            . ($this->post->private ? '<span class="icon" title="Private Post">&#128274; </span>' : "") . '
            </div>
        </div>
        ';

        return $postHTML;
    }
}
