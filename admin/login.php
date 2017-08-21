<?php
if (is_file('../playconf.php')) {
    session_start();
    $_SESSION['url'] = array(
        '0' => 'admin',
        '1' => 'login'
    );
    header('location: ../');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>比赛 - 后台登陆</title>
</head>
<script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="./js/layui/layui.js"></script>
<link rel="stylesheet" type="text/css" href="./js/layui/css/layui.css" />
<body>
<script>
	layui.use('layer', function(){
		var layer = layui.layer;
		layer.open({
			type: 1,
			title: '后台管理员登陆',
			area: ['400px', '220px'],
			content: $('#login')
		});
	});
</script>
</body>
<div id="login" style="width: 90%; display: none; margin: 10px auto 0">
	<form class="layui-form layui-form-pane" action="">
		<div class="layui-form-item">
			<label class="layui-form-label">用户名</label>
			<div class="layui-input-block">
				<input type="text" name="username" placeholder="请输入管理员用户名" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-block">
				<input type="password" name="password" placeholder="请输入管理员密码" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit1">登录</button>
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
			$.post('action?action=adminlogin', {
				username: medata.username,
				password: medata.password
			}, function(data) {
				if (data == 'success') {
					location.reload();
				} else if (data == 'passerror') {
					layer.msg('密码错误', {icon:2});
				} else if (data == 'usererror') {
					layer.msg('管理员不存在',  {icon:2});
				} else if (data == 'error') {
					layer.msg('用户名和密码不能为空',  {icon:2});
				}
			});
			return false;
		  });
		});
	</script>
</div>

</html>
