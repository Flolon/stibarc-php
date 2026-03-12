<?php

namespace STiBaRC\STiBaRC;

class Comment
{

	private $comment;
	private $postId;
	private $loggedIn;

	public function __construct($commentData, $postId, $loggedIn = false)
	{
		$this->comment = $commentData;
		$this->postId = $postId;
		$this->loggedIn = $loggedIn;
	}

	public function comment()
	{
		$poster = $this->comment->poster;
		$date = strtotime($this->comment->date);

		$commentHTML = '
		<div class="commentBlock" id="comment-' . $this->comment->id . '">
			<div>
				<a class="userlink" href="user.php?username=' . htmlspecialchars($poster->username) . '" 
				title="' . htmlspecialchars($poster->username) . '">
					<img class="pfp" width="30px" src="' . $poster->pfp . '">
					<span class="username">' . htmlspecialchars($poster->username) . '</span>
					' . ($poster->verified ? '<span class="verified" title="Verified user">
					<img class="icon" src="./img/icon/verified.png" height="14px" alt="Verified"></span>' : '') . '
				</a>
				<span class="pronouns" title="Pronouns">
					' . ($poster->displayPronouns && $poster->pronouns ? '(' . htmlspecialchars($poster->pronouns) . ')' : "") . '
				</span>
			</div>
			<span class="date" title="' . $this->comment->date . '">
				' . date("m/d/y, g:i A", $date) . '
			</span>
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
			<span class="meta">
				<a class="upvote" title="Upvotes" href="./do.php?id=' . $this->postId . '&action=vote&target=comment&commentId=' . $this->comment->id . '&vote=upvote">
				<img class="icon" src="./img/icon/up_arrow.png" height="14px" alt="Upvotes">'
			. $this->comment->upvotes . '</a>
				<a class="downvote" title="Downvotes" href="./do.php?id=' . $this->postId . '&action=vote&target=comment&commentId=' . $this->comment->id . '&vote=downvote">
				<img class="icon" src="./img/icon/down_arrow.png" height="14px" alt="Downvotes">'
			. $this->comment->downvotes . '</a>
				' . ($this->comment->attachments ?
				'<span class="attachments" title="Attachemnts"><img class="icon" src="./img/icon/attachment.png" height="20px" alt="Attachments">'
				. count($this->comment->attachments) . '</span>' : '') . '
			</span>
			<span class="options">' .
			(($this->loggedIn == $poster->username) ? '<a href="edit.php?id=' . $this->postId . '&commentId=' . $this->comment->id . '
			" title="Edit Comment"><img class="icon" src="./img/icon/edit.png" height="20px" alt="Edit"></a>' : '')
			. '
			<a href="./post.php?id=' . $this->postId . '#comment-' . $this->comment->id . '" title="Link to comment">
				<img class="icon" src="./img/icon/link.png" height="20px" alt="Link"></a>
			</span>
		</div>';

		return $commentHTML;
	}
}
