<?php

namespace STiBaRC\STiBaRC;

class Attachment
{

	public $attachment;
	private $attachmentHTML;
	private $isPost;
	public $type;

	public function __construct($attachment, $isPost = false)
	{
		$this->attachment = $attachment;
		$this->isPost = $isPost;
		// file types
		$images = array("png", "jpg", "gif", "webp", "svg");
		$videos = array("mov", "mp4", "webm");
		$audios = array("spx", "m3a", "m4a", "wma", "wav", "mp3");
		$parts = explode(".", $this->attachment);
		$ext = $parts[count($parts) - 1];

		if (in_array($ext, $images))
			$this->type = "image";
		if (in_array($ext, $videos))
			$this->type = "video";
		if (in_array($ext, $audios))
			$this->type = "audio";
	}

	public function attachmentBlock()
	{
		if ($this->type === "image") {
			if ($this->isPost) {
				$this->attachmentHTML = '
				<a class="attachmentLink" href="' . $this->attachment . '" target="_blank">
					<img class="attachment" loading="lazy" src="' . $this->attachment . '.thumb.webp" alt="Image attachment">
				</a>';
			} else {
				$this->attachmentHTML = '
				<a class="attachmentLink" href="' . $this->attachment . '" target="_blank">
					<img class="imgPreview" loading="lazy" src="' . $this->attachment . '.thumb.webp" alt="Image attachment">
				</a>';
			}
		}

		if ($this->type === "video") {
			if ($this->isPost) {
				$this->attachmentHTML = '
				<video class="attachment" controls poster="' . $this->attachment . '.thumb.webp">
					<source src="' . $this->attachment . '"></source>
				</video>';
			} else {
				$this->attachmentHTML = '<a class="attachmentLink videoPreview" href="' . $this->attachment . '"
				title="Video attachment" target="_blank">
				<img loading="lazy" src="' . $this->attachment . '.thumb.webp" alt="Video attachment"></a>';
			}
		}

		if ($this->type === "audio") {
			if ($this->isPost) {
				$this->attachmentHTML = '
				<audio class="attachment" controls>
					<source src="' . $this->attachment . '"></source>
				</audio>';
			} else {
				$this->attachmentHTML = '<a class="attachmentLink" href="' . $this->attachment . '" 
				title="Audio attachment" target="_blank"><img class="icon audioPreview" src="./img/icon/audio.png" height="32px" alt="Audio attachment"></a>';
			}
		}

		return $this->attachmentHTML;
	}
}
