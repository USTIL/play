<?php
session_start();
include './func/mysql.php';
$mysql = new mysql();
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == 'adduser') { //添加用户
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $return = $mysql->addUser($username, $password, $name);
        if ($return == 'success') {
            $sql = "select * from user where username = '{$username}'";
            $rst = $mysql->query($sql);
            $row = $mysql->fetch($rst);
            setcookie('user_id', $row['id'], time()+36000);
            setcookie('user_name', $name, time()+36000);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $name;
        }
        echo $return;
    } else if ($action == 'edituser') { //更新用户信息
        $id = $_POST['id'];
        if (isset($_POST['username'])) {
            $username = $_POST['username'];
        } else {
            $sql = "select * from user where id = {$id}";
            $rst = $mysql->query($sql);
            $tem = $mysql->fetch($rst);
            $username = $tem['username'];
        }
        $password = $_POST['password'];
        $name = $_POST['name'];
        
        $return = $mysql->editUser($username, $password, $name, $id);
        if ($return == 'success') {
            setcookie('user_id','',time()-1);
            setcookie('user_name','',time()-1);
            $_SESSION['user_name'] = $name;
        }
        echo $return;
    } else if ($action == 'getuser') { //获取用户信息
        $id = $_POST['id'];
        $row = $mysql->getUser($id);
        echo json_encode($row);
    } else if ($action == 'addplay') { //添加一个比赛
        $title = $_POST['title'];
        $cont = $_POST['cont'];
        $gdate = $_POST['date'];
        if ($gdate == "") {
            echo "datenull";
        } else {
            $date = strtotime($gdate);
            $return = $mysql->addPlay($title, $cont, $date);
            echo $return;
        }
    } else if ($action == 'editplay') { //更新比赛信息
        $id = $_POST['id'];
        $title = $_POST['title'];
        $cont = $_POST['cont'];
        $gdate = $_POST['date'];
        if ($gdate == "") {
            echo "datenull";
        } else {
            $date = strtotime($gdate);
            $return = $mysql->editPlay($title, $cont, $date, $id);
            echo $return;
        }
    } else if ($action == 'getplay') { //获取比赛信息
        $id = $_POST['id'];
        $row = $mysql->getPlay($id);
        echo json_encode($row);
    } else if ($action == 'deleteplay') { //删除一个比赛
        $id = $_POST['id'];
        $return = $mysql->deletePlay($id);
        echo $return;
    } else if ($action == 'addaward') { //添加获奖
        $up_id = $_POST['up_id'];
        $user_id = $_POST['user_id'];
        $member = $_POST['member'];
        $place = $_POST['place'];
        $return = $mysql->addAward($up_id, $user_id, $place, $member);
        echo $return;
    } else if ($action == 'deleteaward') { //删除一个获奖
        $id = $_POST['id'];
        $return = $mysql->deleteAward($id);
        echo $return;
    } else if ($action == 'userexit') { //用户退出
        setcookie('user_id', '', time()-1);
        setcookie('user_name', '', time()-1);
        $_SESSION['user_id'] = "";
        $_SESSION['user_name'] = "";
        echo "success";
    } else if ($action == 'userlogin') { //用户登录
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username != "" && $password != "") {
            $pass = md5($password);
            $sql = "select * from user where username = '{$username}'";
            $rst = $mysql->query($sql);
            if ($row = $mysql->fetch($rst)) {
                if ($row['password'] == $pass) {
                    setcookie('user_id', $row['id'], time()+36000);
                    setcookie('user_name', $row['name'], time()+36000);
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['name'];
                    echo "success";
                } else {
                    echo "passerror";
                }
            } else {
                echo "usererror";
            }
        } else {
            echo "error";
        }
    } else if ($action == 'adminlogin') { //后台管理员登录
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username != "" && $password != "") {
            $pass = md5($password);
            $sql = "select * from admin where username = '{$username}'";
            $rst = $mysql->query($sql);
            if ($row = $mysql->fetch($rst)) {
                if ($row['password'] == $pass) {
                    $_SESSION['admin_id'] = $row['id'];
                    $_SESSION['admin_name'] = $row['name'];
                    $_SESSION['url'] = array(
                        '0' => 'admin',
                        '1' => 'index'
                    );
                    echo "success";
                } else {
                    echo "passerror";
                }
            } else {
                echo "usererror";
            }
        } else {
            echo "error";
        }
    } else if ($action == 'adminexit') { //管理员退出
        $_SESSION['admin_id'] = "";
        $_SESSION['admin_name'] = "";
        echo "success";
    } else if ($action == 'showaward') { //显示获奖页
        $id = $_POST['id'];
        $_SESSION['up_id'] = $id;
        $_SESSION['url'] = array(
            '0' => 'index',
            '1' => 'award'
        );
        echo "success";
    } else if ($action == 'addadmin') { //添加管理员
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $return = $mysql->addAdmin($username, $password, $name);
        echo $return;
    } else if ($action == 'editpass') { //修改管理员密码
        $id = $_POST['id'];
        $password = $_POST['password'];
        $return = $mysql->editpass($password, $id);
        if ($return == 'success') {
            $_SESSION['admin_id'] = "";
            $_SESSION['admin_name'] = "";
        }
        echo $return;
    } else if ($action == 'deleteuser') { //删除一个用户
        $id = $_POST['id'];
        $return = $mysql->deleteUser($id);
        echo $return;
    } else if ($action == 'deleteadmin') { //删除一个管理员
        $id = $_POST['id'];
        if ($_SESSION['admin_id'] == $id) {
            echo "isme";
        } else {
            $sql = "select * from admin";
            $rst = $mysql->query($sql);
            $num = mysqli_num_rows($rst);
            if ($num == 1) {
                echo "onlyone";
            } else {
                $return = $mysql->deleteAdmin($id);
                echo $return;
            }
        }
    } else if ($action == 'sh') { //通过审核
        $id = $_POST['id'];
        $place = $_POST['place'];
        $return = $mysql->sh($place, $id);
        echo $return;
    } else if ($action == 'adminaward') { //后台显示获奖信息
        $_SESSION['adminplayid'] = $_POST['id'];
        $_SESSION['url'] = array(
            '0' => 'admin',
            '1' => 'playaward'
        );
        echo "success";
    } else if ($action == 'uadminaward') { //后台显示获奖信息
        $_SESSION['adminuserid'] = $_POST['id'];
        $_SESSION['url'] = array(
            '0' => 'admin',
            '1' => 'uplayaward'
        );
        echo "success";
    }
    
}














