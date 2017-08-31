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
$sql = "select * from plays order by id";
$rst = $mysql->query($sql);
$rows = $mysql->fetchAll($rst);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>获奖展示系统</title>
</head>
<script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="./js/layui/layui.js"></script>
<link rel="stylesheet" type="text/css" href="./js/layui/css/layui.css" />
<body>
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo">获奖展示系统</div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-right">
      		<?php 
		if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != "") {
		    $sql = "select * from user where id = {$_SESSION['user_id']}";
		    $rst = $mysql->query($sql);
		    $user = $mysql->fetch($rst);
		 ?>
		    <li class="layui-nav-item">
		    <a href="javascript:;">
		         <?php echo $_SESSION['user_name'];?>
		    </a>
		    <dl class="layui-nav-child">
		    <dd><a href="javascript:edituser(<?php echo $_SESSION['user_id'];?>, '<?php echo $user['username'];?>', '<?php echo $_SESSION['user_name'];?>')">个人信息</a></dd>
		    <dd><a href="javascript:userexit()">退出</a></dd>
		    </dl>
		    </li>
	   
		<?php
		} else {
		?>
		  <li class="layui-nav-item"><a href="javascript:reg()">注册</a></li>
		  <li class="layui-nav-item"><a href="javascript:login()">登录</a></li>
		  <?php }?>
		  
    </ul>
  </div>
  
  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree" lay-filter="test">
      <?php $ayear = array();
      foreach ($rows as $row) {
        $udate = $row['date'];
        $tdate = date("Y-n-j", $udate);
        $date = explode("-", $tdate);
        if (!in_array($date['0'], $ayear)) {
            array_push($ayear, $date['0']);
        }
      }
      sort($ayear);
      foreach ($ayear as $nyear) {
      ?>
        <li class="layui-nav-item">
          <a class="" href="javascript:;"><?php echo $nyear;?>年比赛获奖</a>
          <dl class="layui-nav-child">
          <?php
          foreach ($rows as $row) {
            $date = explode("-", date("Y-n-j", $row['date']));
            $year = $date['0'];
            if ($year == $nyear) {
                echo '<dd><a href="javascript:show('.$row['id'].')">'.$row['title'].'</a></dd>';
            }
          }
          ?>
          </dl>
        </li>
      <?php }?>
      </ul>
    </div>
  </div>
  
  <div class="layui-body">
    <!-- 内容主体区域 -->
	  <div class="layui-carousel" style="width: 100%; height: 100%;" id="car1">
      <div carousel-item="">
        <div><img style="width: 100%; height: 100%;" src="./img/car/1.jpg"></div>
        <div><img style="width: 100%; height: 100%;" src="./img/car/2.jpg"></div>
        <div><img style="width: 100%; height: 100%;" src="./img/car/3.jpg"></div>
        <div><img style="width: 100%; height: 100%;" src="./img/car/4.jpg"></div>
        <div><img style="width: 100%; height: 100%;" src="./img/car/5.jpg"></div>
      </div>
    </div>
  </div>
  
  <div class="layui-footer">
    <!-- 底部固定区域 -->
    © 2017 获奖展示系统
  </div>
</div>

<script>
//JavaScript代码区域
layui.use(['element','carousel'], function(){
  var element = layui.element
  ,carousel = layui.carousel;

  var dwidth = $(document).width()-200;
  var dheight = $(document).height()-105;
  
  carousel.render({
		elem: '#car1'
		,width: dwidth
		,height: dheight
		,interval: 2000
	  });
});
</script>
<script type="text/javascript">
		function show(id) {
			$.post('action.php?action=showaward', {
				id: id
			}, function(data) {
				if (data == 'success') {
					location.reload();
				}
			});
		}
		function reg() {
			layer.open({
				type: 1,
				title: '注册',
				area: ['400px', '280px'],
				content: $('#adduser')
			});
		}
		function login() {
			layer.open({
				type: 1,
				title: '登录',
				area: ['400px', '220px'],
				content: $('#login')
			});
		}
		function edituser(id,username,name) {
			$('#idme').val(id);
			$('#nameme').val(name);
			layer.open({
				type: 1,
				title: '个人资料',
				area: ['400px', '220px'],
				content: $('#editme')
			});
		}
		function userexit() {
			$.post('action.php?action=userexit', {
				
				}, function(data) {
					if (data == 'success') {
						location.reload();
					}
				});
		}
</script>
</body>
<div id="editme" style="width: 90%; display: none; margin: 10px auto 0">
	<form class="layui-form layui-form-pane" action="">
		<input id="idme" name="id" style="display: none" type="text" />
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" id="nameme" name="name" placeholder="请输入姓名" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-block">
				<input type="password" id="passwordme" name="password" placeholder="请输入密码（留空即不修改）" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit1">修改</button>
		</div>
	</form>
	<script>
		layui.use(['form', 'layer'], function(){
		  var form = layui.form
		  ,layer = layui.layer;

		  //监听提交
		  form.on('submit(submit1)', function(data){
			  var mdata = JSON.stringify(data.field);
			  var medata = JSON.parse(mdata);
			$.post('action.php?action=edituser', {
				id: medata.id,
				password: medata.password,
				name: medata.name
			}, function(data) {
				if (data == 'success') {
					location.reload();
				} else if (data == 'namenull') {
					layer.msg('姓名不能为空', {icon:2});
				} else if (data == 'usernull') {
					layer.msg('用户名不能为空', {icon:2});
				}
			});
			return false;
		  });
		});
	</script>
</div>
<div id="login" style="width: 90%; display: none; margin: 10px auto 0">
	<form class="layui-form layui-form-pane" action="">
		<div class="layui-form-item">
			<label class="layui-form-label">用户名</label>
			<div class="layui-input-block">
				<input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-block">
				<input type="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit2">登录</button>
		</div>
	</form>
	<script>
		layui.use(['form', 'layer'], function(){
		  var form = layui.form
		  ,layer = layui.layer;

		  //监听提交
		  form.on('submit(submit2)', function(data){
			  var mdata = JSON.stringify(data.field);
			  var medata = JSON.parse(mdata);
			$.post('action.php?action=userlogin', {
				username: medata.username,
				password: medata.password
			}, function(data) {
				if (data == 'success') {
					location.reload();
				} else if (data == 'usererror') {
					layer.msg('该用户不存在', {icon:2});
				} else if (data == 'passerror') {
					layer.msg('密码错误', {icon:2});
				} else if (data == 'error') {
					layer.msg('用户名和密码不能为空', {icon:2});
				}
			});
			return false;
		  });
		});
	</script>
</div>
<div id="adduser" style="width: 90%; display: none; margin: 10px auto 0">
	<form class="layui-form layui-form-pane" action="">
		<div class="layui-form-item">
			<label class="layui-form-label">用户名</label>
			<div class="layui-input-block">
				<input type="text" name="username" placeholder="请输入用户名（请不要使用汉语）" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-block">
				<input type="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" name="name" placeholder="请输入姓名" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit3">注册</button>
		</div>
	</form>
	<script>
		layui.use(['form', 'layer'], function(){
		  var form = layui.form
		  ,layer = layui.layer;

		  //监听提交
		  form.on('submit(submit3)', function(data){
			  var mdata = JSON.stringify(data.field);
			  var medata = JSON.parse(mdata);
			$.post('action.php?action=adduser', {
				username: medata.username,
				password: medata.password,
				name: medata.name
			}, function(data) {
				if (data == 'success') {
					location.reload();
				} else if (data == 'namenull') {
					layer.msg('姓名不能为空', {icon:2});
				} else if (data == 'usernull') {
					layer.msg('用户名不能为空', {icon:2});
				} else if (data == 'passnull') {
					layer.msg('密码不能为空', {icon:2});
				} else if (data == 'userhave') {
					layer.msg('该用户名已经被注册',  {icon:2});
				}
			});
			return false;
		  });
		});
	</script>
</div>
</html>
