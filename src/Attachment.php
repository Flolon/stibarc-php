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
				<a class="attachmentLink" href="' . $this->attachment . '">
					<img class="attachment" loading="lazy" src="' . $this->attachment . '.thumb.webp">
				</a>';
			} else {
				$this->attachmentHTML = '
				<a class="attachmentLink" href="' . $this->attachment . '">
					<img class="imgPreview" loading="lazy" src="' . $this->attachment . '.thumb.webp">
				</a>';			}

		}

		if (in_array($ext, $videos)) {
			$type = "video";
			if ($this->isPost) {
				$this->attachmentHTML = '
				<video class="attachment" controls poster="' . $this->attachment . '.thumb.webp">
					<source src="' . $this->attachment . '"></source>
				</video>';
			} else {
				$this->attachmentHTML = '<div class="videoPreview"><img loading="lazy" src="' . $this->attachment . '.thumb.webp"></div>';
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
				$this->attachmentHTML = '<div class="audioPreview" title="Audio Attachment">&#128266;</div>';
			}
		}

		return $this->attachmentHTML;
	}
}
