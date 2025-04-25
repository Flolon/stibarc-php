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

    public function __construct($environment = "development", $debug)
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

        // $this->session = $_SESSION["sess"];
        // $this->username = $_SESSION["username"];
        // $this->pfp = $_SESSION["pfp"] || $this->cdn . "/pfp/default.png";
        // $this->banner = $_SESSION["banner"];

        // $this->connect();
    }

    public function __destruct()
    {
        $this->clearSess();
    }

    public function clearSess()
    {
        $_SESSION = array();
    }

    public function request($url)
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
        $result = curl_exec($ch);
        // get content header //
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        // close curl
        curl_close($ch);
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
        $response = $this->request($this->host . "/v4/getposts.sjs");

        $responseJSON = json_decode($response);

        if ($responseJSON->status !== "ok") {
            echo $this->debug ? "Failed to fetch posts: " . $response : "";
        }
        return $responseJSON->globalPosts;
    }

    public function getPost($postId)
    {
        $response = $this->request($this->host . "/v4/getpost.sjs?id=" . $postId);

        $responseJSON = json_decode($response);

        if ($responseJSON->status !== "ok") {
            echo $this->debug ? "Failed to fetch posts: " . $response : "";
        }
        return $responseJSON->post;
    }
}
