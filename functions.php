<?php

function slashes($var) {
    if(is_array($var)) {
        return array_map('slashes', $var);
    } else {
        return addslashes($var);
    }
}

function shortString($string, $limit) {
    if (strlen($string) > ($limit + '3')) {
        $string = substr($string, '0' ,$limit) . '...';
        echo $string;
    } else {
        echo $string;;
    }
}

function url($parameter, $value) {
    $params = array();
    $output = '?';
    $firstRun = true;
    foreach($_GET as $key=>$val) {
        if($key != $parameter) {
            if(!$firstRun) {
                $output .= '&';
            } else {
                $firstRun = false;
            }

            $output .= $key . '=' . urlencode($val);
         }
    }

    if(!$firstRun)
        $output .= '&';
    $output .= $parameter . '=' . urlencode($value);
    return htmlentities($output);
}

function htmlContent($str) {
    return str_replace(array('<', '>'), array('&lt;', '&gt;'), htmlspecialchars($str, ENT_NOQUOTES, 'UTF-8'));
}

function removeDir($path) {
    if(is_dir($path)) {
        $files = glob($path . '*', GLOB_MARK);
        foreach($files as $file) {
            removeDir($file);
        }

        rmdir($path);
    } elseif(is_file($path)) {
        unlink($path);
    }
}

function pagination($total, $page, $shown, $url) {
    $pages = ceil($total / $shown);
    $range_start = ($page - '2') <= '0' || ($pages - '4') <= '0' ? '1' : (($page + '2') >= $pages ? $pages - '4' : $page - '2');
    $range_end = $pages <= '5' ? $pages : (($page + '2') >= $pages ? $pages : $range_start + '4');    

    if ($page > '1') {
        $r[] = '<li><a href="' . url($url, ($page / $page)) . '">&laquo;</a></li>';
        $r[] = '<li><a href="' . url($url, ($page - '1')) . '">&lsaquo;</a></li>';
    } else {
        $r[] = '<li class="disabled"><a href="javascript:;">&laquo;</a></li>';
        $r[] = '<li class="disabled"><a href="javascript:;">&lsaquo;</a></li>';
    }

    if ($range_end > '1') {
        foreach(range($range_start, $range_end) as $key => $value) {
            if ($value == $page) {
                $r[] = '<li class="active"><a href="javascript:;">' . $value . '</a></li>';
            } else {
                $r[] = '<li><a href="' . url($url, $value)  . '">' . $value . '</a></li>';
            }
        }
    } else {
        $r[] = '<li class="disabled"><a href="javascript:;">' . $page . '</a></li>';
    }

    if ($page < $pages) {
        $r[] = '<li><a href="' . url($url, ($page + '1')) . '">&rsaquo;</a></li>';
        $r[] = '<li><a href="' . url($url, $pages) . '">&raquo;</a></li>';
    } else {
        $r[] = '<li class="disabled"><a href="javascript:;">&rsaquo;</a></li>';
        $r[] = '<li class="disabled"><a href="javascript:;">&raquo;</a></li>';
    }

    return ((isset($r)) ? implode("\r\n", $r) : '');
}

function spin($content) {
    preg_match('#\{(.+?)\}#is', $content, $match);
    if (empty($match)) {
        return $content;
    }

    $text = $match['1'];
    if (strpos($text,'{') !== false) {
        $text = substr($text, strrpos($text,'{') + '1');
    }

    $parts = explode("|", $text);
    $content = preg_replace('+\{' . preg_quote($text) . '\}+is', $parts[array_rand($parts)], $content, '1');
    return spin($content);
}

function slug($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^A-Za-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    $str = substr($str, '0', '108');
    return $str;
}

function tag($string) {
    $stopwords = array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount',  'an', 'and', 'another', 'any','anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as',  'at', 'back','be','became', 'because','become','becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own','part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the', 'www', 'wait');
    $stopwords = array_map(function($x){return trim(strtolower($x));}, $stopwords);
    $pattern = '/[0-9\W]/';
    $string = preg_replace($pattern, ',', $string);
    $stringArray = explode(',', $string);
    $stringArray = array_map(function($x){return trim(strtolower($x));}, $stringArray);
    foreach ($stringArray as $term) {
        if (!in_array($term, $stopwords)) {
            $matchWords[] = $term;
        }
    }

    foreach ( $matchWords as $key=>$item ) {
        if ( $item == '' || in_array(strtolower($item), $stopwords) || strlen($item) <= 3 ) {
            unset($matchWords[$key]);
        }
    }

    $matchWords = array_slice(array_filter(array_unique($matchWords)), '0', '12');
    $tag = implode(',', $matchWords);
    return $tag;
}

function rssDate($timestamp = null) {
    $timestamp = ($timestamp == null) ? time() : $timestamp;
    return date(DATE_RSS, $timestamp);
}

function randDate($start, $end, $type) {
    $days = round((strtotime($end) - strtotime($start)) / ('60' * '60' * '24'));
    $randDay = mt_rand('0', $days);
    $randTime = mt_rand('1', '86400');
    $randStart = strtotime("$start + $randDay days");
    if ($type == 'wp') {
        $randDate = date('Ymd', $randStart) . 'T' . date('H:i:s', $randTime) . 'Z';
    }

    if ($type == 'bgxml') {
        $randDate = date('Y-m-d', $randStart) . 'T' . date('H:i:s', $randTime);
    }

    if ($type == 'wpxml') {
        $randDate = date("Y-m-d", $randStart) . ' ' . date('H:i:s', $randTime);
    }

    return $randDate;
}

function findLogo($q, $file) {
    $table = new MyCSV($file);
    while ($row = $table->each()) {
        if (stristr($row['merchant'], $q)) {
            $logourl= htmlspecialchars($row['logoUrl']);
        }
    }

    $table->close();
    if ($logourl == '') {
        $logourl = '';
    }
    return $logourl;
}

function compareTable($api, $productId) {
    $prosperentApi = new Prosperent_Api(array(
        'api_key'    => $api,
        'query'      => '',
        'visitor_ip' => $_SERVER['REMOTE_ADDR'],
        'channel_id' => '0',
        'page'       => '1',
        'imageSize'  => '250x250',
        'sortPrice'  => '',
        'limit'      => '100',
        'filterProductId' => array($productId)
        )
    );
    $results = $prosperentApi->fetch();
    $totalResults = $results['totalRecordsFound'];
    $compare_table = '<h3>Price Compareison Table Available In ' . $totalResults . ' Store</h3>';
    $compare_table .= '<table style="border:2px dashed  #ebebeb">';
    $compare_table .= '<tbody>';
    $compare_table .= '<tr align="center" style="background-color:#eef9fd">';
    $compare_table .= '<td><b>Image</b></td>';
    $compare_table .= '<td><b>Title</b></td>';
    $compare_table .= '<td><b>Price</b></td>';
    $compare_table .= '<td><b>Store </b></td>';
    $compare_table .= '<td><b>Action</b> </td></tr>';
    $results = $prosperentApi->getData();
    foreach ($results as $record) { 
        $logoURL = merchantLogo($record['merchant'], 'merchant');
        $compare_table .= '<tr>';
        $compare_table .= '<td style="border-bottom:1px dashed  #ebebeb"><a rel="nofollow" href="' . $record['affiliate_url'] . '"><img src="' . $record['image_url'] . '" width="100" height="80"> </a></td>';
        $compare_table .= '<td style="border-bottom:1px dashed  #ebebeb">' . $record['keyword'];
        $compare_table .= '<br>Brand:<b style="color:green">' . $record['brand'] . '</b></td>';
        $compare_table .= '<td style="border-bottom:1px dashed  #ebebeb"><b style="color:red">' . $record['price'] . '</b></td>';
        $compare_table .= '<td style="border-bottom:1px dashed  #ebebeb"><img src="' . $logoURL . '" title="' . $record['merchant'] . '"></td>';
        $compare_table .= '<td style="border-bottom:1px dashed  #ebebeb"><a rel="nofollow" href="' . $record['affiliate_url'] . '" target="_blank"><img src="http://2.bp.blogspot.com/-2LzsE0Fvnjk/Ue_YxMHxgZI/AAAAAAAAABg/9KDeNlJnMTU/s1600/BuyNow08.jpg" width="100"  title="Buy From ' . $record['merchant'] . '" ></a></td>';
        $compare_table .= '</tr>';
        flush();
    }

    $compare_table .= '</tbody></table>';
    $compare_table .= '</form>' . "\n";
    return $compare_table;
}

function connectProsperent($api, $keywords, $serpLimit, $brand, $merchant, $category, $maxPrice, $minPrice, $sid, $cacheDir) {
    require_once 'lib.prosperent-api.php';

    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    $prosperentApi = new Prosperent_Api(array(
        'api_key' => $api,
        'query' => $keywords,
        'limit' => $serpLimit,
        'filterBrand' => $brand,
        'filterMerchant' => $merchant,
        'filterCategory' => $category,
        'maxPrice' => $maxPrice,
        'minPrice' => $minPrice,
        'sid' => $sid,
        'cacheBackend' => 'File',
        'cacheOptions' => array(
            'cache_dir' => $cacheDir
            )
    ));
    $results = $prosperentApi->fetch();
    $getData['data'] = $prosperentApi->getData();
    $getData['error'] = $prosperentApi->getErrors();
    return $getData;
}

function wpPostXMLRPC($title, $contents, $wp_url, $username, $password, $category, $wpID, $tag, $postMeta, $postDate, $postOption, $encoding = 'UTF-8') {
    global $mysqli;

    $title = htmlentities($title,ENT_NOQUOTES,$encoding);
    $tag = htmlentities($tag,ENT_NOQUOTES,$encoding);
    $categoryName = trim($category);
    $categorySlug = strtolower(str_replace(array('\'', '.', '(', ')', '&', '--', '_', '/'), '-', $categoryName));
    $client = new IXR_Client($wp_url . '/xmlrpc.php');
    $date = new IXR_Date($postDate);
    $params = array('0', $username, $password, $content, 'true');
    $res = $client->query('wp.newCategory', '', $username, $password, array('name' => $categoryName, 'slug' => $categorySlug, 'parent_id' => '0', 'description' => ''));
    $content = array(
        'title' => $title,
        'description' => stripslashes($contents),
        'mt_allow_comments' => '0', // 1 to allow comments
        'mt_allow_pings' => '1', // 1 to allow trackbacks
        'post_type' => 'post',
        'mt_keywords' => $tag,
        'categories' => array($categoryName),
        'custom_fields' => $postMeta,
        'dateCreated' => $date
    );
    if ($postOption == 'nomal-post') {
        if(!$client->query('metaWeblog.newPost', $params)) {
            $now = time();
            $getError = 'Error Post - ' . $client->getErrorCode() . ' : ' . $client->getErrorMessage();
            $queryError = "INSERT INTO error_log (ID, type, blogId, blog_url, reason, datetime) VALUES (NULL, 'wordpress', '" . $wpID . "', '" . $wp_url .  "', '" . $getError . "', '" . $now . "')";
            $resultError = $mysqli->query($queryError) OR trigger_error($mysqli->error . "[$queryError]");

            return $getError;
        }
    }

    if ($postOption == 'quick-post') {
        if(!$client->query('metaWeblog.newPost', $params)) {
            $getError = 'Error Post - ' . $client->getErrorCode() . ' : ' . $client->getErrorMessage();
            return $getError;
        }
    }

    return $client->getResponse();
}

function bgPostXMLRPC($email, $password, $blogID, $title, $content, $tag) {
    if (!defined('ldZend')) {
        require_once 'Zend/Loader.php';
        Zend_Loader::loadClass('Zend_Gdata');
        Zend_Loader::loadClass('Zend_Gdata_Query');
        Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
        define('ldZend', 1);
    }

    $client = Zend_Gdata_ClientLogin::getHttpClient($email, $password, 'blogger', null, Zend_Gdata_ClientLogin::DEFAULT_SOURCE, null, null, Zend_Gdata_ClientLogin::CLIENTLOGIN_URI, 'GOOGLE');
    $blogger = new Zend_Gdata($client);
    $entry = $blogger->newEntry();
    $entry->title = $blogger->newTitle($title);
    $entry->content = $blogger->newContent($content);
    $entry->content->setType('text');
    $tag = explode(',', $tag);
    foreach ($tag as $tags) {
        $count = '1';
        $count++;
        $label[] = new Zend_Gdata_App_Extension_Category($tags, 'http://www.blogger.com/atom/ns#');
        if ($count == '15') {
            break;
        }
    }

    $entry->setCategory($label);
    $createdPost = $blogger->insertEntry($entry, 'http://www.blogger.com/feeds/' . $blogID . '/posts/default');
    $idText = explode('-', $createdPost->id->text);
    return array($idText['2'], $createdPost->link['4']->href);
}

function cleanInput($string) {
    $patterns['0'] = "/'/";
    $patterns['1'] = "/\"/";
    $string = preg_replace($patterns,'',$string);
    $string = trim($string);
    $string = stripslashes($string);
    return preg_replace('/[<>]/', '_', $string);
}

function runTask($id, $script, $name, $type, $blogid, $buffer_output = '1') {
    if ($buffer_output) {
        ob_start();
    }

    $scriptRunning = new scriptStatus;
    $scriptRunning->script = $script;
    $scriptRunning->name = $name;
    $scriptRunning->type = $type;
    $scriptRunning->blogid = $blogid;

    if ($scriptRunning->Running($id)) {
        $start_time = microtime(true);
        if (strstr($script, '://')) {
            remoteScript($script);
        } else {
            include(dirname(__FILE__) . '/' . $script);
        }

        if ($buffer_output) {
            $scriptRunning->output = ob_get_contents();
        } else {
            $scriptRunning->output = '';
        }

        $scriptRunning->execution_time = number_format((microtime(true) - $start_time), '5');
        $scriptRunning->Stopped($id);
    }

    if ($buffer_output) {
        ob_end_clean();
    }
}

class scriptStatus {
    public $script;
    public $output;
    public $name;
    public $type;
    public $blogid;
    public $execution_time;

    public function Running($id) {
        global $mysqli;

        $queryRunning = "UPDATE scheduler SET running = 'ON' WHERE ID = '" . $id . "'";
        $resultRunning = $mysqli->query($queryRunning) OR trigger_error($mysqli->error . "[$queryRunning]");
        register_shutdown_function('clear', $id);
        return $resultRunning;
    }

    public function Stopped($id) {
        global $mysqli;

        $queryStopped = "UPDATE scheduler SET running = 'OFF' WHERE ID = '" . $id . "'";
        $resultStopped = $mysqli->query($queryStopped) OR trigger_error($mysqli->error . "[$queryStopped]");
        if (TRUE) {
            $now = time();
            $this->output = substr(htmlentities($this->output), '0', '1200');
            $queryLogs = "INSERT INTO error_log (ID, blog_url, blogId, type, reason, datetime) VALUES (NULL, '" . $this->name ."', '" . $this->blogid ."', '" . $this->type . "', '" . $this->output . "', '" . $now . "')";
            $resultLogs = $mysqli->query($queryLogs) OR trigger_error($mysqli->error . '[$queryLogs]');
        }
    }
}

function remoteScript($url) {
    if (function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, '1');
        curl_setopt($ch, CURLOPT_HEADER, '0');
        curl_setopt($ch, CURLOPT_USERAGENT, 'cronJob');
        $buffer = curl_exec($ch);
        curl_close($ch);
    }

    elseif ( $fp = @fsockopen($host, $port, $errno, $errstr, '30') ) {
        $header = "POST $path HTTP/1.0\r\nHost: $host\r\nReferer: $referer\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "User-Agent: $useragent\r\n";
        $header .= "Content-Length: " . strlen($query) . "\r\n";
        if ($user!= '') {
            $header.= "Authorization: Basic " . base64_encode("$user:$pass") . "\r\n";
        }

        $header .= "Connection: close\r\n\r\n";
        fputs($fp, $header);
        fputs($fp, $query);
        if ($fp) {
            while (!feof($fp)) {
                $buffer.= fgets($fp, 8192);
            }
        }

        @fclose($fp);
    }

    echo $buffer;
}

function clear($id) {
    global $mysqli;

    $query = "UPDATE scheduler SET running = 'OFF' WHERE ID = '" . $id . "'";
    $result = $mysqli->query($query) OR trigger_error($mysqli->error . '[$query]');
}

?>