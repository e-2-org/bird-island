<?php
/**
 * processForm.php
 * bird island form processor
 */
ob_start();
require_once("includes/validateEmailFormat.php");
//<input type="HIDDEN" name="required" value="name,country,submit_by">
$ok = true;
if (isEmpty("name") || isEmpty("country") || isEmpty("submit_by") || !validateEmailFormat($_POST["submit_by"])|| badEmail()) {
    $ok = false;
}
//<input type="HIDDEN" name="data_order" value="name,country,submit_by,choose_villa,choose_plot,rental_programme,weeks, own_home,second_home,number_holidays,holiday_bahamas,why_interested">
$fields = array();
$fields[] = array("name" => cleanUp("name"));
$fields[] = array("country" => cleanUp("country"));
$fields[] = array("submit_by" => cleanUp("submit_by"));
$fields[] = array("choose_villa" => cleanUp("choose_villa"));
$fields[] = array("choose_plot" => cleanUp("choose_plot"));
$fields[] = array("rental_programme" => cleanUp("rental_programme"));
$fields[] = array("weeks" => cleanUp("weeks"));
$fields[] = array("own_home" => cleanUp("own_home"));
$fields[] = array("second_home" => cleanUp("second_home"));
$fields[] = array("number_holidays" => cleanUp("number_holidays"));
$fields[] = array("holiday_bahamas" => cleanUp("holiday_bahamas"));
$fields[] = array("why_interested" => cleanUp("why_interested"));
// <input type="HIDDEN" name="submit_to" value="dream@bird-island.com,mail@e-2.org"> <input type="HIDDEN" name=
if ($ok) {
		require_once("includes/phpmailer/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->From = "dream@bird-island.com";
    $mail->FromName = "Bird Island";
    $mail->AddAddress($_POST["submit_by"]);
    $mail->AddReplyTo("dream@bird-island.com", "Bird Island");
    $mail->Subject = "Bird Island Villas";
    $mail->Body = "Thank you for registering your interest in Bird Island.\nWe look forward to discussing you requirements further, bringing your dream of paradise closer to becoming reality.\nOne of our representatives will be contacting you shortly.";
    if (!$mail->Send()) {
		    $ok = false;
    }
		$mail->ClearAddresses();
    $mail->From = "dream@bird-island.com";
    $mail->FromName = "Bird Island";
    $mail->AddAddress("mail@e-2.org");
    $mail->AddAddress("dream@bird-island.com");
    $mail->Subject = "Bird Island Registration";
		$msg = "The following information was submitted:\n";
    $msg .= "From Host: " . $_SERVER["REMOTE_ADDR"] . "\n";
		for ($i = 0; $i < count($fields); $i++) { 
		    foreach ($fields[$i] as $name => $val) {
		        $msg .= $name . " = " . $val . "\n";
		    }
		}
		$mail->Body = $msg;
		$mail->Send();
}
if ($ok) {
    header("Location: http://www.bird-island.com/thanks.html");
} else {
    header("Location: http://www.bird-island.com/oopsup.html");
}
function isEmpty($fieldname)
{
    return (isset($_POST[$fieldname]) && trim($_POST[$fieldname]) != '')? false: true;
}
function cleanUp($field)
{
		global $ok;
		if (!isset($_POST[$field]) || 
preg_match('/Content-Type:(\s*)multipart\/mixed/i', $_POST[$field])) {
		    $ok = false;
		    return '';
		} else {
		    return htmlentities(trim(strip_tags($_POST[$field])));
		}
}
function badEmail()
{
    if (stristr($_POST["submit_by"], "bird-island.com")) {
		    return true;
		}
		return false;
}
ob_end_flush();
?>
