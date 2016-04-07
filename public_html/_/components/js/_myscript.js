$(function() {

	//SLIDE UO MESSAGES AFTER 5 SECONDS
	$('div.alert-dismissible').delay(7000).slideUp();

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
				$('#comments').load(html + " #ajax-comments");
			}
		});
		return false;
	});

	$('form.fileForm').on('submit', function() {
		var $btn = $('#fileSubmit').button('loading');
		setTimeout(function() {
			$btn.button('reset');
		}, 43200000); //1000*60*60*12 (0.5 day)
	});

	$('form.registration').on('submit', function() {
		var $btn = $('#register').button('loading');
		setTimeout(function() {
			$btn.button('reset');
		}, 3600000); //1000*60*60 (1 hour)
	});

	$('form.contactus').on('submit', function() {
		var $btn = $('#contactbtn').button('loading');
		setTimeout(function() {
			$btn.button('reset');
		}, 300000); //1000*60*5 (5 min)
	});

	/**
	 * Function to perform smooth scrolling
	 */
	//$('a[href*=#]:not([href=#myCarousel], [href=#comments])').click(function() {
	//	if(location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname ==
	// this.hostname) { var target = $(this.hash); target     = target.length ? target : $('[name=' +
	// this.hash.slice(1) + ']'); if(target.length) { $('html,body').animate({scrollTop : target.offset().top}, 1000);
	// return false; } } });

	$('.confirmation').click(function(e) {
		var href = $(this).attr('href');

		swal({
				title              : "آیا مطمئن هستید؟",
				type               : "warning",
				showCancelButton   : true,
				confirmButtonColor : "#E74C3C",
				confirmButtonText  : "بله",
				cancelButtonText   : "خیر",
				closeOnConfirm     : true,
				closeOnCancel      : true
			},
			function(isConfirm) {
				if(isConfirm) {
					window.location.href = href;
				}
			});

		return false;
	});

}); //jQuery IS LOADED -----------------------------------------------------------------------------------------------

/**
 * Function opens the new window without address bar
 * @param url gets the URL
 */
function shareButton(url) {
	var x = screen.width / 2 - 700 / 2;
	var y = screen.height / 2 - 450 / 2;
	window.open(url.href, 'sharegplus', 'width=640, height=360,left=' + x + ',top=' + y + 'toolbar=no,scrollbars=no,location=0,resizable=yes');
}

/**
 * Variables to get the registration fields
 * @type {Element}
 */
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

/**
 * Function to check the username
 * @returns {boolean} TRUE if validation passes and FALSE otherwise
 */
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
/**
 * Function to check the first name
 * @returns {boolean} TRUE if validation passes and FALSE otherwise
 */
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
/**
 * Function to check the last name
 * @returns {boolean} TRUE if validation passes and FALSE otherwise
 */
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
/**
 * Function to check the password
 * @returns {boolean} TRUE if validation passes and FALSE otherwise
 */
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
/**
 * Function to check the password confirmation
 * @returns {boolean} TRUE if validation passes and FALSE otherwise
 */
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
/**
 * Function to check the email
 * @returns {boolean} TRUE if validation passes and FALSE otherwise
 */
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

// function vex_confirm(text) {
// 	vex.dialog.confirm({
// 		message: text,
// 		callback: function(value) {
// 			return console.log(value);
// 		}
// 	});
// }

/**
 * Initializing WOW animation:
 * WOW is used when user reaches the specific height,
 * WOW used animate.css file in order to work
 */
wow = new WOW({animateClass : 'animated', offset : 100});
wow.init();
/**
 * data-wow-delay="2s"
 * data-wow-offset="300"
 * data-wow-duration="4s"
 * data-wow-iteration="infinite"
 */