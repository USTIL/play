<?php
if (is_file('../playconf.php')) {
    session_start();
    $_SESSION['url'] = array(
        '0' => 'index',
        '1' => 'index'
    );
    header('location: ../');
} else {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == "") {
        if (isset($_COOKIE['user_id'])) {
            $_SESSION['user_id'] = $_COOKIE['user_id'];
            $_SESSION['user_name'] = $_COOKIE['user_name'];
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>比赛</title>
</head>
<script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="./js/layui/layui.js"></script>
<link rel="stylesheet" type="text/css" href="./js/layui/css/layui.css" />
<body>
<a>欢迎您：<?php 
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_id'] != "") {
        echo $_SESSION['user_name'];
    } else {
        echo "游客";
    }
} else {
    echo "游客";
}
    ?></a>&nbsp&nbsp
<?php 
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != "") {
?>
<a href="action.php?action=userexit">退出</a>
<?php
} else {
?>
<a>注册</a>&nbsp&nbsp<a>登陆</a>
<h3>注册</h3>
<form action="action.php?action=adduser" method="post">
  <p>用户名: <input type="text" name="username" /></p>
  <p>密码: <input type="password" name="password" /></p>
  <p>姓名: <input type="text" name="name" /></p>
  <input type="submit" value="注册" />
</form>
<h3>登录</h3>
<form action="action.php?action=userlogin" method="post">
  <p>用户名: <input type="text" name="username" /></p>
  <p>密码: <input type="password" name="password" /></p>
  <input type="submit" value="登录" />
</form>
<?php
}
?>
<table>
	<thead>
		<th>序号</th>
		<th>标题</th>
		<th>简介</th>
		<th>时间</th>
	</thead>
	<tbody>
	<?php 
	$sql = "select * from plays";
	$rst = $mysql->query($sql);
	$rows = $mysql->fetchAll($rst);
	$i = 0;
	foreach ($rows as $row) {
	?>
		<tr onClick="award(<?php echo $row['id']?>)">
			<td><?php $i++; echo $i;?></td>
			<td><?php echo $row['title'];?></td>
			<td><?php echo $row['cont'];?></td>
			<td><?php echo $row['time'];?></td>
		</tr>
	<?php }?>
	</tbody>
</table>
</body>
<script type="text/javascript">
	function award(id) {
    	$.post('action.php?action=showaward', {
    		id: id,
    	}, function(data) {
			if (data == 'success') {
				location.reload();
			}
    	});
	}
</script>
</html>