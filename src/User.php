<?php

namespace STiBaRC\STiBaRC;

class UserBlock
{

	private $userData;

	public function __construct($userData)
	{
		$this->userData = $userData;
	}

	public function user()
	{
		$user = $this->userData;
		$followerUsernames = [];
		$followingUsernames = [];
		foreach ($user->followers as $follower) {
			array_push($followerUsernames, htmlspecialchars($follower->username));
		}
		foreach ($user->following as $follower) {
			array_push($followingUsernames, htmlspecialchars($follower->username));
		}

		$userHTML = '
        <div class="userBlock">
			<div class="userlink" title="' . htmlspecialchars($user->username) . '">
				<img class="pfp" width="50px" src="' . $user->pfp . '">
				<span class="username">' . htmlspecialchars($user->username) . '</span>
				' . ($user->verified ? '<span class="verified">&#10004;</span>' : '') . '
				<span class="pronouns">
				' . ($user->displayPronouns && $user->pronouns ? '(' . htmlspecialchars($user->pronouns) . ')' : "") . '
				</span>
            </div>
			<div>
				' . ($user->displayName && $user->name ? '<div>Name: ' . htmlspecialchars($user->name) . '</div>' : '') . '
				' . ($user->displayBio && $user->bio ? '<div class="bio">' . htmlspecialchars($user->bio) . '</div>' : '') . '
				' . ($user->displayEmail && $user->email ? '<div>Email: ' . htmlspecialchars($user->email) . '</div>' : '') . '
				' . ($user->displayBirthday && $user->birthday ? '<div title="' . htmlspecialchars($user->birthday) .
			'">Birthday: ' . htmlspecialchars($user->birthday) . '</div>' : '') . '
				<div>Rank: ' . htmlspecialchars($user->rank) . '</div>
				<div class="followCounts">
					<span title="' . implode(", ", $followerUsernames) . '">Followers: '
			. count($user->followers) . '</span>
					<span  title="' . implode(", ", $followingUsernames) . '">Following: '
			. count($user->following) . '</span>
				</div>
			</div>
		</div>';

		return $userHTML;
	}

	public function userBlock()
	{
		$user = $this->userData;

		$userHTML = '
			<div class="userBlock">
				<a class="userlink" href="./user.php?username=' . htmlspecialchars($user->username) . '">
					<img class="pfp" width="50px" src="' . $user->pfp . '">
					<span class="username">' . htmlspecialchars($user->username) . '</span>
					' . ($user->verified ? '<span class="verified">&#10004;</span>' : '') . '
					<span class="pronouns">
					' . ($user->displayPronouns && $user->pronouns ? '(' . htmlspecialchars($user->pronouns) . ')' : "") . '
					</span>
				</a>
				<div>
					' . ($user->displayName && $user->name ? '<div>Name: ' . htmlspecialchars($user->name) . '</div>' : '') . '
					' . ($user->displayBio && $user->bio ? '<div class="bio">' . htmlspecialchars($user->bio) . '</div>' : '') . '
				</div>
			</div>';

		return $userHTML;
	}
}
