/**
 * Created by Administrator on 2015/9/11.
 */

$(function(){
    for(var i =0; i< $(".creditor-title").length; i++){
        changeWidth(i);
    }

    function changeWidth(i){
        var width1 = $(".creditor-title").eq(i).width();
        var width2 = $(".creditor-icon").eq(i).width();
        var width3 = $(".pro-detail").eq(i).width();
        $(".blue-line").eq(i).width(width1-width2-width3-10);
    }

    $(".buy-creditor").click(function(){
        modalNew('bob',"购买债权",'请在新打开的汇付页面进行购买操作','','','','','已完成','重新购买','/FinanciaTransactions','');
        $(".register-success span").hide();
    })

});

function confirmAct(pid,mpid){
    $.ajax({
        type:'post',
        url:'/projectcredit/docredit',
        data:{pid:pid,mpid:mpid},  
        error:function(){  
            alert("error occured!!!");  
        },  
        success:function(data){
            if(data=='success'){
                modalNew('bob',"债权转让",'您已成功发布债权转让','','','','','确定','','/Usercenter','');
            }else{
                modalNew('bob',"债权转让",'发布债权转让失败','','','','','确定','','/Usercenter','');
            }
        }  
   
    }); 
}

