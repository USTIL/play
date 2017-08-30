<?php
include 'conf.php';
class mysql {
    /**
     * 连接数据库
     */
    private function connect() {
        $conf = new conf();
        $con = $conf->getAllConf('mysql');
        $link = mysqli_connect($con['HOST'],$con['USERNAME'],$con['PASSWORD'],$con['DBNAME']);
        if ($link) {
            return $link;
        } else {
            echo "数据库连接失败，请检查配置文件中的配置是否正确";
            exit();
        }
    }
    /**
     * 执行SQL语句
     */
   public function query($sql) {
        $link = $this->connect();
        $rst = mysqli_query($link, $sql);
        if ($rst) {
            return $rst;
        } else {
            echo "SQL语句执行错误，请检查SQL语句";
        }
    }
    /**
     * 查找一条记录
     */
    public function fetch($rst) {
        $row = mysqli_fetch_assoc($rst);
        return $row;
    }
    /**
     * 查询所有记录
     */
    public function fetchAll($rst) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($rst)) {
            $rows[] = $row;
        }
        return $rows;
    }
    /**
     * 添加用户
     */
    public function addUser($username, $password, $name) {
        if ($name == "") {
            return 'namenull';
        }
        if ($username == "") {
            return 'usernull';
        }
        if ($password == "") {
            return 'passnull';
        }
        $sql = "select * from user where username = '{$username}'";
        $rst = $this->query($sql);
        if ($row = $this->fetch($rst)) {
            return 'userhave';
        } else {
            $pass = md5($password);
            $sql = "insert into user(username,password,name) values('{$username}','{$pass}','{$name}')";
            $this->query($sql);
            return 'success';
        }
    }
    /**
     * 更新用户信息
     */
    public function editUser($username, $password, $name, $id) {
        if ($name == "") {
            return 'namenull';
        }
        if ($username == "") {
            return 'usernull';
        }
        if ($password == "") {
            $sql = "select * from user where id = {$id}";
            $rst = $this->query($sql);
            $row = $this->fetch($rst);
            $pass = $row['password'];
        } else {
            $pass = md5($password);
        }
        $sql = "update user set username = '{$username}', password = '{$pass}', name = '{$name}' where id = {$id}";
        $this->query($sql);
        return 'success';
    }
    /**
     * 得到用户信息
     */
    public function getUser($id) {
        $sql = "select * from user where id = {$id}";
        $rst = $this->query($sql);
        $row = $this->fetch($rst);
        return $row;
    }
    /**
     * 添加一个比赛
     */
    public function addPlay($title, $cont, $date) {
        if ($title == "") {
            return 'titlenull';
        }
        if ($cont == "") {
            return 'contnull';
        }
        $sql = "select * from plays where title = '{$title}'";
        $rst = $this->query($sql);
        if ($row = $this->fetch($rst)) {
            return 'playhave';
        } else {
            $sql = "insert into plays(title,cont,date) values('{$title}','{$cont}','{$date}')";
            $this->query($sql);
            return 'success';
        }
    }
    /**
     * 更新比赛信息
     */
    public function editPlay($title, $cont, $date, $id) {
        if ($title == "") {
            return 'titlenull';
        }
        if ($cont == "") {
            return 'contnull';
        }
        $sql = "update plays set title = '{$title}', cont = '{$cont}', date = '{$date}' where id = {$id}";
        $this->query($sql);
        return 'success';
    }
    /**
     * 得到比赛信息
     */
    public function getPlay($id) {
        $sql = "select * from plays where id = {$id}";
        $rst = $this->query($sql);
        $row = $this->fetch($rst);
        return $row;
    }
    /**
     * 得到全部比赛
     */
    public function getAllPlay() {
        $sql = "select * from plays";
        $rst = $this->query($sql);
        $rows = $this->fetchAll($rst);
        return $rows;
    }
    /**
     * 删除一个比赛
     */
    public function deletePlay($id) {
        $sql = "delete from plays where id = {$id}";
        $this->query($sql);
        $sql = "delete from awards where up_id = {$id}";
        $this->query($sql);
        return 'success';
    }
    /**
     * 添加获奖
     */
    public function addAward($up_id, $user_id, $place, $member) {
        $sql = "select * from awards where up_id = {$up_id}";
        $rst = $this->query($sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($rst)) {
            $rows[] = $row['user_id'];
        }
        if (in_array($user_id, $rows)) {
            return 'error';
        } else if ($place == "") {
            return 'placenull';
        } else if ($member == "") {
            return 'membernull';
        } else {
            $sql = "insert into awards(up_id,user_id,place) values({$up_id},{$user_id},{$place})";
            $this->query($sql);
            return 'success';
        }
    }
    /**
     * 通过审核
     */
    public function sh($place, $id) {
        if ($place == "") {
            return "placenull";
        }
        $sql = "update awards set place = {$place}, sh = 1 where id = {$id}";
        $this->query($sql);
        echo "success";
    }
    /**
     * 删除一个获奖
     */
    public function deleteAward($id) {
        $sql = "delete from awards where id = {$id}";
        $this->query($sql);
        return 'success';
    }
    /**
     * 添加管理员
     */
    public function addAdmin($username, $password, $name) {
        if ($name == "") {
            return 'namenull';
        }
        if ($username == "") {
            return 'usernull';
        }
        if ($password == "") {
            return 'passnull';
        }
        $sql = "select * from admin where username = '{$username}'";
        $rst = $this->query($sql);
        if ($row = $this->fetch($rst)) {
            return 'adminhave';
        } else {
            $pass = md5($password);
            $sql = "insert into admin(username,password,name) values('{$username}','{$pass}','{$name}')";
            $this->query($sql);
            return 'success';
        }
    }
    /**
     * 修改管理员密码
     */
    public function editpass($password, $id) {
        if ($password == "") {
            return "passnull";
        }
        $pass = md5($password);
        $sql = "update admin set password = '{$pass}' where id = {$id}";
        $this->query($sql);
        return 'success';
    }
    /**
     * 删除一个管理员
     */
    public function deleteAdmin($id) {
        $sql = "delete from admin where id = {$id}";
        $this->query($sql);
        return 'success';
    }
    /**
     * 删除一个用户
     */
    public function deleteUser($id) {
        $sql = "delete from user where id = {$id}";
        $this->query($sql);
        $sql = "delete from awards where user_id = {$id}";
        $this->query($sql);
        return 'success';
    }
    /**
     * 创建表
     */
    public function createTable() {
            $sql = "CREATE TABLE `admin` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `username` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `name` text NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
            $this->query($sql);
            $sql = "INSERT INTO `admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin');";
            $this->query($sql);
            $sql = "CREATE TABLE `awards` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `up_id` int(10) unsigned NOT NULL,
                  `user_id` int(10) unsigned NOT NULL,
                  `place` int(10) unsigned NOT NULL,
                  `sh` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            $this->query($sql);
            $sql = "CREATE TABLE `plays` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `title` text NOT NULL,
                  `cont` text NOT NULL,
                  `time` text NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            $this->query($sql);
            $sql = "CREATE TABLE `user` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `username` varchar(255) NOT NULL,
                  `password` varchar(255) NOT NULL,
                  `name` text NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            $this->query($sql);
    }
    
}
