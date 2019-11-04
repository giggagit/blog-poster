<?php
//include 'functions.php';
//include 'setting.php';
include 'lib/lib.xml-rpc.php';
//date_default_timezone_set('Asia/Bangkok');

 ?>
<html>
<head>
        <meta charset="utf-8" />
        <title>test</title>
        <!-- start: META -->
        <meta name="description" content="description" />
        <meta name="keywords" content="keywords" />
        <meta name="author" content="author" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- end: META -->
        <!-- start: MAIN CSS -->
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- end: MAIN CSS -->
        <!-- start: PAGE LEVEL CSS -->
        <link href="assets/plugins/summernote/css/summernote.css" rel="stylesheet" />
        <!-- end: PAGE LEVEL CSS -->
        <link rel="shortcut icon" href="favicon.ico" />
</head>
 
<body class="container">

   <form action="" method="POST">
        <input type="text" name="option[name1]" /><br />
        <input type="text" name="option[value1]" /><br />
        <input type="text" name="option[name2]" /><br />
        <input type="text" name="option[value2]" /><br />
        <button type="submit">submit</button>
   </form>

<pre>
<?php

for($i = '0'; $i <= '6'; $i++) {
    if ($i < '1') {
        echo $i;
    }

    if ($i = '1') {
        echo $i;
    }

    if ($i > '1') {
        echo $i;
    }
}

?><button id="open">open</button>
</pre>
<script type="text/javascript">
document.getElementById('open').onclick = function() {
    window.open('http://google.com','popup');
};
</script>
</body>
</html>