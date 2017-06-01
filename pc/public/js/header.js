/**
 * Created by houwenli on 2015/7/12.
 */

$(function(){
    $(".close-icon").click(function(){
        $(this).parent(".top-section1").slideUp("500");
    });

    $(".part1-top").hover(function(){
        var src = $(this).attr("src").split(".");
        $(this).attr({"src":src[0]+"-00."+src[1]});
    },function(){
        var src = $(this).attr("src").split("-");
       // alert(src);
        $(this).attr({"src":src[0]+".png"});
    });

    var $navLine = $(".nav-bot-line");
    var pos = {0:21,1:99,2:213,3:327,4:441};
    var secTemp = $(".section3-left1>ul>li");
    for(var m= 0;m<secTemp.length;m++){
        var currentT = secTemp.eq(m).find("a");
        if(currentT.attr("class").indexOf("nav-a-current") != -1){
            temp = m;
            sWidth = secTemp.eq(temp).find("a").width();
            $navLine.css({"left":pos[temp],"width":sWidth});
            break;
        }
    }
    secTemp.each(function(i){
        $(this).hover(function(){
            var oWidth = $(this).find("a").width();
            $(this).find("a").addClass("nav-a-current");
            $(this).siblings("li").not(secTemp.eq(temp)).find("a").removeClass("nav-a-current");
            $navLine.stop(true).animate({"left":pos[i],"width":oWidth},500);
        },function(){
            secTemp.eq(temp).find("a").addClass("nav-a-current");
            $(this).not(secTemp.eq(temp)).find("a").removeClass("nav-a-current");
            $navLine.stop(true).animate({"left":pos[temp],"width":sWidth},500);
        });
    });
    $("#marquee").marquee({yScroll: "bottom"});



function outputMoney(number)
{ 
    number=number.replace(/\,/g,""); 
    if (number=="") return ""; 
    if(number<0) 
        return '-'+outputDollars(Math.floor(Math.abs(number)-0) + '') + outputCents(Math.abs(number) - 0); 
    else 
        return outputDollars(Math.floor(number-0) + '') + outputCents(number - 0); 
} 

function outputDollars(number) 
{ 
    if (number.length<= 3) 
        return (number == '' ? '0' : number); 
    else 
    { 
        var mod = number.length%3; 
        var output = (mod == 0 ? '' : (number.substring(0,mod))); 
        for (i=0 ; i< Math.floor(number.length/3) ; i++) 
        { 
            if ((mod ==0) && (i ==0)) 
            output+= number.substring(mod+3*i,mod+3*i+3); 
            else 
            output+= ',' + number.substring(mod+3*i,mod+3*i+3); 
        } 
        return (output); 
    } 
}

function outputCents(amount) 
{
    amount = Math.round( ( (amount) - Math.floor(amount) ) *100); 
    return (amount<10 ? '.0' + amount : '.' + amount); 
}

function fmoney(s, n)  
{  
   n = n > 0 && n <= 20 ? n : 2;  
   s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";  
   var l = s.split(".")[0].split("").reverse(),  
   r = s.split(".")[1];  
   t = "";  
   for(i = 0; i < l.length; i ++ )  
   {  
      t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");  
   }  
   return t.split("").reverse().join("") + "." + r;  
} 

});


