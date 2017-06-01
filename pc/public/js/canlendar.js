/**
 * Created by Administrator on 2015/9/17.
 */
$(function(){
    var $inputDate = $("#inputDate");
    var $inputDate1 = $("#inputDate1");
    $inputDate.DatePicker({
        format:'Y-m-d',
        date: $inputDate.val(),
        current: $inputDate.val(),
        starts: 1,
        position: 'r',
        locale: {
            days: [ "星期一", "星期二", "星期三", "星期四", "星期五", "星期六","星期日"],
            daysShort: [ "周一", "周二", "周三", "周四", "周五", "周六","周日"],
            daysMin: ["周一", "周二", "周三", "周四", "周五", "周六","周日"],
            months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            weekMin: '周一'
        },
        onBeforeShow: function(){
            $inputDate.DatePickerSetDate($inputDate.val(), true);
        },
        onChange: function(formated, dates){
            $inputDate.val(formated);
        }

    });
    $inputDate1.DatePicker({
        format:'Y-m-d',
        date: $inputDate1.val(),
        current: $inputDate1.val(),
        starts: 1,
        position: 'r',
        locale: {
            days: [ "星期一", "星期二", "星期三", "星期四", "星期五", "星期六","星期日"],
            daysShort: [ "周一", "周二", "周三", "周四", "周五", "周六","周日"],
            daysMin: ["周一", "周二", "周三", "周四", "周五", "周六","周日"],
            months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            weekMin: '周一'
        },
        onBeforeShow: function(){
            $inputDate1.DatePickerSetDate($inputDate1.val(), true);
        },
        onChange: function(formated, dates){
            $inputDate1.val(formated);
        }

    });
});