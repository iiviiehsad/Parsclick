<!DOCTYPE html><title></title>
<?php
/*
require_once '../includes/initialize.php';

$body = '
<p>MAILER-DAEMON: DELIVERY HAS PERMANENTLY FAILED TO THIS RECIPIENT OR DELIVERY LIST.</p>
<p>An error occurred while trying to deliver this message to the recipient\'s e-mail address. Microsoft Exchange will not try to redeliver this message for you. User does not exist.</p>
<p>Sent by Microsoft Exchange Server 2007</p>
<p>Diagnostic information for administrators:</p>
<p>Generating server: corporate.dmgt.net</p>
<p>HSJFK-EXWT-W0V1V-S.corporate.bzqt.net #550 ... No such user ##</p>
<p>Original message headers:</p>
<p>Received: from info@javan-restaurant.co.uk (10.181.104.248) by</p>
<p>HSJFK-EXWT-W0V1V-S.corporate.dzzgt.net (10.195.2.137) with Microsoft SMTP Server</p>
<p>(TLS) id 8.3.274.0;</p>
<p>Received: from [208.93.140.190:44031] by server-5.bzgmt-5.meqlabs.com id</p>
<p>FB/48-17252-2A7230FG4;</p>
<p>X-Msg-Ref: server-136.tower-425.meqlabs.com!345832498624!11005757!1</p>
<p>X-Originating-IP: [184.154.123.70]</p>
<p>X-SpamReason: No, hits=1.2 required=7.0 tests=HTML_20_30,HTML_MESSAGE,</p>
<p>RCVD_BY_IP</p>
<p>X-StarScan-Version: 6.4.3; banners=-,-</p>
<p>X-VirusChecked: Checked</p>
<p>Received: (qmail 22800 invoked from network);</p>
<p>Received: from mail-hw0-f34.gaole.com (HELO mail-hw0-f34.gaole.com)</p>
<p>(289.25.986.44) by server-136.tower-425.meqlabs.com with RH6-ZRA</p>
<p>encrypted SMTP;</p>
<p>Received: by qfadb12 with SMTP id c14sb9234843qxh.20</p>
<p>Received: by 173.193.156.78 with SMTP id fdsalkf293238hj2.30.11349182363705;</p>
<p>MIME-Version: 1.0</p>
<p>Received: by 174.120.168.2195with HTTP;</p>
<p>Content-Type: multipart/alternative; boundary="20cf385fc2b644bb8314b59ggbc0"</p>
<p>------------------------------------------------------------------------------</p>
';
$originalBody = '
Javan Restaurant
291-293 King Street
Hammersmith
London
W6 9NH
Email: info@javan-restaurant.co.uk                                Date:09 January 2017                                                                                                     
PRE COURT ACTION                                     RIGHTS INFRINGEMENT CASE:  29817
 PREVIOUS LETTER DATES: 24/10/2016
 AMOUNT DUE:£1550.00
 IMAGE(S) NUMBER(S):00851212
 DEADLINE FOR CLEARED FUNDS:23/01/2017
Dear Javan Restaurant,
StockFood Ltd has previously written to you offering an out of court settlement for the unauthorised use of Rights-Managed imagery represented exclusively by StockFood. While we have tried to communicate with you several times, you have failed to settle this case and unfortunately to date this matter remains unresolved.
StockFood Ltd have provided extensive information regarding the matter as well as significant deadline extensions in order to allow you to familiarise yourself with copyright liability or seek appropriate legal advice.
We are offering you a final opportunity to accept our settlement offer as outlined above. Full payment, along with the removal of the imagery will be considered full and final settlement of the case. An invoice marked paid and including confirmation that the issue has been resolved will be provided on receipt of full payment.
Please remit payment and quote your case number using one of the following methods:
Bank Transfer:
National Westminster Bank, StockFood Ltd
Bank Account: 39276848 - Sort Code: 600001
Swift Code: NWBKGB2L - IBAN:GB70NWBK60000 139276848
Debit or Credit card:
Please phone 020 7438 1220 in order to make a payment
Cheques may be sent to StockFood Ltd at:
Signet House, 49-51 Farringdon Road, London, EC1M 3JP.
Please allow extra time for a cheque payment to clear.
If no settlement is reached, we will be left no other option to file a claim in the small claims court of the Intellectual Property Enterprise Court in London. This will result in further significant charges.
StockFood Ltd is committed to investigating licensing infractions not only to protect our interests, but also to protect the interests of the professional photographers whom we represent. We must enforce our licensing conditions rigorously and feel that your cooperation regarding this matter should be promptly forthcoming. This letter is without prejudice to StockFood’s rights and remedies, all of which are expressly reserved.
Yours sincerely,
Z. Khan
Rights Control Associate
Telephone: 020 7438 1228 / 62
Best Wishes
Zahra Khan
Rights Control
StockFood Ltd.
+44 (20) 7438 1262
zahra.khan@stockfood.co.uk
www.stockfood.co.uk
SF_11_2016
Signet House, 49-51 Farringdon Road | London EC1M 3JP | United Kingdom
+44 (20) 7438 1220 | welcome@stockfood.co.uk | www.stockfood.co.uk
Place of registration: England and Wales | Company No.: 5951778
VAT No.: 898874134
';

$mailer = new PHPMailer();

$mailer->isSMTP();
$mailer->isHTML(FALSE);
$mailer->addBCC('hazz.azimi@gmail.com', 'MAILER DAEMON');
$mailer->CharSet    = 'UTF-8';
$mailer->Host       = SMTP;
$mailer->SMTPSecure = TLS;
$mailer->Port       = PORT;
$mailer->SMTPAuth   = TRUE;
$mailer->Username   = EMAILUSER;
$mailer->Password   = EMAILPASS;
$mailer->FromName   = 'Mail Delivery Subsystem';
$mailer->From       = 'mailer-daemon@googlemail.com';
$mailer->Subject    = 'Delivery Status Notification (Failure)';
$mailer->Body       = $body . $originalBody;

if ($mailer->send()) echo 'OK';
else echo 'NOT OK!';
*/
?>