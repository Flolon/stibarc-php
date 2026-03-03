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
                <img class="pfp" width="30px" src="' . $poster->pfp . '" alt="Pfp">
                <span class="username">' . htmlspecialchars($poster->username) . '</span>
                ' . ($poster->verified ? '<span class="verified" title="Verified user">
                <img class="icon" src="./img/icon/verified.png" height="14px" alt="Verified"></span>' : '') . '
            </a>
            <span class="pronouns" title="Pronouns">
                ' . ($poster->displayPronouns && $poster->pronouns ? '(' . htmlspecialchars($poster->pronouns) . ')' : "") . '
            </span>
            <div class="date" title="' . $this->post->date . '">
                ' . date("m/d/y h:i:s A", $date) . '
            </div>'
            . ($this->post->edited ? '<span class="badge" title="Edited Post"><i>Edited</i></span>' : "") . '
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
                <span class="upvote" title="Upvotes"><img class="icon" src="./img/icon/up_arrow.png" height="14px" alt="Upvotes">'
            . $this->post->upvotes . '</span>
                <span class="downvote" title="Downvotes"><img class="icon" src="./img/icon/down_arrow.png" height="14px" alt="Downvotes">'
            . $this->post->downvotes . '</span>
                <span class="comments" title="Comments"><img class="icon" src="./img/icon/comment.png" height="14px" alt="Comments">'
            . $this->post->comments . '</span>
                ' . ($this->post->attachments ?
                '<span class="attachments" title="Attachemnts"><span class="icon">&#128206;</span>'
                . count($this->post->attachments) . '</span>' : '')
            . ($this->post->private ? '<img class="icon" src="./img/icon/lock.png" height="14px" alt="Private Post" title="Private Post">' : "") . '
            </div>
        </div>
        ';

        return $postHTML;
    }
}
