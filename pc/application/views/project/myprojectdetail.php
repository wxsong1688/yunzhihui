<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>悦生活-我的投资详情页面</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/header.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/banner.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/index-middle.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/footer.css"></link>
    <script type="text/javascript" src="/public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/public/js/jQueryRotate.2.2.js"></script>
    <script type="text/javascript" src="/public/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="/public/js/i.js"></script>
    <script type="text/javascript" src="/public/js/header.js"></script>
    <script type="text/javascript" src="/public/js/index.js"></script>
    <script type="text/javascript" src="/public/js/banner.js"></script>
</head>
<body>
<div class="footer-center show-center">

<table>
<tr><td width="100">【项目投资详情】</td></tr>
<tr><td width="100">ID：</td><td><?php echo $mypro['id'];?></td></tr>
<tr><td>项目名称：</td><td><?php echo $mypro['pro_name'];?></td></tr>
<tr><td>项目类型：</td><td><?php echo $mypro['type'];?></td></tr>
<tr><td>融资金额：</td><td><?php echo sprintf("%.2f", $mypro['amount']);?></td></tr>
<tr><td>项目周期：</td><td><?php echo $mypro['cycle'];?></td></tr>
<tr><td>项目目的：</td><td><?php echo $mypro['projectinfo']['proj_use'];?></td></tr>
<tr><td>项目描述：</td><td><?php echo $mypro['projectinfo']['proj_desc'];?></td></tr>
<tr><td>项目展示：</td><td><img src="<?php echo !empty($mypro['projectinfo']['proj_rzpic'])?$mypro['projectinfo']['proj_rzpic']:'';?>" /></td></tr>
</table>

<br>
<table>
<tr><td width="100">【公司信息】</td><td></td></tr>
<tr><td>公司信息：</td><td><?php echo $mypro['companyinfo']['comp_industry'];?></td></tr>
<tr><td>公司规模：</td><td><?php echo $mypro['companyinfo']['comp_scale'];?></td></tr>
<tr><td>公司担保：</td><td><?php echo $mypro['companyinfo']['comp_guarantee'];?></td></tr>
</table>

<br>
<table>
<tr><td width="100">【借款人信息】</td><td></td></tr>
<tr><td>借款人姓名：</td><td><?php echo $mypro['financierinfo']['financier_username'];?></td></tr>
<tr><td>借款人性别：</td><td><?php echo $mypro['financierinfo']['financier_sex'];?></td></tr>
<tr><td>借款人年龄：</td><td><?php echo $mypro['financierinfo']['financier_year'];?></td></tr>
<tr><td>借款人其他：</td><td><?php echo $mypro['financierinfo']['financier_mar'];?></td></tr>
<tr><td>发布时间</td><td><?php echo $mypro['create_time'];?></td></tr>
</table>

<br><br>
我的投资金额(元)：<?php echo sprintf("%.2f", $mypro['remain_amount']);?>
<br>
</div>
</body>
</html>