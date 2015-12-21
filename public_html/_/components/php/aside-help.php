<aside>
	<h2><i class="fa fa-cc-stripe fa-lg"></i> کمک مالی</h2>
	<p>همانطور که میدانید این ساخت این وب سایت، ساخت تمامی ویدئوها برای شما خانمها و آقایان گرامی خیلی هزینه و
	   وقت لازم دارد. تمامی این هزینه ها را بنده فراهم میکنم و از شما خواهش می کنم اگر مایل بودید کمکی یا هزینه
	   ای هر چند ناچیز به این وب سایت و کانال آموزشی ما کنید از دگمه ی زیر استفاده کنید.</p>

	<form action="help.php" method="POST">
		<!--<script-->
		<!--	src="https://checkout.stripe.com/checkout.js" class="stripe-button"-->
		<!--	data-key="--><?php //echo PUBLICKEY; ?><!--"-->
		<!--	data-image="https://s3.amazonaws.com/stripe-uploads/acct_14MqiV4eawdZ9ohBmerchant-icon-1435675807377-Logo-Small.png"-->
		<!--	data-name="parsclick.net"-->
		<!--	data-description="کمک به پارس کلیک"-->
		<!--	data-label="پرداخت"-->
		<!--	data-allow-remember-me="false"-->
		<!--	data-currency="gbp"-->
		<!--	data-amount="1000">-->
		<!--</script>-->
		<noscript>You must <a href="http://www.enable-javascript.com" target="_blank">enable JavaScript</a> in your web
		          browser in order to pay via Stripe.
		</noscript>
		<input
			class="btn btn-success btn-small"
			id="stripe"
			type="submit"
			value="پرداخت کنید"
			data-key="<?php echo PUBLICKEY; ?>"
			data-image="https://s3.amazonaws.com/stripe-uploads/acct_14MqiV4eawdZ9ohBmerchant-icon-1435675807377-Logo-Small.png"
			data-name="parsclick.net"
			data-amount="1000"
			data-currency="GBP"
			data-description="کمک به پارس کلیک"
			data-label="پرداخت"
			/>
		<script src="https://checkout.stripe.com/v2/checkout.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
		<script>
			$(document).ready(function() {
				$('#stripe').on('click', function(event) {
					event.preventDefault();

					var $button = $(this),
					    $form   = $button.parents('form');

					var opts = $.extend({}, $button.data(), {
						token: function(result) {
							$form.append($('<input>').attr({
								type : 'hidden',
								name : 'stripeToken',
								value: result.id
							})).submit();
						}
					});

					StripeCheckout.open(opts);
				});
			});
		</script>
	</form>
	<h2><i class="fa fa-cc-paypal fa-lg"></i> کمک مالی</h2>
	<p>اگر پی پل برای شما راحت تر است از دگمه ی زیر استفاده کنید. خیلی ممنون از مرام و معرفت شما و ساپورت کردن کانال
	   خودتون که متعلق به خود شماست.</p>

	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="9JWBPDDR598FN">
		<input type="submit" name="submit" class="btn btn-success btn-small" value="پرداخت کنید">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	</form>
</aside>