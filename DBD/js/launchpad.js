jQuery.fn.center = function() {
	this.css({
    	"position": "absolute",
		"top": ((($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px"),
		"left": ((($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px")
	});
	return this;
}

$(function() {
	$(".launchpad").center();
});

$(window).resize(function() {
	$(".launchpad").center();
});