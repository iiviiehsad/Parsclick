<aside>
	<h2>کمک به ما</h2>
	<form action="help.php" method="POST">
		<button
				type="submit"
				class="btn btn-success btn-large btn-block"
				id="stripe"
				data-key="<?php echo PUBLICKEY; ?>"
				data-image="https://s3.amazonaws.com/stripe-uploads/acct_14MqiV4eawdZ9ohBmerchant-icon-1435675807377-Logo-Small.png"
				data-name="parsclick.net"
				data-amount="1000"
				data-currency="GBP"
				data-description="کمک به پارس کلیک"
				data-label="پرداخت"
		>
			<i class="fa fa-cc-stripe fa-lg fa-fw"></i> <span>پرداخت کنید</span>
		</button>
		<script src="https://checkout.stripe.com/v2/checkout.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
		<script>
		$(document).ready(function() {
			$('#stripe').on('click', function(event) {
				event.preventDefault();
				var $button = $(this),
				    $form   = $button.parents('form');
				var opts    = $.extend({}, $button.data(), {
					token : function(result) {
						$form.append($('<input>').attr({
							type  : 'hidden',
							name  : 'stripeToken',
							value : result.id
						})).submit();
					}
				});
				StripeCheckout.open(opts);
			});
		});
		</script>
	</form>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="POST" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="9JWBPDDR598FN">
		<button type="submit" name="submit" class="btn btn-success btn-large btn-block">
			<i class="fa fa-cc-paypal fa-lg fa-fw"></i> <span>پرداخت کنید</span>
		</button>
		<img alt="PayPal Parsclick" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	</form>
		<div class="well center">
			<h4>تا الآن : 10$ <i class="fa fa-circle-o-notch fa-spin fa-lg fa-fw"></i></h4>
		</div>
</aside>