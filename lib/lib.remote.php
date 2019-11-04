<?

class remotePost {
    private $client;
    private $wpURL = '';
    private $ixrPath = 'lib/class.xml-rpc.php';
    private $username = '';
    private $password = '';

    public $postID;
    function __construct($url, $username, $password, $content) {
        if (!is_array($content)) throw new Exception('Invalid Argument');
        require_once $this->ixrPath;
        $this->wpURL = $url;
        $this->username = $username;
        $this->password = $password;
        
        $this->client = new IXR_Client($this->wpURL);
        $this->postID = $this->postContent($content);            
        $this->arr = $this->getRecentPost();
    }

    private function postContent($content) {
        if(!$this->client->query('metaWeblog.newPost', '', $this->username, $this->password, $content, true)) throw new Exception($this->client->getErrorMessage());
        return $this->client->getResponse();
    }

    private function getRecentPost() {
        if(!$this->client->query('metaWeblog.getRecentPosts', '', $this->username, $this->password, '1', true))  echo 'Error';
        return $this->client->getResponse();
    }
}

?>