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
            <a class="userlink" href="user.php?username=' . $poster->username . '" 
            title="' . htmlspecialchars($poster->username) . '">
                <img class="pfp" height="26px" src="' . $poster->pfp . '">
                <span class="username">' . htmlspecialchars($poster->username) . '</span>
                ' . ($poster->verified ? '<span class="verified">&#10004;</span>' : '') . '
                <span class="pronouns">
                ' . ($poster->displayPronouns && $poster->pronouns ? '(' . htmlspecialchars($poster->pronouns) . ')' : "") . '
                </span>
            </a>
            <div class="date" title="' . $this->comment->date . '">
                ' . date("m/d/y h:i:s A", $date) . '
            </div>
			<div class="content">' . htmlspecialchars($this->comment->content) . '</div>';

        if ($this->comment->attachments) {
            foreach ($this->comment->attachments as $attachment) {
                $attachmentObj = new Attachment($attachment, false);
                $commentHTML .= $attachmentObj->attachmentBlock();
            }
        }

        $commentHTML .= '
            <div class="meta">
                <span class="upvote" title="Upvotes"><span class="icon">&#8679;</span>
                ' . $this->comment->upvotes . '</span>
                <span class="downvote" title="Downvotes"><span class="icon">&#8681;</span>
                ' . $this->comment->downvotes . '</span>
                ' . ($this->comment->attachments ? '<span class="attachments" title="Attachemnts"><span class="icon">&#128206;</span>
                ' . count($this->comment->attachments) : '') . '</span>
            </div>
        </div>';

        return $commentHTML;
    }
}
