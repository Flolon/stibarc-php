<?php

namespace STiBaRC\STiBaRC;

class API
{

    private $debug;
    private $host;
    private $cdn;
    private $session;
    private $username;
    private $pfp;
    private $banner;
    private $private;

    public function __construct($environment, $debug)
    {
        // debug vars
        $this->debug = $debug;
        $this->debug ? error_reporting(E_ALL) : "";

        switch ($environment) {
            default:
            case "development":
                // $this->host = "https://api-dev.stibarc.com";
                // $this->cdn = "https://cdn-dev.stibarc.com";
                $this->host = "https://betaapi.stibarc.com";
                $this->cdn = "https://betacdn.stibarc.com";
                break;
            case "staging":
                $this->host = "https://api-staging.stibarc.com";
                $this->cdn = "https://cdn-staging.stibarc.com";
                break;
            case "production":
                $this->host = "https://api.stibarc.com";
                $this->cdn = "https://cdn.stibarc.com";
                break;
        }

        $this->session = $_SESSION["sess"] ?? null;
        $this->username = $_SESSION["username"] ?? null;
        $this->pfp = $_SESSION["pfp"] ?? $this->cdn . "/pfp/default.png";
        $this->banner = $_SESSION["banner"] ?? $this->cdn . "/banner/default.png";
        $this->private = $_SESSION["private"] ?? null;
    }

    public function __destruct()
    {
        // object destruct
    }

    public function clearSess()
    {
        $_SESSION = array();
        $this->session = null;
        $this->username = null;
        $this->pfp = $this->cdn . "/pfp/default.png";
        $this->banner = $this->cdn . "/banner/default.png";
        $this->private = null;
    }

    public function setSess($session)
    {
        $this->session = $session;
        $_SESSION["sess"] = $this->session;
    }

    public function request($url, $type = "GET", $post_data = [])
    {
        // initialize curl
        $ch = curl_init();
        // set options
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        // set user agent //
        curl_setopt($ch, CURLOPT_USERAGENT, 'STiBaRC PHP');
        // send post data if post request
        if ($type == "POST") {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Origin: https://stibarc.com']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        }
        // get response
        $result = curl_exec($ch);
        // get errors
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        // get content header //
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        // close curl
        curl_close($ch);
        if ($curl_errno > 0)
            echo "cURL Error ($curl_errno): $curl_error\n";
        return $result;
    }

    public function getAnnouncement()
    {
        $response = $this->request($this->host . "/v4/getannouncement.sjs");

        $responseJSON = json_decode($response);

        if ($responseJSON->status !== "ok") {
            echo $this->debug ? "Failed to fetch announcement: " . $response : "";
        }
        return $responseJSON->announcement ?? false;
    }

    public function getPosts()
    {
        $body = [];
        if ($this->session)
            $body["session"] = $this->session;

        $response = $this->request($this->host . "/v4/getposts.sjs", "POST", $body);

        $responseJSON = json_decode($response);

        if ($responseJSON->status !== "ok") {
            echo $this->debug ? "Failed to fetch posts: " . $response : "";
        }
        return $responseJSON->globalPosts;
    }

    public function getPost($postId)
    {
        $body = [
            "id" => $postId
        ];
        if ($this->session)
            $body["session"] = $this->session;

        $response = $this->request($this->host . "/v4/getpost.sjs", "POST", $body);

        $responseJSON = json_decode($response);

        if ($responseJSON->status !== "ok") {
            echo $this->debug ? "Failed to fetch post: " . $response : "";
        }
        return $responseJSON->post ?? false;
    }

    public function getUser($username)
    {
        $body = [
            "username" => $username
        ];
        if ($this->session)
            $body["session"] = $this->session;

        $response = $this->request($this->host . "/v4/getuser.sjs", "POST", $body);

        $responseJSON = json_decode($response);

        if ($responseJSON->status !== "ok") {
            echo $this->debug ? "Failed to fetch user: " . $response : "";
        }
        return $responseJSON->user ?? false;
    }

    public function search($query)
    {
        $body = [
            "query" => $query
        ];

        $response = $this->request($this->host . "/v4/search.sjs", "POST", $body);

        $responseJSON = json_decode($response);

        if ($responseJSON->status !== "ok") {
            echo $this->debug ? "Failed to fetch search results: " . $response : "";
        }
        return $responseJSON->results ?? false;
    }

    public function login($username, $password)
    {
        $body = [
            'username' => $username,
            'password' => $password
        ];

        $response = $this->request($this->host . "/v4/login.sjs", "POST", $body);

        $responseJSON = json_decode($response);

        $errorText = false;
        if ($responseJSON->status !== "ok") {
            switch ($responseJSON->errorCode) {
                case "totpr":
                    $errorText = "2FA code required";
                case "iuop":
                    $errorText = "Invalid username or password";
                case "itotp":
                    $errorText = "Invalid 2FA code";
                case "banned":
                    $errorText = "User is banned";
                default:
                    $errorText = "Failed to login";
            }
        }
        if (!$errorText) {
            $this->session = $responseJSON->session;
            $this->username = $responseJSON->username;
            $this->pfp = $responseJSON->pfp;
            $this->banner = $responseJSON->banner;
            $this->private = $responseJSON->private;
            $_SESSION["sess"] = $this->session;
            $_SESSION["username"] = $this->username;
            $_SESSION["pfp"] = $this->pfp;
            $_SESSION["banner"] = $this->banner;
            $_SESSION["private"] = $this->private;
            return [
                "error" => false,
                "session" => $this->session,
                "username" => $this->username,
                "pfp" => $this->pfp,
                "banner" => $this->banner,
                "private" => $this->private
            ];
        } else {
            return [
                "error" => $responseJSON->error,
                "errorText" => $errorText
            ];
        }
    }

    public function logout()
    {
        $body = [
            "session" => $this->session
        ];

        $response = $this->request($this->host . "/v4/logout.sjs", "POST", $body);

        $responseJSON = json_decode($response);

        if ($responseJSON->status !== "ok") {
            echo $this->debug ? "Failed to logout: " . $response : "";
        }
        $this->clearSess();
        return $responseJSON ?? false;
    }

    public function getPrivateData()
    {
        $body = [
            "session" => $this->session
        ];

        $response = $this->request($this->host . "/v4/getprivatedata.sjs", "POST", $body);

        $responseJSON = json_decode($response);

        $errorText = false;
        if ($responseJSON->status !== "ok") {
            switch ($responseJSON->errorCode) {
                case "is":
                    $errorText = "Invalid Session";
                default:
                    $errorText = "Failed to login";
            }
        }
        if (!$errorText) {
            $this->username = $responseJSON->user->username;
            $this->pfp = $responseJSON->user->pfp;
            $this->banner = $responseJSON->user->banner;
            $this->private = $responseJSON->user->private;
            $_SESSION["sess"] = $this->session;
            $_SESSION["username"] = $this->username;
            $_SESSION["pfp"] = $this->pfp;
            $_SESSION["banner"] = $this->banner;
            $_SESSION["private"] = $this->private;
            return [
                "error" => false,
                "session" => $this->session,
                "username" => $this->username,
                "pfp" => $this->pfp,
                "banner" => $this->banner,
                "private" => $this->private
            ];
        } else {
            return [
                "error" => $responseJSON->error,
                "errorText" => $errorText
            ];
        }
    }
}
