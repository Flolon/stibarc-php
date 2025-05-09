<?php

namespace STiBaRC\STiBaRC;

require('Attachment.php');

class PostBlock
{

    private $post;
    private $showAttachments;
    private $maxCharLength;

    public function __construct($postData, $showAttachments = true, $maxCharLength = 150)
    {
        $this->post = $postData;
        $this->showAttachments = $showAttachments;
        $this->maxCharLength = $maxCharLength;
    }

    public function post()
    {

        $poster = $this->post->poster;
        $date = strtotime($this->post->date);
        $title = $this->post->title;
        $contentPreview = $this->post->content;
        if (strlen($this->post->title) > $this->maxCharLength)
            $title = substr($this->post->title, 0, ($this->maxCharLength - 3)) . '...';
        if (strlen($this->post->content) > $this->maxCharLength)
            $contentPreview = substr($this->post->content, 0, ($this->maxCharLength - 3)) . '...';

        $postHTML = '
        <div class="postBlock postPreview">
            <a class="title" href="post.php?id=' . $this->post->id . '" title="' 
            . htmlspecialchars($this->post->title) . '">' 
            . htmlspecialchars($title) . '</a>
            <a class="userlink" href="user.php?username=' . htmlspecialchars($poster->username) . '" 
            title="' . htmlspecialchars($poster->username) . '">
                <img class="pfp" width="28px" src="' . $poster->pfp . '">
                <span class="username">' . htmlspecialchars($poster->username) . '</span>
                ' . ($poster->verified ? '<span class="verified">&#10004;</span>' : '') . '
                <span class="pronouns">
                ' . ($poster->displayPronouns && $poster->pronouns ? '(' . htmlspecialchars($poster->pronouns) . ')' : "") . '
                </span>
            </a>
            <div class="date" title="' . $this->post->date . '">
                ' . date("m/d/y h:i:s A", $date) . '
            </div>
            <hr>
            <div class="content">' . htmlspecialchars($contentPreview) . '</div>
            ';

        if ($this->showAttachments && $this->post->attachments) {
            $attachment = $this->post->attachments[0];
            $attachmentObj = new Attachment($attachment, false);
            $postHTML .= $attachmentObj->attachmentBlock();
        }

        $postHTML .= '
        <hr>
            <div class="meta">
                <span class="upvote" title="Upvotes"><span class="icon">&#8679;</span>' 
                . $this->post->upvotes . '</span>
                <span class="downvote" title="Downvotes"><span class="icon">&#8681;</span>' 
                . $this->post->downvotes . '</span>
                <span class="comments" title="Comments"><span class="icon">&#128488;</span>' 
                . $this->post->comments . '</span>
                ' . ($this->post->attachments ? 
                '<span class="attachments" title="Attachemnts"><span class="icon">&#128206;</span>' 
                . count($this->post->attachments) . '</span>' : '') . '
            </div>
        </div>
        ';

        return $postHTML;
    }
}
