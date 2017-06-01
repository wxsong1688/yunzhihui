<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>基本信息</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userInformation.css?<?php echo rand(1000,9999)?>"></link>
    <script type="text/javascript" src="/public/js/jquery.js"></script>
	<script type="text/javascript" src="/public/js/userInformation.js?<?php echo rand(1000,9999)?>"></script>
	
	<script type="text/javascript">		
		function changeimg(){
			$(".upload-success").show();
		}
   </script>
    <!--[if lte IE 6]>
    <script type="text/javascript">
        alert('您的浏览器版本太低了');
        window.opener=null;
        window.open('','_self','');
        window.close();
    </script>
    <![endif]-->
</head>
<body>
<input type="hidden" name="checkRes" id="checkRes" value="<?php echo !empty($checkRes)?$checkRes:'';?>" />
<div class="imsec1-ul-out display-none"></div>
<div class="userInfor user-all" id="userInfor">
    <div class="user-section user-right-height" id="userInfor-section">
        <p class="userInfor-title usercenter-title">基本资料</p>
        <div class="userInfor-content">
			<p class="infor-sec2-title"><span class="infor-text1">个人信息</span></p>
            <div class="infor-content-section1">
                <div class="upload float-left">
                    <div id="preview" class="uploadImg show-center">
                        <!--<img src="/public/images/uploadImg.png">-->
                        <?php if($userInfo['headpic']): ?>
                            <img id="imghead" src="<?php echo $userInfo['headpic'];?>" border=0 width="102" height="122"/>
                        <?php else: ?>
                            <img id="imghead" src="/public/images/uploadImg.png" border=0 width="102" height="122"/>
                        <?php endif; ?>
                    </div>
                
                    <!-- <a href="javascript:;" class="file">上传头像
                    <input type="file" onchange="previewImage(this)" /></a> -->
                  
					<div class="text-center upfile-image position-re">
						<p class="upload-text text-center">上传头像</p>
						<div class="upfile-div display-none">
                            <div class="upfile-sec1">
                                <p class="float-left modify-img">上传头像</p>
                                <p class="float-right close-up">×</p>
                                <div class="clear"></div>
                            </div>
                            <div class="avatar-local-wrap">
                               
                                <div class="select-img">
                                <form id="submit_form" action="/Usercenter/uploadImg" method="post" enctype="multipart/form-data">
                                    <div class="upfile-sec2-img position-re">
                                        <label>上传文件：</label>
                                        <input type="button" value="上传头像" disabled class="form-control ng-pristine ng-valid inline-block">
                                        <input type="file" value="" class="form-file" id="upfile" name="upfile" onchange="changeimg()">
                                    </div>
									<p class="red-color upload-success up-top display-none">上传成功</p>
                                    <p class="flie-gs up-top color-grey">上传的文件格式支持jpg,png,gif</p>
                                    <p class="file-dx up-top color-grey">上传文件大小小于1M</p>
                                    <div class="fileUpload">                                        
                                        <input type="submit" size="15" name="sub" class="upload-btn" value="确定" />
                                    </div>
                                </form>
                                <div id="feedback"></div>
                                </div>
                            </div>
                           
                        </div>
						
						
					</div>
           
                    
					<div>
                        <label class="infor-text1">用户等级:</label>
                        <span class="infor-text1"><?php echo $userInfo['level']?></span>
                    </div>
                    <div class="text-center"><a class="inline-block uper-user">成为高端用户</a></div>
                </div>
                <div class="float-left infor-sec1-part2">
					<div class="infor-sec2-part">
                    <ul class="float-left">
                        <li>
                            <div class="infor-sec2-part-00 float-left">
                                <label class="infor-text2">用户名：</label>
                                <span class="infor-text2"><?php echo $userInfo['phone'];?></span>
                            </div>
                            <div class="clear"></div>
                        </li>
						<li>
                            <div class="infor-sec2-part-00 float-left">
                                <label class="infor-text2">昵称：</label>
                                <span class="infor-text2 niName"><?php echo $userInfo['username'];?></span> 
                                <input type="hidden" id="uid" name="uid" value="<?php echo $userInfo['uid'];?>">                                
								<input class="infor-text2 usersNi" id="niName" value="">
                            </div>
                            <a class="infor-sec2-part-01 float-left block infor-text3 nicEdit" href="javascript:void(0)">编辑</a>
							<div class="upfile-div editNc-div display-none">
								<div class="upfile-sec1">
									<p class="float-left modify-img">昵称修改</p>
									<p class="float-right close-up">×</p>
									<div class="clear"></div>
								</div>
								<div class="avatar-local-wrap">

									<div class="select-img">
										<div class="upfile-sec2-img position-re">
											<label>输入新昵称：</label>
											<input type="text" id="username" name="username" value="" class="new-nc">
										</div>
										<p class="flie-gs up-top color-grey user-lef1">2-12位汉字、英文、数字组合，可包含“-”以及“_”</p>
										<p class="file-dd up-top color-red"><!--账号已经存在，请重新登录--></p>
										<div class="fileUpload user-plef1">
											<input type="submit" size="15" name="sub" class="upload-btn updateNiname" value="确定" />
											<input type="button" size="15" name="sub" class="cancle-btn" value="取消" />
										</div>
										<div id="feedback"></div>
									</div>
								</div>

							</div>
                            <div class="clear"></div>
						</li>
                        <li>
                            <div class="infor-sec2-part-00 float-left">
                                <label class="infor-text2">真实姓名：</label>
                                <span class="infor-text2"><?php echo $userInfo['realname'];?></span>
                            </div>
                            <div class="finishiBind float-left mar-top00">
                                <?php if($userInfo['realname']): ?>
                                    <p class="float-left"><img src="/public/images/bind-00.png"></p>
                                    <span class="bind-text float-left">已通过实名认证</span>
                                <?php else: ?>
                                    <a href="/hfcenter/createaccount?c=<?php echo $code;?>" target="_blank" class="infor-sec2-part-01 float-left block infor-text3" id="identify">现在实名认证</a>
                                <?php endif; ?>
    								<div class="clear"></div>
							</div>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="infor-sec2-part-00 float-left">
                                <label class="infor-text2">身份证号：</label>
                                <span class="infor-text2"><?php echo $userInfo['identify'];?></span>
                            </div>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="infor-sec2-part-00 float-left">
                                <label class="infor-text2">
                                <?php if($userInfo['bank']['type']==1):?>
                                    首绑银行卡
                                <?php else:?>
                                    快捷绑定卡
                                <?php endif;?>
                                ：</label>
                                <span class="infor-text2">
                                <?php 
                                    if(isset($userInfo['bank']['card_num'])){
                                        echo $userInfo['bank']['card_num'];
                                        echo "(".$userInfo['bank']['bank_name'].")";
                                    }
                                ?>
                                </span>
                            </div>
                            <?php if(isset($userInfo['bank']['card_num'])): ?>
                                <p class="float-left">
                                <img src="/public/images/bind-00.png"></p>
                                <span class="bind-text float-left">已绑定</span>
                            <?php elseif($userInfo['realname']): ?>
                                <a href="/Hfcenter/userBindCard" target="_blank" id="bind_card" class="infor-sec2-part-01 float-left block infor-text3">现在绑定</a>

                            <?php else:?>
                                <p class="float-left">
                                <img src="/public/images/pwd-error.png"></p>
                                <span class="bind-text float-left">请先实名认证</span>
                            <?php endif; ?>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="infor-sec2-part-00 float-left">
                                <label class="infor-text2">绑定邮箱：</label>
                                <span class="infor-text2 bindEmail"><?php echo $userInfo['email'];?></span>								
                            </div>
                            <a class="infor-sec2-part-01 float-left block infor-text3 emailEdit" href="javascript:void(0)">
								现在绑定
							</a>
							<div class="upfile-div editNc-div display-none">
								<div class="upfile-sec1">
									<p class="float-left modify-img">邮箱绑定</p>
									<p class="float-right close-up">×</p>
									<div class="clear"></div>
								</div>
								<div class="avatar-local-wrap">
									<div class="select-img">
										<div class="upfile-sec2-img position-re">
											<label>输入新邮箱：</label>
											<input type="text" id="email" name="email" class="new-nc">									
										</div>
									
										<p class="flie-gs up-top color-grey user-lef1 msgemail"></p>
										<p class="file-dd up-top color-red"><!--邮箱已经存在，请重新登录--></p>
										<div class="fileUpload user-plef1">
											<input type="submit" size="15" name="sub" class="upload-btn updateEmail" value="确定" />
											<input type="button" size="15" name="sub" class="cancle-btn" value="取消" />
										</div>
										<div id="feedback"></div>
									</div>
								</div>
      
							</div>
                            <div class="clear"></div>
                        </li>
						<li>
							<div class="infor-sec2-part-00">
								<label class="infor-text2">注册时间：</label>
								<span class="infor-text2"><?php echo $userInfo['create_time'];?></span>
							</div>
						</li>
                        <li>
                            <div class="infor-sec2-part-00">
                                <label class="infor-text4">第三方支付：</label>
                                <span class="infor-text4"><?php echo $userInfo['hf_usrId'];?></span>
                            </div>
                        </li>
						<li class="bot-none" style="padding-top:0px;">
							<div class="infor-sec2-part-00">
								<a class="infor-sec2-part1-01 block" href="javascript:void(0);">*成为高端用户可以享受更多更高收益优质项目</a>
							</div>
						</li>
                    </ul>
                    <div class="clear"></div>
                </div>
					<div class="infor-sec2-part1">
                    <div class="infor-sec2-part1-00">
					
                        <div class="infor-sec2-part1-02">
                            <div class="infor-ts float-left">
                                <img src="/public/images/ts.png">
                            </div>
                            <div class="ts-text infor-wt float-left">
                                <h3>温馨提示：</h3>
                                <p>为了您的资金安全，请勿将您在云智慧平台的账号信息泄露给他人。</p>
                            </div>
                            <div class="clear"></div>
                        </div>
						
                    </div>
                </div>
				</div>
                <div class="clear"></div>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript" src="/public/js/userCenter.js"></script>
<script type="text/javascript">
	change_height();
</script>
</body>
</html>