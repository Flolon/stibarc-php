<?php

namespace STiBaRC\STiBaRC;

class Comment
{

    private $comment;

    public function __construct($commentData)
    {
        $this->comment = $commentData;
    }

    public function comment()
    {
        $poster = $this->comment->poster;
        $date = strtotime($this->comment->date);

        $commentHTML = '
        <div class="commentBlock">
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
            <div class="date" title="' . $this->comment->date . '">
                ' . date("m/d/y g:i A", $date) . '
            </div>
            <hr>
			<div class="content">' . htmlspecialchars($this->comment->content) . '</div>';

        if ($this->comment->attachments) {
            foreach ($this->comment->attachments as $attachment) {
                $attachmentObj = new Attachment($attachment, false);
                $commentHTML .= $attachmentObj->attachmentBlock();
            }
        }

        $commentHTML .= '
            <hr>
            <div class="meta">
                <span class="upvote" title="Upvotes"><img class="icon" src="./img/icon/up_arrow.png" height="14px">' 
                . $this->comment->upvotes . '</span>
                <span class="downvote" title="Downvotes"><img class="icon" src="./img/icon/down_arrow.png" height="14px">' 
                . $this->comment->downvotes . '</span>
                ' . ($this->comment->attachments ? 
                '<span class="attachments" title="Attachemnts"><img class="icon" src="./img/icon/attachment.png" height="14px">' 
                . count($this->comment->attachments) . '</span>' : '') . '
            </div>
        </div>';

        return $commentHTML;
    }
}
