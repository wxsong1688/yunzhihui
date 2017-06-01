(function($){
	var start_from,end_on,new_page;
	var ms = {
		init:function(obj,args){
			return (function(){
				ms.showPage(obj,args);
				ms.fillHtml(obj,args);
				ms.bindEvent(obj,args);
			})();
		},
		showPage:function(obj,args){
			return (function(){
				var pageContentId = $("#"+args.pageContentId);
				pageContentId.children().css('display', 'none');
				pageContentId.children().slice(0, args.showCount).show();//显示信息 css('display', 'block')
			})();
		},
		//填充html
		fillHtml:function(obj,args){
			return (function(){
				var pageContentId = $("#"+args.pageContentId);
				var pageCount = Math.ceil(pageContentId.children().length/args.showCount);
				obj.empty();
				//上一页
				if(args.current > 1){
					obj.append('<a href="javascript:;" class="prevPage">上一页</a>');
				}else{
					obj.remove('.prevPage');
					obj.append('<span class="disabled">上一页</span>');
				}
				//中间页码
				if(args.current != 1 && args.current >= 4 && pageCount != 4){
					obj.append('<a href="javascript:;" class="tcdNumber">'+1+'</a>');
				}
				if(args.current-2 > 2 && args.current <= pageCount && pageCount > 5){
					obj.append('<span>...</span>');
				}
				var start = args.current -2,end = args.current+2;
				if((start > 1 && args.current < 4)||args.current == 1){
					end++;
				}
				if(args.current > pageCount-4 && args.current >= args.pageCount){
					start--;
				}
				for (;start <= end; start++) {
					if(start <= pageCount && start >= 1){
						if(start != args.current){
							obj.append('<a href="javascript:;" class="tcdNumber">'+ start +'</a>');
						}else{
							obj.append('<span class="current">'+ start +'</span>');
						}
					}
				}
				if(args.current + 2 < pageCount - 1 && args.current >= 1 && pageCount > 5){
					obj.append('<span>...</span>');
				}
				if(args.current != pageCount && args.current < pageCount -2  && pageCount != 4){
					obj.append('<a href="javascript:;" class="tcdNumber">'+pageCount+'</a>');
				}
				//下一页
				if(args.current < pageCount){
					obj.append('<a href="javascript:;" class="nextPage">下一页</a>');
				}else{
					obj.remove('.nextPage');
					obj.append('<span class="disabled">下一页</span>');
				}
			})();
		},
		//绑定事件
		bindEvent:function(obj,args){
			return (function(){
				var pageContentId = $("#"+args.pageContentId);
				var pageCount = Math.ceil(pageContentId.children().length/args.showCount);
				obj.on("click","a.tcdNumber",function(){
					var current = parseInt($(this).text());
					ms.gotoPage(obj,{"current":current,"pageContentId": args.pageContentId, "showCount": args.showCount});
					ms.fillHtml(obj,{"current":current,"pageContentId": args.pageContentId, "showCount": args.showCount});
					if(typeof(args.backFn)=="function"){
						args.backFn(current);
					}
				});
				//上一页
				obj.on("click","a.prevPage",function(){
					var temp3 = obj.children("span.current");
					var current = parseInt(temp3.text());
					ms.previousPage(obj,{"current":current,"pageContentId": args.pageContentId, "showCount": args.showCount});
					ms.fillHtml(obj,{"current":current-1,"pageContentId": args.pageContentId, "showCount": args.showCount});
					if(typeof(args.backFn)=="function"){
						args.backFn(current-1);
					}
				});
				//下一页
				obj.on("click","a.nextPage",function(){
					var temp3 = obj.children("span.current");
					var current = parseInt(temp3.text());
					ms.nextPage(obj,{"current":current,"pageContentId": args.pageContentId, "showCount": args.showCount});
					ms.fillHtml(obj,{"current":current+1,"pageContentId": args.pageContentId, "showCount": args.showCount});
					if(typeof(args.backFn)=="function"){
						args.backFn(current+1);
					}
				});
			})();
		},
		gotoPage:function(obj,args){
			return (function(){
				var pageContentId = $("#"+args.pageContentId);
				end_on = args.current * args.showCount;
				start_from = end_on - args.showCount;
				pageContentId.children().css('display', 'none').slice(start_from, end_on).show();
			})();
		},
		previousPage:function(obj,args){
			return (function(){
				new_page = args.current - 1;
				var temp3 = obj.children("span.current");
				if(temp3.prev('.tcdNumber').length==true){//prev() 在此表示找到类名为：page_link的前一个同胞元素
					ms.gotoPage(obj,{"current":new_page,"pageContentId": args.pageContentId, "showCount": args.showCount});
				}
			})();
		},
		nextPage:function(obj,args){
			return (function(){
				new_page = args.current + 1;
				var temp3 = obj.children("span.current");
				if(temp3.next('.tcdNumber').length==true){
					ms.gotoPage(obj,{"current":new_page,"pageContentId": args.pageContentId, "showCount": args.showCount});
				}
			})();
		}
	};
	$.fn.createPage = function(options){
		var args = $.extend({
			current : 1,
			pageContentId: "pageContent-tbody1",
			showCount: 10,
			backFn : function(){}
		},options);
		ms.init(this,args);
	}
})(jQuery);