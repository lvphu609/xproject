<?php
$resources = base_url().'resources/';
$url = base_url().'index.php/';
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?php echo $resources ?>img/tsms_fav.png">
  <script type="text/javascript" src="<?php echo $resources ?>js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $resources ?>js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo $resources ?>css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $resources ?>css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="<?php echo base_url("modules/login/assets/css/style.css"); ?>">

  <title>No Permission</title>
</head>
<body class="bg_body">
<div style="height:10px; width:100%;top: 0px;float: left;background: white;position: fixed;left: 0px;z-index: 10000;"></div>
<div id="navigationBar">
  <div id="navToggleMenu" class="iconRight"></div>

</div>
<div id="container">
  <div class="col-md-6 col-md-offset-3" style="margin-top: 1%;">
    <div class="alert alert-success fade in text-center">
      Oops! Bạn không có quyền truy cập vào chức năng này! <br /> Xin vui lòng liên hệ với <label class="label label-warning color_black valItemDelete">QUẢN TRỊ VIÊN</label>.
      <br /><a href="#" onclick="parent.history.back(); return false;">Quay lại trang trước</a>
      </div>
    </div>
</div>
<script>
  $.backstretch(["<?php echo $resources ?>img/financial-tower_bg.png"]);
</script>
</body>
</html>