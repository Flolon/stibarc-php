<?php

namespace STiBaRC\STiBaRC;

class Post
{

	private $post;
	private $loggedIn;

	public function __construct($postData, $loggedIn)
	{
		$this->post = $postData;
		$this->loggedIn = $loggedIn;
	}

	public function post()
	{

		$poster = $this->post->poster;
		$date = strtotime($this->post->date);

		$postHTML = '
		<div class="postBlock">
			<h1 class="title">' . htmlspecialchars($this->post->title) . '</h1>
			<div>
				<a class="userLink" href="user.php?username=' . htmlspecialchars($poster->username) . '" 
				title="' . htmlspecialchars($poster->username) . '">
					<img class="pfp" width="30px" height="30px" src="' . $poster->pfp . '">
					<span class="username">' . htmlspecialchars($poster->username) . '</span>
					' . ($poster->verified ? '<span class="verified" title="Verified user">
					<img class="icon" src="./img/icon/verified.png" height="14px" alt="Verified"></span>' : '') . '
				</a>
				<span class="pronouns" title="Pronouns">
					' . ($poster->displayPronouns && $poster->pronouns ? '(' . htmlspecialchars($poster->pronouns) . ')' : "") . '
				</span>
			</div>
			<span class="date" title="' . $this->post->date . '">
					' . date("m/d/y, g:i A", $date) . '
			</span>'
			. ($this->post->edited ? '<div><span class="badge" title="Edited Post"><i>Edited</i></span></div>' : "") . '
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
				<a class="upvote" title="Upvotes" href="./do.php?id=' . $this->post->id . '&action=vote&target=post&vote=upvote">
				<img class="icon" src="./img/icon/up_arrow.png" height="14px" alt="Upvotes">'
			. $this->post->upvotes . '</a>
				<a class="downvote" title="Downvotes" href="./do.php?id=' . $this->post->id . '&action=vote&target=post&vote=downvote">
				<img class="icon" src="./img/icon/down_arrow.png" height="14px" alt="Downvotes">'
			. $this->post->downvotes . '</a>
				' . ($this->post->attachments ?
				'<span class="attachments" title="Attachemnts"><img class="icon" src="./img/icon/attachment.png" height="20px" alt="Attachments">'
				. count($this->post->attachments) . '</span>' : '')
			. ($this->post->private ? '<img class="icon" src="./img/icon/lock.png" height="14px" alt="Private Post" title="Private Post">' : "") . '
			<span class="options">' .
			(($this->loggedIn == $poster->username) ? '<a href="edit.php?id=' . $this->post->id  . '" 
				title="Edit Post"><img class="icon" src="./img/icon/edit.png" height="20px" alt="Edit"></a>' : '')
			. '
				<a href="./post.php?id=' . $this->post->id . '" title="Link to post">
					<img class="icon" src="./img/icon/link.png" height="20px" alt="Link"></a>
				</span>
			</div>
		</div>';

		return $postHTML;
	}
}
