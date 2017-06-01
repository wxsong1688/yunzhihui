    <!--banner start -->
    <div class="banner-all">
        <div class="banner">
            <div class="banner_container" id="index_banner">
                <div class="banner_content">
                    <div class="banner1">
                        <div class="banner1-all">
                            <a id="wel_one" class="banner_link_one banner1-01">
                                <img src="/public/images/banner1-01.png"  class="png" />
                            </a>
                            <a id="wel_two" class="banner_link_two banner1-02"><img src="/public/images/banner1-02.png"  class="png" /></a>
                            <a id="wel_three" class="banner_link_three banner1-03"> <img src="/public/images/banner1-03.png"  class="png" /></a>
                            <a id="wel_four" class="banner_link_four banner1-04"><img src="/public/images/banner1-04.png"  class="png" /></a>
                           
                        </div>
                    </div>
                </div>

                <div class="banner_content">
                    <div class="banner2">
                        <div class="banner1-all">
                            <a id="kf_one" class="banner_link_one banner1-01">
                                <img src="/public/images/banner1-01.png"  class="png" />
                            </a>
                            <a id="kf_two" class="banner_link_two banner1-02"><img src="/public/images/banner1-02.png"  class="png" /></a>
                            <a id="kf_three" class="banner_link_three banner1-03"> <img src="/public/images/banner1-03.png"  class="png" /></a>
                            <a id="kf_four" class="banner_link_four banner1-04"><img src="/public/images/banner1-04.png"  class="png" /></a>
                        </div>
                    </div>
                </div>
                <div class="banner_content">
                    <div class="banner3">
                        <div class="banner1-all">
                            <a id="dz_one" class="banner_link_one banner1-01">
                                <img src="/public/images/banner1-01.png"  class="png" />
                            </a>
                            <a id="dz_two" class="banner_link_two banner1-02"><img src="/public/images/banner1-02.png"  class="png" /></a>
                            <a id="dz_three" class="banner_link_three banner1-03"> <img src="/public/images/banner1-03.png"  class="png" /></a>
                            <a id="dz_four" class="banner_link_four banner1-04"><img src="/public/images/banner1-04.png"  class="png" /></a>
                        </div>
                    </div>
                </div>
                <ul class="banner_nav">
                    <li class="current"><a></a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                </ul>

                <!--<a class="prove_index" style="display: inline;"></a>
                <a class="next_index" style="display: inline;"></a>-->
            </div>
        </div>
        <?php if(empty($user)): ?>
            <div class="banner-login show-center">
                <!--if IE7-->
                <div class="banner-login-bg float-right"></div>
                <!--end if-->
                <div class="banner-login-all float-right">
                    <div class="banner-login-content show-center">
                        <form method="post" action="/Login/doLogin">
                        <input type="hidden" name="url" value="<?php echo !empty($u)?$u:'';?>" />
                            <div class="login-username show-center">
                                <div class="username">
                                    <input type="text" name="phone" placeholder="用户名或昵称" />
                                </div>
                            </div>
                            <div class="login-password show-center">
                                <div class="password">
                                <input type="password" name="pwd1" placeholder="密码" />
                                </div>
                            </div>
                            <div class="show-center code-all">
                                <div class="login-code show-center float-left">
                                    <div class="code">
                                        <input type="text" name="checkcode" placeholder="验证码" />
                                    </div>
                                </div>
                                <div class="login-code-img show-center float-left">
                                    <img id="imgcheck" style="cursor:pointer; vertical-align: middle;" src="/login/checkcode" onclick="javascript:this.src='/login/checkcode?tm='+Math.random()" /> 
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="login-button show-center">
                                <button type="submit">立即登录</button>
                            </div>
                            <p class="text-center no-account">哎呀！&nbsp;<a href="/usercenter/findPwd">忘记密码</a>&nbsp; </p>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif;?>
    </div>

    <!--banner end -->
    <!--bannerr-bottom start -->
    <div class="banner-bottom">
        <div class="banner-bottom-section1">
            <div class="banner-bottom-center">
                <div class="banner-bottom-part1 float-left">
                    <div class="float-left bb-part1-left">
                        <img src="/public/images/adv01.png">
                    </div>
                    <div class="float-left bb-part1-right">
                        <h1 class="h2-color1 h2-text">保安全</h1>
                        <p class="adv-p border-color1">资金由第三方支付平台托管，为您资产安全保驾护航</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="banner-bottom-part2 float-left">
                    <div class="float-left bb-part1-left">
                        <img src="/public/images/adv02.png">
                    </div>
                    <div class="float-left bb-part1-right">
                        <h1 class="h2-color2 h2-text">高收益</h1>
                        <p class="adv-p border-color2">远高于银行定期收益，稳健安全</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="banner-bottom-part3 float-left">
                    <div class="float-left bb-part1-left">
                        <img src="/public/images/adv03.png">
                    </div>
                    <div class="float-left bb-part1-right">
                        <h1 class="h2-color3 h2-text">灵活易</h1>
                        <p class="adv-p border-color3">资金提取灵活，可选随时提现，方便快捷</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="banner-bottom-part4 float-left">
                    <div class="float-left bb-part1-left">
                        <img src="/public/images/adv04.png">
                    </div>
                    <div class="float-left bb-part1-right">
                        <h1 class="h2-color4 h2-text">低门槛</h1>
                        <p class="adv-p border-color4">100元即可理财，小钱生钱，低门槛也能高收益</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div class="banner-bottom-section2">
            <div class="banner-bottom-center bb-zh">
           
            </div>
        </div>
    </div>
    <!--banner-bottom end -->
    <!--index-middle start -->
    <div class="index-middle">
        <!--middle-section1 start-->
        <div class="index-middle-section1">
            <div class="index-middle-section-center-home show-center">
                <div class="im-sec1">
                    <div class="float-left im-sec1-part1">
                        <p class="im-sec1-rj"></p>
                        <p class="im-part1-ptitle text-center">普惠<span class="diff-size">金融</span><span class="diff-size diff-color">6%~8%</span></p>
                        <div class="im-part1-img">
                            <a href="/financiaTransactions?p=fin_ph">
                                <img src="/public/images/bt02.png">
                            </a>
                        </div>
                        <div class="im-small-title text-center show-center">小钱生息，颇高收益</div>
                        <p class="text-center im-part1-p">一百元至一万元投资</p>
                    </div>
                    <div class="float-left im-sec1-part1 left-01">
                        <p class="im-sec1-rj"></p>
                        <p class="im-part1-ptitle text-center">精英<span class="diff-size">理财</span><span class="diff-size diff-color">8%~10%</span></p>
                        <div class="im-part1-img">
                            <a href="/financiaTransactions?p=fin_jy">
                                <img src="/public/images/bt03.png">
                            </a>
                        </div>
                        <div class="im-small-title text-center show-center">精英中产，量身打造</div>
                        <p class="text-center im-part1-p">一万元至十万元投资</p>
                    </div>
                    <div class="float-left im-sec1-part1 left-01">
                        <p class="im-sec1-rj"></p>
                        <p class="im-part1-ptitle text-center">高端<span class="diff-size">定制</span><span class="diff-size diff-color">10%~15%</span></p>
                        <div class="im-part1-img">
                            <a href="/financiaTransactions?p=fin_gd">
                                <img src="/public/images/bt04.png">
                            </a>
                        </div>
                        <div class="im-small-title text-center show-center">优质项目，高端专享</div>
                        <p class="text-center im-part1-p">十万元以上投资</p>
                        <a href="#" class="im-part-button text-center show-center">如何高端定制</a>
                    </div>
                    <div class="float-left im-sec1-part1 left-01">
                        <p class="im-sec1-rj"></p>
                        <p class="im-part1-ptitle text-center">债权转让</p>
                        <div class="im-part1-img">
                            <a href="/financiaTransactions?p=fin_zq">
                                <img src="/public/images/bt01.png">
                            </a>
                        </div>
                        <div class="im-small-title text-center show-center">收益更高，快来捡宝</div>
                        <p class="text-center im-part1-p">收益略高于原始项目</p>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <!--middle-section1 end-->

        <!--middle-section2 start-->
        <div class="index-middle-section2">
            <div class="index-middle-section-center-home show-center">
                <div class="im-sec2-title">项目精选&nbsp;<img class="im-sec2-tjt" src="/public/images/right-jt.png"></div>
                <div class="text-right"><a href="/FinanciaTransactions?p=fin_ph" class="see-more">更多项目&nbsp;>></a></div>
                <div class="im-sec2">
                    <div class="im-sec2-part">
                        <ul>

                            <?php foreach ($handpickProject as $key => $value): ?>
                                <li class="im-sec2-part00">
                                    <ul class="float-left">
                                        <li class="float-left im-sec2-part-00 ">
                                            <?php if($value['type']==2): ?>
                                                <img src="/public/images/pu.png">
                                            <?php elseif($value['type']==3): ?>
                                                <img src="/public/images/jing.png"> 
                                            <?php elseif($value['type']==4): ?>
                                                <img src="/public/images/hign.png">
                                            <?php endif;?>
                                        </li>
                                        <li class="float-left im-sec2-part-01 /public-left0">
                                            <p class="text-center diff-text1 height2">ID&nbsp;&nbsp;
                                            <a target="_blank" href="/Project/bid?pro_id=<?php echo $value['id'];?>">
                                            <font color="#0099cc"><?php echo $value['pro_num'];?></font></p></a>
                                            <p class="text-center"><a href="#" class="im-sec2-detail diff-text1"><?php echo $value['pro_name'];?></a></p>
                                        </li>
                                        <li class="float-left im-sec2-part-02 /public-left0">
                                            <p class="text-center diff-text2 height1"><?php echo $value['year_rate_out'];?></p>
                                            <p class="text-center diff-text1">年化收益</p>
                                        </li>
                                        <li class="float-left im-sec2-part-03 /public-left0">
                                            <p class="text-center diff-text3 height1"><?php echo $value['cycle']*30;?>天</p>
                                            <p class="text-center diff-text1">项目周期</p>
                                        </li>
                                        <li class="float-left im-sec2-part-04 /public-left0">
                                            <p class="text-center diff-text2 height1"><?php echo $value['amount'];?>元</p>
                                            <p class="text-center diff-text1">融资金额</p>
                                        </li>
                                        <li class="float-left im-sec2-part-05 /public-left0">
                                            <div>
                                                <img class="float-left" src="/public/images/mon.png">
                                                <p class="diff-text4 float-left">剩余金额:<span>￥<?php echo $value['remain_amount'];?></span></p>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="long-bar">
                                                <div class="out-bar float-left">
                                                    <div class="bar-00"></div>
                                                    <?php if($key==0): ?>
                                                        <div class="bar-00-slider bar-slider"></div>
                                                    <?php elseif($key==1): ?>
                                                        <div class="bar-01-slider bar-slider"></div>
                                                    <?php elseif($key==2): ?>
                                                        <div class="bar-02-slider bar-slider"></div>
                                                    <?php endif;?>
                                                </div>
                                                <label class="bar-num"><?php echo $value['percentage'];?></label>
                                                <div class="clear"></div>
                                            </div>
                                        </li>
                                        <li class="float-right im-sec2-part-06 /public-left0">
<?php if($value['status']<=5): ?>
                                            <a class="block button-tb button-tb-color1" target="_blank" href="/Project/bid?pro_id=<?php echo $value['id'];?>">
<?php elseif($value['status']>5): ?>
                                            <a class="block button-tb button-tb-color2" href="javascript:void(0);">
<?php endif;?>
                                                <?php echo $value['status_zh'];?>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="clear"></div>
                                </li>
                            <?php endforeach;?>
                           
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--middle-section2 end-->
        <!--middle-section3 start-->
        <div class="index-middle-section3">
            <div class="index-middle-section-center-home show-center">
                <div class="im-sec3-title">随时理财，尽在“掌”握&nbsp;<img class="im-sec3-tjt" src="/public/images/right-jt.png"></div>
                <div class="im-sec3">
                    <div class="im-sec3-part1 float-left">
                        <img src="/public/images/telephone.png">
                    </div>
                    <div class="im-sec3-part2 float-left">
                        <div class="im-sp2-01">
                            <div class="float-left download-bg app-download">
                                <img class="float-left" src="/public/images/apple.png">
                                <p class="float-left">Iphone版下载</p>
                            </div>
                            <div class="float-left download-bg andriod-download">
                                <img class="float-left" src="/public/images/andrio.png">
                                <p class="float-left">Andriod版下载</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <p class="im-sp2-02">轻松一扫，收益在“手”</p>
                        <p class="im-sp2-03">
                            轻松了解投资去向，快速知晓收益信息，及时接受新标上线提醒。即刻下载悦生活APP，
                            享用会生钱的手机钱包。
                        </p>
                        <div class="im-sp2-04">
                            <ul class="float-left">
                                <li class="float-left">
                                    <a href="#" class="block text-center public-border">
                                        <img src="/public/images/er1.png">
                                    </a>
                                    <a href="#" class="block im-sp2-04-btn text-center">
                                        从苹果商店下载
                                    </a>
                                </li>
                                <li class="float-left public-left2">
                                    <a href="#" class="block text-center public-border">
                                        <img src="/public/images/er2.png">
                                    </a>
                                    <a href="#" class="block im-sp2-04-btn text-center">
                                        下载Andrioid应用
                                    </a>
                                </li>
                                <li class="float-left public-left2">
                                    <span class="single-line block"></span>
                                </li>
                                <li class="float-left public-left3">
                                    <a href="#" class="block text-center public-border">
                                        <img src="/public/images/er3.png">
                                    </a>
                                    <a href="#" class="block im-sp2-04-btn text-center">
                                        微信/公众平台
                                    </a>
                                </li>

                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
        <div class="im-sec3-bot"></div>
        <!--middle-section3 end-->
    </div>
    <!--index-middle end -->
    <!--footer start-->
    <?php $this->load->helper('footer');?>
    <!--footer end-->
</body>
</html>