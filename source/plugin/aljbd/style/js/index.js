/**
 *      [品牌空间] (C)2001-2010 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: index.js 4237 2010-08-20 09:10:33Z fanshengshuai $
 */

function showAuto(){
	n = n >= (count - 1) ? 0 : n + 1;
	$("#play_text span").eq(n).trigger('mouseover');
}

var t = n = count = 0;

if ($("#play_list")[0]) {
	$(function() {
		count = $("#play_list a").size();
		var listr = '';
		if(count > 0) {
			for(var i = 1; i < ( count + 1 ); i++) {
				listr += "<span style=\"background: none repeat scroll 0% 0% rgb(252, 242, 207); color: rgb(217, 75, 1); height: 16px; width: 16px;\">"+i+"</span>";
			}
			$('#play_text').append(listr);
		}
		$("#play_list a:not(:first-child)").hide();
		$("#play_text span:first-child").css({"background":"#FF9415",'color':'#FFF','height':'18px','width':'18px'});

		$("#play_text span").mouseover(function() {
			var _this = this,
			i = $(_this).text() - 1;
			n = i;
			if (i >= count) return;
			$(_this).css({"background":"#FF9415",'color':'#FFF','height':'18px','width':'18px'}).siblings().css({"background":"#FCF2CF",'color':'#D94B01','height':'16px','width':'16px'});
			$("#play_list a").filter(":visible").fadeOut(200).parent().children().eq(i).fadeIn(1000);
		});

		clearInterval(t);
		t = setInterval("showAuto()", 3000);
	});
}

$("#category ul").find("li").each(
	function() {
		$(this).mouseover(
			function() {
				$("#" + this.id + "_menu").show();
				$(this).addClass("a");
			}
		);
		$(this).mouseout(
			function() {
				$(this).removeClass("a");
				$("#" + this.id + "_menu").hide();
			}
		);
	}
);

showAllCategory = false;
$("#more_cat").click(function() {
	showAllCategory = !showAllCategory;
	cat_count = 0;
	if (showAllCategory) {
		$(this).addClass("close");
		$("#category ul").find("li").each(function() {
			cat_count ++;
			cat_count > 8 ? $(this).show("slow") : "";
		});
	} else {
		$(this).removeClass("close");
		$("#category ul").find("li").each(function() {
			cat_count ++;
			cat_count > 8 ? $(this).hide("slow") : "";
		});
	}
});

$("#hot_shop").find("li").each(function() {
	$(this).mouseover(
		function() {
			$("#hot_shop").find("li").each(function() {
				$(this).removeClass("a");
			});
			$(this).addClass("a");
			$(($(this).find("div"))[0]).show();
		}
	);
});
