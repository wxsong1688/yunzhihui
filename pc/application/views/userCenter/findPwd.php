<link rel="stylesheet" type="text/css" href="/public/css/findPwd.css"></link>
<script type="text/javascript" src="/public/js/findPwd.js"></script>
<!--middle start -->
<div class="findPwd-middle">
        <div class="findPwd-middle-sec show-center">
            <div class="findPwd-middle-section1 text-center findPwd-title">找回密码</div>
            <div class="findPwd-middle-section2">
                <div class="findPwd-sec2-part show-center">
                    <p class="float-left text-center infor-confirm faccurent"><i>1&nbsp;信息确认</i></p>
                    <p class="float-left text-center infor-confirm"><i>2&nbsp;密码重置</i></p>
                    <p class="float-left text-center infor-confirm"><i>3&nbsp;重置成功</i></p>
                    <div class="clear"></div>
                </div>
                <div class="findPwd-sec2-part1 show-center">
                    <div class="findPwd-account findPwd-top">
                        <label class="inline-block text-right">账户：</label>
                        <?php if( $p ): ?>
                            <input value="<?php echo $p;?>" id="phone" name="phone" onblur="if(this.value==''){this.value='请输入您的手机号'}" onfocus="if(this.value=='请输入您的手机号'){this.value=''}"/><span class='msgphone'></span>
                        <?php else: ?>
                            <input value="请输入您的手机号" id="phone" name="phone" onblur="if(this.value==''){this.value='请输入您的手机号'}" onfocus="if(this.value=='请输入您的手机号'){this.value=''}"/><span class='msgphone'></span>
                        <?php endif; ?>
                    </div>
                    <div class="findPwd-yzm findPwd-top">
                        <label class="inline-block text-right">验证码：</label>
                        <input value="" id="phonecode" name="phonecode" onblur="if(this.value==''){this.value='请输入验证码'}" onfocus="if(this.value=='请输入验证码'){this.value=''}"/>
                        <button type="button" class="yzm-get" id="yzm-get" onClick="get_mobile_code(this);">获取免费短信验证码</button>
                        <span class='msgcheckcode'></span>
                        <p class="findPwd-sm display-none">“验证码已发送至您的账户手机号，30分钟内有效，请勿泄露”</p>
                    </div>
                    <div class="text-center"><button class="findPwdNext">下一步</button></div>

                </div>
            </div>
        </div>
</div>
<!--middle end -->