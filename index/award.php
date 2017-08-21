<?php
if (is_file('../playconf.php')) {
    session_start();
    $_SESSION['url'] = array(
        '0' => 'index',
        '1' => 'award'
    );
    header('location: ../');
}
$up_id = $_SESSION['up_id'];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>比赛 - 获奖详情</title>
</head>
<script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="./js/layui/layui.js"></script>
<link rel="stylesheet" type="text/css" href="./js/layui/css/layui.css" />
<body>
<?php 
$sql = "select * from plays where id = {$up_id}";
$rst = $mysql->query($sql);
$row = $mysql->fetch($rst);
$sql = "select * from awards where up_id = {$up_id} order by place asc";
$rst = $mysql->query($sql);
$rows = $mysql->fetchAll($rst);
?>
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
<a href="action.php?action=userexit">退出</a>&nbsp&nbsp<a href="javascript:addaward(<?php echo $up_id;?>, <?php echo $_SESSION['user_id'];?>)">添加此比赛获奖</a>
<?php
} else {
?>
<a>注册</a>&nbsp&nbsp<a>登陆</a>
<?php
}
?>
&nbsp&nbsp<a href="javascript:printexcel(<?php echo $up_id;?>)">下载表格</a>
<h3>标题：<?php echo $row['title']?></h3>
<p>简介：<?php echo $row['cont']?></p>
<p>时间：<?php echo $row['time']?></p>
<table>
	<thead>
	<?php 
	
	?>
		<th>名次</th>
		<th>姓名</th>
		<th>奖次</th>
	</thead>
	<tbody>
	<?php 
	$sum = mysqli_num_rows($rst);
	foreach ($rows as $row) {
	    $sql = "select * from user where id = {$row['user_id']}";
	    $rst = $mysql->query($sql);
	    $user = $mysql->fetch($rst);
	?>
		<tr>
			<td><?php echo $row['place'];?></td>
			<td><?php echo $user['name'];?></td>
			<td><?php 
			if ($sum < 10) {
			    if ($sum <= 6) {
			        if ($sum == 1) {
			            echo "一等奖";
			        } else if ($sum == 2) {
			            if ($row['place'] == 1) {
			                echo "一等奖";
			            } else if ($row['place'] == 2) {
			                echo "二等奖";
			            }
			        } else if ($sum == 3) {
			            if ($row['place'] == 1) {
			                echo "一等奖";
			            } else if ($row['place'] == 2) {
			                echo "二等奖";
			            } else if ($row['place'] == 3) {
			                echo "三等奖";
			            }
			        } else {
			            if ($row['place'] == 1) {
			                echo "一等奖";
			            } else if ($row['place'] <= 3) {
			                echo "二等奖";
			            } else if ($row['place'] <= 6) {
			                echo "三等奖";
			            }
			        }
			    } else {
			        if ($row['place'] == 1) {
			            echo "一等奖";
			        } else if ($row['place'] <= 3) {
			            echo "二等奖";
			        } else if ($row['place'] <= 6) {
			            echo "三等奖";
			        } else {
			            echo "没有奖";
			        }
			    }
			} else {
    			if ($row['place'] <= $sum*0.1) {
    			    echo "一等奖";
    			} else if ($row['place'] <= $sum*0.3) {
    			    echo "二等奖";
    			} else if ($row['place'] <= $sum*0.6) {
    			    echo "三等奖";
    			} else {
    			    echo "没有奖";
    			}
			}
			?></td>
		</tr>
	<?php }?>
	</tbody>
</table>
</body>
<script type="text/javascript">
	function addaward(up_id,user_id) {
		$.post('action.php?action=addaward', {
    		up_id: up_id,
    		user_id: user_id,
    	}, function(data) {
			if (data == 'success') {
				location.reload();
			} else {
				alert("您已经添加过该奖项");
			}
    	});
	}
	function printexcel(up_id) {
		$.post('action.php?action=printexcel', {
    		up_id: up_id,
    	});
	}
</script>
</html>