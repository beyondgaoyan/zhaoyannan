
/**
 *      [品牌空间] (C)2001-2010 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: header.js 5200 2010-11-30 02:19:27Z fanshengshuai $
 */

search_w = $("#srchtxt").val();

var search = {};
search.searchRedirect = function() {
	$("#searchoption li a").click(function() {
		if ("" == $("#srchtxt").val() || search_w == $("#srchtxt").val()) {
			$("#srchtxt").focus();
			return false;
		}
		actionUrl = "";
		searchType = $(this).attr('search_type');
		switch (searchType) {
		case 'shopsearch':
			actionUrl = 'street.php?range=all';
			break;
		case 'consume':
			actionUrl = 'consume.php';
			break;
		case 'goodssearch':
			actionUrl = 'goodsearch.php';
			break;
		default:
			break;
		}
		if ("" == actionUrl) return false;
		$("#srchtxt").attr("name", "keyword");
		$("#form_search").attr("action", actionUrl);
		$("#form_search").submit();
		return false;
	});
};

function changeclass(obj, Otar, type) {
	if (type == 1) {
		$("#" + Otar).hide();
		$("#addto_supnav").unbind().bind("mouseover", function() {
			$("#brandspce > a").addClass("mouseover");
		}).bind("mouseout", function() {
			$("#brandspce > a").removeClass("mouseover");
		});
	} else {
		$("#" + Otar).show();
	}
}

$(function(){
	search.searchRedirect();
	$("#srchtxt").click(function() { if ($(this).val() == search_w) {$(this).val("");};});
	$("#srchtxt").blur(function() { if ($(this).val() == "") {$(this).val(search_w);};});
	$("#search_submit").click(function() {if ($("#srchtxt").val() == search_w || $("#srchtxt").val() == "") {alert(search_w);return false;}});
	$("#nav_message_handle").hover(
		function() {
			$(this).addClass("on");
			$("#show_navmsg").addClass("nav_msg_active");
			$("#show_navmsg").show();
			$("#show_navmsg").css({top:'30px'});
			
		},
		function() {
			$(this).removeClass("on");
			$("#show_navmsg").removeClass("nav_msg_active");
			$("#show_navmsg").hide();
	});
	$("#nav_myshops_handle").hover(
		function() {
			$(this).addClass("on");
			$("#show_myshops").show();
			
		},
		function() {
			$(this).removeClass("on");
			$("#show_myshops").hide();
	});
});

