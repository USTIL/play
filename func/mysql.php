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
    public function addPlay($title, $cont) {
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
            $time = date("Y-m-d H:i:s");
            $sql = "insert into plays(title,cont,time) values('{$title}','{$cont}','{$time}')";
            $this->query($sql);
            return 'success';
        }
    }
    /**
     * 更新比赛信息
     */
    public function editPlay($title, $cont, $id) {
        if ($title == "") {
            return 'titlenull';
        }
        if ($cont == "") {
            return 'contnull';
        }
        $sql = "update plays set title = '{$title}', cont = '{$cont}' where id = {$id}";
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
     * 得到全部比赛信息
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
    public function addAward($up_id, $user_id) {
        $sql = "select * from awards where up_id = {$up_id}";
        $rst = $this->query($sql);
        $place = mysqli_num_rows($rst) + 1;
        $rows = array();
        while ($row = mysqli_fetch_assoc($rst)) {
            $rows[] = $row['user_id'];
        }
        if (in_array($user_id, $rows)) {
            return 'error';
        } else {
            $sql = "insert into awards(up_id,user_id,place) values({$up_id},{$user_id},{$place})";
            $this->query($sql);
            return 'success';
        }
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
     * 创建URL表
     */
    public function createURLDB() {
        $sql = "SHOW TABLES";
        $rst = $this->query($sql);
        $tables = $this->fetchAll($rst);
        if (!in_array('url', $tables)) {
            $sql = "CREATE TABLE `url` (
                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `url` varchar(255) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
            $this->query($sql);
            $sql = "INSERT INTO `url` VALUES ('1', 'index');";
            $this->query($sql);
        }
    }
    
}