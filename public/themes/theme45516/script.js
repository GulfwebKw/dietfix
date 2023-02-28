var tablet_size = 767;
var direction='left';
function positionLoginFrame() {
	var main_body_offset = $('.motopress-wrapper > .container').offset();
	var loginFrame = $('#loginframe');
	var login_but = $('.login-frame');
   	if (document.body.offsetWidth > tablet_size) {
		var login_offset = login_but.offset();
		// if(direction == 'right') {
		// 	// var space = document.body.offsetWidth - login_offset.left - login_but.width - main_body_offset.left;
		// 	var space = (login_offset.left - main_body_offset.left) + login_but.width() - loginFrame.width();
		// } else {
		// 	var space = login_offset.left - main_body_offset.left;
		// }
		// 	loginFrame.css('left',space+'px');
	}
}
jQuery(document).ready(function($) {
	positionLoginFrame();
   	if (document.body.offsetWidth > tablet_size) {
		var loginFrame = $('#loginframe');
		$('.nav__primary #topnav > .login-frame > a').click(function(event) {
			event.preventDefault();
			loginFrame.slideToggle();
		});
	}
});
window.onresize = positionLoginFrame;
window.onload = positionLoginFrame;
