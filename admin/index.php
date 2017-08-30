<?php
if (is_file('../playconf.php')) {
    session_start();
    $_SESSION['url'] = array(
        '0' => 'admin',
        '1' => 'index'
    );
    header('location: ../');
} else if (!isset($_SESSION['admin_id'])) {
    header("location: ./admin/login.php");
} else if ($_SESSION['admin_id'] == "") {
    header("location: ./admin/login.php");
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
<title>后台 - 比赛管理</title>
</head>
<script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="./js/layui/layui.js"></script>
<link rel="stylesheet" type="text/css" href="./js/layui/css/layui.css" />
<body>
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo">后台 - 比赛管理</div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
      <li class="layui-nav-item"><a href="./admin/index.php">比赛管理</a></li>
      <li class="layui-nav-item"><a href="./admin/user.php">用户管理</a></li>
      <li class="layui-nav-item"><a href="./admin/admin.php">管理员管理</a></li>
    </ul>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item">
        <a href="javascript:;">
          <?php echo $_SESSION['admin_name'];?>
        </a>
        <dl class="layui-nav-child">
          <dd><a href="javascript:editpass(<?php echo $_SESSION['admin_id'];?>)">修改密码</a></dd>
        </dl>
      </li>
      <li class="layui-nav-item"><a href="javascript:adminexit()">退出</a></li>
    </ul>
  </div>
  
  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree"  lay-filter="test">
        <li class="layui-nav-item layui-nav-itemed">
          <a class="" href="javascript:;">菜单</a>
          <dl class="layui-nav-child">
            <dd><a href="javascript:addplay()">添加比赛</a></dd>
            <dd><a href="javascript:adduser()">添加用户</a></dd>
            <dd><a href="javascript:addadmin()">添加管理员</a></dd>
            <dd><a href="./admin/list.php">待审核列表</a></dd>
          </dl>
        </li>
      </ul>
    </div>
  </div>
  
  <div class="layui-body">
    <!-- 内容主体区域 -->
	  <table id="table1" lay-filter="table1"></table>
	<script>
	layui.use(['table','layer'], function(){
	  var table = layui.table,
	  layer = layui.layer;
		var dwidth = $(document).width()-210;
		var idwidth = dwidth*0.1;
		var titlewidth = dwidth*0.18;
		var contwidth = dwidth*0.27;
		var timewidth = dwidth*0.18;
		var actionwidth = dwidth*0.27;
		table.render({
			elem: '#table1',
			cols:  [[
				{field: 'id', title: 'ID', width: idwidth, align:'center'}
				,{field: 'title', title: '标题', width: titlewidth, align:'center'}
				,{field: 'cont', title: '简介', width: contwidth, align:'center'}
				,{field: 'date', title: '日期', width: timewidth, align:'center'}
				,{fixed: 'right', field: 'action', title: '操作', width: actionwidth, align:'center', toolbar: '#barDemo'}
			  ]],
			data  :[<?php foreach ($rows as $row) {?>
				{
			"id":"<?php echo $row['id'];?>",
			"title":"<?php echo $row['title'];?>",
			"cont":"<?php echo $row['cont'];?>",
			<?php $gdate = date("Y-m-d", $row['date']);?>
			"date":"<?php echo $gdate;?>",
			<?php $gdate = date("Y-n-j", $row['date']);
			$ymd = explode("-", $gdate);?>
			"year":"<?php echo $ymd['0'];?>",
			"month":"<?php echo $ymd['1'];?>",
			"day":"<?php echo $ymd['2'];?>",
			},<?php }?>
			],
		});
		table.on('tool(table1)', function(obj){
		  var data = obj.data; //获得当前行数据
		  var layEvent = obj.event; //获得 lay-event 对应的值
		  var tr = obj.tr; //获得当前行 tr 的DOM对象
			
			if (layEvent == 'detail') { //查看
				$.post('action.php?action=adminaward', {
					id: data.id
					}, function(data) {
						if (data == 'success') {
							location.reload();
						}
					});
			} else if(layEvent === 'del'){ //删除
			layer.confirm('删除一个比赛就会删除这个比赛的所有获奖信息，确定要删除比赛“'+data.title+'”吗？',{title: '提示'}, function(index){
				$.post('action.php?action=deleteplay', {
				id: data.id
				}, function(data) {
					if (data == 'success') {
						location.reload();
					}
				});
			});
		  } else if(layEvent === 'edit'){ //编辑
			  $('#idme').val(data.id);
			  $('#titleme').val(data.title);
			  $('#contme').html(data.cont);
			  $("#sey").find("option[text='"+data.year+"']").attr("selected",true);
			  $('#sem').val("8");
			  $('#sed').val("29");
			layer.open({
			  type: 1,
			  title: '编辑比赛信息',
			  area: ['690px', '470px'],
			  content: $('#editme')
			});
		  }
		});
	});
	</script>
  </div>
  
  <div class="layui-footer">
    <!-- 底部固定区域 -->
    © 2017 比赛系统后台管理
  </div>
</div>

<script>
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;
  
});
</script>
</body>
<script type="text/javascript">
	function addplay() {
		layer.open({
			  type: 1,
			  title: '添加比赛',
			  area: ['690px', '470px'],
			  content: $('#addplay')
			});
	}
	function adduser() {
		layer.open({
			  type: 1,
			  title: '添加用户',
			  area: ['600px', '280px'],
			  content: $('#adduser')
			});
	}
	function addadmin() {
		layer.open({
			  type: 1,
			  title: '添加管理员',
			  area: ['600px', '280px'],
			  content: $('#addadmin')
			});
	}
	function adminexit() {
		$.post('action.php?action=adminexit', {
			
		}, function(data) {
			if (data == 'success') {
				location.reload();
			}
		});
	}
	function editpass(id) {
		$('#adminid').val(id);
		layer.open({
			  type: 1,
			  title: '修改密码',
			  area: ['600px', '180px'],
			  content: $('#editpass')
			});
	}
</script>
<script type="text/html" id="barDemo">
	<a class="layui-btn layui-btn-mini" lay-event="detail">查看</a>
 	<a class="layui-btn layui-btn-mini" lay-event="edit">编辑</a>
 	<a class="layui-btn layui-btn-danger layui-btn-mini" lay-event="del">删除</a>
</script>
<div id="editme" style="width: 90%; display: none; margin: 10px auto 0">
	<form class="layui-form layui-form-pane" action="">
		<input id="idme" name="id" style="display: none" type="text" />
		<div class="layui-form-item">
			<label class="layui-form-label">标题</label>
			<div class="layui-input-block">
				<input type="text" id="titleme" name="title" placeholder="请输入标题" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item layui-form-text">
			<label class="layui-form-label">简介</label>
			<div class="layui-input-block">
			  <textarea placeholder="请输入简介" id="contme" name="cont" class="layui-textarea"  style="height: 200px"></textarea>
			</div>
		</div>
		<div class="layui-form-item">
            <div class="layui-input-inline">
              <select name="year" id="sey" >
                <option value="">请选择年</option>
                <?php 
                for ($i = 2013; $i < 2020; $i++) {
                ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php }?>
              </select>
            </div>
            <div class="layui-input-inline">
              <select name="month" id="sem">
                <option value="">请选择月</option>
                <?php 
                for ($i = 1; $i < 13; $i++) {
                ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php }?>
              </select>
            </div>
            <div class="layui-input-inline">
              <select name="day" id="sed">
                <option value="">请选择日</option>
                <?php 
                for ($i = 1; $i < 32; $i++) {
                ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php }?>
              </select>
            </div>
          </div>
		<div class="layui-form-item">
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit1">确认修改</button>
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
			$.post('action.php?action=editplay', {
				id: medata.id,
				title: medata.title,
				cont: medata.cont,
				year: medata.year,
				month: medata.month,
				day: medata.day,
			}, function(data) {
				if (data == 'success') {
					location.reload();
				} else if (data == 'titlenull') {
					layer.msg('标题不能为空', {icon:2});
				} else if (data == 'contnull') {
					layer.msg('简介不能为空', {icon:2});
				} else if (data == 'datenull') {
					layer.msg('日期不能为空', {icon:2});
				}
			});
			return false;
		  });
		});
	</script>
</div>
<div id="addplay" style="width: 90%; display: none; margin: 10px auto 0">
	<form class="layui-form layui-form-pane" action="">
		<div class="layui-form-item">
			<label class="layui-form-label">标题</label>
			<div class="layui-input-block">
				<input type="text" name="title" placeholder="请输入标题" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item layui-form-text">
			<label class="layui-form-label">简介</label>
			<div class="layui-input-block">
			  <textarea placeholder="请输入简介" name="cont" class="layui-textarea" style="height: 200px"></textarea>
			</div>
		</div>
		<div class="layui-form-item">
            <div class="layui-input-inline">
              <select name="year">
                <option value="">请选择年</option>
                <?php 
                for ($i = 2013; $i < 2020; $i++) {
                ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php }?>
              </select>
            </div>
            <div class="layui-input-inline">
              <select name="month">
                <option value="">请选择月</option>
                <?php 
                for ($i = 1; $i < 13; $i++) {
                ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php }?>
              </select>
            </div>
            <div class="layui-input-inline">
              <select name="day">
                <option value="">请选择日</option>
                <?php 
                for ($i = 1; $i < 32; $i++) {
                ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php }?>
              </select>
            </div>
          </div>
		<div class="layui-form-item">
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit2">确认添加</button>
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
			$.post('action.php?action=addplay', {
				title: medata.title,
				cont: medata.cont,
				year: medata.year,
				month: medata.month,
				day: medata.day,
			}, function(data) {
				if (data == 'success') {
					location.reload();
				} else if (data == 'titlenull') {
					layer.msg('标题不能为空',  {icon:2});
				} else if (data == 'contnull') {
					layer.msg('简介不能为空', {icon:2});
				} else if (data == 'datenull') {
					layer.msg('日期不能为空', {icon:2});
				} else if (data == 'playhave') {
					layer.msg('同标题比赛已存在', {icon:2});
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
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit3">确认添加</button>
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
<div id="addadmin" style="width: 90%; display: none; margin: 10px auto 0">
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
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit4">确认添加</button>
		</div>
	</form>
	<script>
		layui.use(['form', 'layer'], function(){
		  var form = layui.form
		  ,layer = layui.layer;

		  //监听提交
		  form.on('submit(submit4)', function(data){
			  var mdata = JSON.stringify(data.field);
			  var medata = JSON.parse(mdata);
			$.post('action.php?action=addadmin', {
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
				} else if (data == 'adminhave') {
					layer.msg('该管理员已经存在', {icon:2});
				}
			});
			return false;
		  });
		});
	</script>
</div>
<div id="editpass" style="width: 90%; display: none; margin: 10px auto 0">
	<form class="layui-form layui-form-pane" action="">
		<input id="adminid" name="id" style="display: none" type="text" />
		<div class="layui-form-item">
			<label class="layui-form-label">新密码</label>
			<div class="layui-input-block">
				<input type="password" name="password" placeholder="请输入新密码" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<button style="width: 100%" class="layui-btn" lay-submit="" lay-filter="submit5">确认修改</button>
		</div>
	</form>
	<script>
		layui.use(['form', 'layer'], function(){
		  var form = layui.form
		  ,layer = layui.layer;

		  //监听提交
		  form.on('submit(submit5)', function(data){
			  var mdata = JSON.stringify(data.field);
			  var medata = JSON.parse(mdata);
			$.post('action.php?action=editpass', {
				id: medata.id,
				password: medata.password,
			}, function(data) {
				if (data == 'success') {
					location.reload();
				} else if (data == 'passnull') {
					layer.msg('密码不能为空', {icon:2});
				}
			});
			return false;
		  });
		});
	</script>
</div>
</html>
