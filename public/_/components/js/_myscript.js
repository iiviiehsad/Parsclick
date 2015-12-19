$(function() {

	//SLIDE UO MESSAGES AFTER 5 SECONDS
	$('div.alert-dismissible').delay(5000).slideUp();

	// YOUTUBE INIT VIDEO PLAY
	$(".youtube").YouTubeModal({width : 640, height : 360});

	$('.collapse').collapse();

	//ACTIVATE COURSE TABS
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');

	/**
	 This function will make menus drop automatically
	 it targets the ul navigation and li drop-down
	 and uses the jQuery hover() function to do that.
	 */
	$('ul.nav li.dropdown').hover(function() {
		$('.dropdown-menu', this).fadeIn();
	}, function() {
		$('.dropdown-menu', this).fadeOut('fast');
	}); //HOVER

	//SHOW TOOLTIPS
	$("[data-toggle='tooltip']").tooltip({animation : true});

	//SHOW MODALS
	$('.modalphotos img').on('click', function() {
		$('#modal').modal({show : true});
		var mysrc      = this.src.substr(0, this.src.length - 7) + '.jpg';
		var modalimage = $('#modalimage');
		modalimage.css('width', "80%");
		modalimage.css('display', "block");
		modalimage.css('margin', "auto");
		modalimage.css('border-radius', "10px");
		modalimage.attr('src', mysrc);
		modalimage.on('click', function() {
			$('#modal').modal('hide');
		}); //HIDE MODAL
	}); //SHOW MODAL

	// INPUT FILE STYLE
	var wrapper = $('<label/>').css({height : 0, width : 0, 'overflow' : 'hidden'});
	$(':file').wrap(wrapper);
	//var fileInput = $(':file').wrap(wrapper);

	//fileInput.change(function() {
	//	$this = $(this);
	//	$('#file').text($this.val());
	//});
	//
	//$('#file').click(function() {
	//	fileInput.click();
	//}).show();

	// ADDING COURSE TO PLAYLIST USING AJAX ----------------------------------------------------------------------------------
	$('form.addtoplaylist').on('submit', function() {
		var that = $(this),
		    url  = that.attr('action'),
		    type = that.attr('method'),
		    data = {},
		    btn  = $("#btn");
		btn.prop('disabled', true);
		that.find('[name]').each(function() {
			var that   = $(this),
			    name   = that.attr('name');
			data[name] = that.val();
		});

		$.ajax({
			url     : url,
			type    : type,
			data    : data,
			success : function(html) {
				that.replaceWith('<a href="#" class="btn btn-info disabled"><i class="fa fa-check"></i> به لیست پخش اضافه شد</a>');
			}
		});
		return false;
	});

	// REMOVING COURSE FROM PLAYLIST USING AJAX --------------------------------------------------------------------------
	$('form.removefromplaylist').on('submit', function() {
		var that = $(this),
		    url  = that.attr('action'),
		    type = that.attr('method'),
		    data = {},
		    btn  = $("#btn");
		btn.prop('disabled', true);
		that.find('[name]').each(function() {
			var that   = $(this),
			    name   = that.attr('name');
			data[name] = that.val();
		});

		$.ajax({
			url     : url,
			type    : type,
			data    : data,
			success : function(html) {
				that.replaceWith('<a href="#" class="btn btn-danger disabled"><i class="fa fa-check"></i> از لیست پخش حذف شد</a>');
			}
		});
		return false;
	});

	// ADDING COMMENTS AJAX ----------------------------------------------------------------------------------------------
	$('form.submit-comment').on('submit', function() {
		var that = $(this),
		    url  = that.attr('action'),
		    type = that.attr('method'),
		    data = {};
		that.find('[name]').each(function() {
			var that   = $(this),
			    name   = that.attr('name');
			data[name] = that.val();
		});

		$.ajax({
			url     : url,
			type    : type,
			data    : data,
			success : function(html) {
				$('.submit-comment textarea').val('');
				$('#ajax-comments').load(html + " #ajax-comments");
			}
		});
		return false;
	});

	// FUNCTION FOR MEMBER'S VIDEO ---------------------------------------------------------------------------------------
	//var videos = document.querySelectorAll(".videoThumbnail");
	//for(var i = 0; i < videos.length; i++) {
	//	videos[i].addEventListener('click', clickHandler, false);
	//}
	//function clickHandler(e) {
	//	var mainVideo = document.getElementById("mainVideo");
	//	if(!isChrome || !isSafari) {
	//		mainVideo.src = e.target.currentSrc;
	//	} else {
	//		mainVideo.src = e.srcElement.currentSrc;
	//	}
	//}

	//FINDING THE BROWSERS NAME ------------------------------------------------------------------------------------------
	//var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
	//var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
	//
	////TO PLAY OR PAUSE VIDEO MANUALLY BY CLICKING ON THE VIDEO ---------------------------------------------------------
	//if(isChrome || isSafari) {
	//	var video = document.getElementById('mainVideo');
	//	if(video) {
	//		video.addEventListener(
	//				'play',
	//				function () {
	//					video.play();
	//				},
	//				false);
	//
	//		video.onclick = function () {
	//			if(video.paused) {
	//				video.play();
	//			} else {
	//				video.pause();
	//			}
	//		};
	//	}
	//}

	//SMOOTH SCROLLING ---------------------------------------------------------------------------------------------------
	//$('a[href*=#]:not([href=#myCarousel], [href=#comments])').click(function() {
	//	if(location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
	//		var target = $(this.hash);
	//		target     = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
	//		if(target.length) {
	//			$('html,body').animate({scrollTop : target.offset().top}, 1000);
	//			return false;
	//		}
	//	}
	//});

	// AJAX FOR UPLOAD VIDEO BUTTON
	$('form.uploadForm').on('submit', function() {
		var $btn = $('#upladSubmit').button('loading');
		setTimeout(function() {
			$btn.button('reset');
		}, 86400000); //1000*60*60*24*1 (1 day)
	});

	// AJAX FOR UPLOAD FILE BUTTON
	$('form.fileForm').on('submit', function() {
		var $btn = $('#fileSubmit').button('loading');
		setTimeout(function() {
			$btn.button('reset');
		}, 86400000); //1000*60*60*24*1 (1 day)
	});

}); //jQuery IS LOADED -----------------------------------------------------------------------------------------------

//function videoPlayer(url) {
//	var x = screen.width / 2 - 700 / 2;
//	var y = screen.height / 2 - 450 / 2;
//	window.open(url.href, 'sharegplus', 'width=640, height=360,left=' + x + ',top=' + y + 'toolbar=no,scrollbars=no,location=0,resizable=yes');
//}

function shareButton(url) {
	var x = screen.width / 2 - 700 / 2;
	var y = screen.height / 2 - 450 / 2;
	window.open(url.href, 'sharegplus', 'width=640, height=360,left=' + x + ',top=' + y + 'toolbar=no,scrollbars=no,location=0,resizable=yes');
}

//var bgvideo = document.getElementById('bgvideo');

//FUNCTIONS FOR LIVE INPUT VALIDATION ----------------------------------------------------------------------------------
var username      = document.getElementById('username');
var pass1         = document.getElementById('password');
var pass2         = document.getElementById('confirm_pass');
var firstname     = document.getElementById('first_name');
var lastname      = document.getElementById('last_name');
var email         = document.getElementById('email');
var message       = document.getElementById('confirmMessage');
var goodColor     = "#C8FAC8";
var badColor      = "#FAC8C8";
var mBadColor     = "#B94A48";
var usernameregex = /[^a-zA-Z0-9_.]/;
var passregex     = /[^A-Za-z0-9]/;

function checkUser() {
	if(usernameregex.test(username.value)) {
		username.style.backgroundColor = badColor;
		message.style.color            = mBadColor;
		message.innerHTML              = "اسم کاربری نباید دارای حروف مخصوص باشد";
		return false;
	} else if(username.value.indexOf(' ') >= 0) {
		username.style.backgroundColor = badColor;
		message.style.color            = mBadColor;
		message.innerHTML              = "در اسم کاربری نباید فاصله بکار رود";
		return false;
	} else if(username.value == '' || username.value == null) {
		username.style.backgroundColor = badColor;
		message.style.color            = mBadColor;
		message.innerHTML              = "اسم کاربری نباید خالی بماند";
		return false;
	} else {
		username.style.backgroundColor = goodColor;
		message.style.color            = goodColor;
		message.innerHTML              = "";
		return true;
	}
}
function checkfirstname() {
	if(firstname.value == '' || firstname.value == null || firstname.value == ' ') {
		firstname.style.backgroundColor = badColor;
		message.style.color             = mBadColor;
		message.innerHTML               = "نام نباید خالی بماند";
		return false;
	} else {
		firstname.style.backgroundColor = goodColor;
		message.style.color             = goodColor;
		message.innerHTML               = "";
		return true;
	}
}
function checklastname() {
	if(lastname.value == '' || lastname.value == null || lastname.value == ' ') {
		lastname.style.backgroundColor = badColor;
		message.style.color            = mBadColor;
		message.innerHTML              = "نام خانوادگی نباید خالی بماند";
		return false;
	} else {
		lastname.style.backgroundColor = goodColor;
		message.style.color            = goodColor;
		message.innerHTML              = "";
		return true;
	}
}
function checkPass() {
	if(pass1.value.length < 6) {
		pass1.style.backgroundColor = badColor;
		message.style.color         = mBadColor;
		message.innerHTML           = "پسورد کمتر از ۶ کاراکتر است";
		return false;
	} else if(!passregex.test(pass1.value)) {
		pass1.style.backgroundColor = badColor;
		message.style.color         = mBadColor;
		message.innerHTML           = "پسورد دارای حروف مخصوص نیست";
		return false;
	} else {
		pass1.style.backgroundColor = goodColor;
		message.style.color         = goodColor;
		message.innerHTML           = "";
		return true;
	}
}
function checkConfirmPass() {
	if(pass2.value.length < 6) {
		pass2.style.backgroundColor = badColor;
		message.style.color         = mBadColor;
		message.innerHTML           = "پسورد کمتر از ۶ کاراکتر است";
		return false;
	} else if(!passregex.test(pass2.value)) {
		pass2.style.backgroundColor = badColor;
		message.style.color         = mBadColor;
		message.innerHTML           = "پسوردها دارای حروف مخصوص نیستند";
		return false;
	} else if(pass1.value !== pass2.value) {
		pass2.style.backgroundColor = badColor;
		message.style.color         = mBadColor;
		message.innerHTML           = "پسوردها مطابقت ندارند";
		return false;
	} else {
		pass2.style.backgroundColor = goodColor;
		message.style.color         = goodColor;
		message.innerHTML           = "";
		return true;
	}
}
function checkEmail() {
	var regExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i;
	if(regExp.test(email.value) == false) {
		email.style.backgroundColor = badColor;
		message.style.color         = mBadColor;
		message.innerHTML           = "ایمیل معتبر نیست";
		return false;
	} else {
		email.style.backgroundColor = goodColor;
		message.style.color         = goodColor;
		message.innerHTML           = "";
		return true;
	}
}

//WOW ------------------------------------------------------------------------------------------------------------------
wow = new WOW({animateClass : 'animated', offset : 100});
wow.init();
//data-wow-delay="2s"
//data-wow-offset="300"
//data-wow-duration="4s"
//data-wow-iteration="infinite"
//----------------------------------------------------------------------------------------------------------------------
