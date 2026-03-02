<?php

namespace STiBaRC\STiBaRC;

class Attachment
{

	private $attachment;
	private $localFileName;
	private $attachmentHTML;
	private $isPost;

	public function __construct($attachment, $isPost = false)
	{
		$this->attachment = $attachment;
		$this->isPost = $isPost;
	}

	public function attachmentBlock()
	{

		// file types
		$images = array("png", "jpg", "gif", "webp", "svg");
		$videos = array("mov", "mp4", "webm");
		$audios = array("spx", "m3a", "m4a", "wma", "wav", "mp3");
		$parts = ($this->localFileName !== null) ? explode(".", $this->localFileName) : explode(".", $this->attachment);
		$ext = $parts[count($parts) - 1];

		if (in_array($ext, $images)) {
			$type = "img";
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

		if (in_array($ext, $videos)) {
			$type = "video";
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

		if (in_array($ext, $audios)) {
			$type = "audio";
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
