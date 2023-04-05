<?php
include '../../connection.inc.php';
if (isset($_POST['data']) && isset($_SESSION['ADMIN_LOGIN']) && isset($_SESSION['ADMIN_USERNAME'])) {
    /**
     * Generate a random string, using a cryptographically secure 
     * pseudorandom number generator (random_int)
     * 
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     * 
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }


    function query($con, $title, $short_url, $data)
    {
        $sql = "INSERT INTO `videos` (`username`,`title`,`short_url`, `video_data`, `status`,`time`) VALUES ('{$_SESSION['ADMIN_USERNAME']}','$title','$short_url', '$data','1', current_timestamp())";
        $result = mysqli_query($con, $sql);
        if ($result) {
            return $short_url;
        }
    }
    function return_result($data){
        
        echo '<iframe src="'.SITE_PATH.'watch/'.$data.'"frameborder="0" width="720" height="400"></iframe>';
    }
    $data = trim($_POST['data']);
    $title = trim($_POST['title']);
    $type = trim($_POST['type']);
    $data = mysqli_real_escape_string($con, $data);
    $title = mysqli_real_escape_string($con, $title);
    $type = mysqli_real_escape_string($con, $type);
    if ($type == "create") {
        $short_url = random_str(11);
        $sql = "SELECT * FROM `videos` WHERE `short_url` = '$short_url' AND `username` = '{$_SESSION['ADMIN_USERNAME']}'";
        $result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) < 1) {
            $data = query($con, $title, $short_url, $data);
            return_result($data);;
        } else {
            $short_url = random_str(11);
            $data = query($con, $title, $short_url, $data);
            return_result($data);
        }
    } elseif ($type == "edit") {
        $short_url = trim($_POST['v']);
        $short_url = mysqli_real_escape_string($con, $short_url);
        $sql = "UPDATE `videos` SET `title` = '$title' , `video_data` = '$data' WHERE `short_url` = '$short_url' AND `username` = '{$_SESSION['ADMIN_USERNAME']}'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            return_result($short_url);
        }
    }
    
}
