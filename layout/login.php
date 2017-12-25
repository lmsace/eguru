<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The one column layout.
 *
 * @package   theme_eguru
 * @copyright 2015 LMSACE Dev Team,lmsace.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Get the HTML for the settings bits.
$html = theme_eguru_get_html_for_settings($OUTPUT, $PAGE);

if (empty($frm->username) && $authsequence[0] != 'shibboleth') {  // See bug 5184
    if (!empty($_GET["username"])) {
        $frm->username = clean_param($_GET["username"], PARAM_RAW); // we do not want data from _POST here
    } else {
        $frm->username = get_moodle_cookie();
    }

    $frm->password = "";
}

if (isloggedin() && !isguestuser()) {
    redirect ($CFG->wwwroot);
}
if (empty($CFG->authloginviaemail)) {
    $strusername = get_string('username');
} else {
    $strusername = get_string('usernameemail');
}
echo $OUTPUT->doctype(); ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
	#site-footer { padding: 0px !important; }
	</style>
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

<?php echo "<div style='display: none;'>".$OUTPUT->main_content()."</div>";  ?>

<div class="custom-login-page">
	<div class="container-fluid">

        <div class="loginbox">
            <h3><?php print_string('loginheader', 'theme_eguru') ?></h3>
            <div class="formwrap">
            	<form action="<?php echo $CFG->httpswwwroot; ?>/login/index.php" method="post" id="login1" >
                    <div class="alert alert-error" id="lemsg" style="display:none;">
                      &nbsp;
                    </div>
                	<div class="form-fields">
                    	<label><?php echo($strusername) ?></label>
                        <div class="textbox-wrap">
                        	<input type="text" name="username"  value="<?php p($frm->username) ?>" />
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                	<div class="form-fields">
                    	<label><?php print_string("password") ?></label>
                        <div class="textbox-wrap">
	                        <input type="password" name="password" value=""/>
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                    <div class="support-fields visible-phone">
                        <p><label class="checkbox"><input type="checkbox" > <?php print_string('rememberusername', 'admin') ?>
                        </label></p>
                        <p><a href="<?php echo new moodle_url("/login/forgot_password.php"); ?>">
						<?php print_string("forgotten") ?></a></p>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-action">
                    	<label class="hidden-phone">&nbsp;</label>
                    	<input type="submit" id="loginbtn1" value="<?php echo get_string("login"); ?>">
                    </div>
                    <div class="clearfix"></div>
                    <div class="support-fields visible-tablet visible-desktop">
                    	<p><a href="<?php echo new moodle_url("/login/forgot_password.php"); ?>">
						<?php print_string("forgotten") ?></a></p>
                        <p><label class="checkbox"><input type="checkbox" name="rememberusername" value="1"  <?php if ($frm->username) {echo 'checked="checked"';} ?>/>
						<?php print_string('rememberusername', 'admin') ?></label></p>
	                    <div class="clearfix"></div>
                    </div>
                </form>
                <!-- Guest Access Start -->
                <?php if ($CFG->guestloginbutton and !isguestuser()) {  ?>
                    <form action="index.php" method="post" id="guestlogin">
                        <div class="form-action">
                            <input type="hidden" name="username" value="guest" />
                            <input type="hidden" name="password" value="guest" />
                            <input type="submit" value="<?php print_string("loginguest") ?>" />
                        </div>
                    </form>
<?php
}
?>
                <!-- Guest Access E.O -->
            </div>
        </div>

    </div>
</div>

<script>
$(function(){
	var e1 = $("#loginerrormessage").text();
	if(e1.length>0)
	{
		$("#lemsg").html(e1);
		$("#lemsg").show();
	}
	$("#loginbtn").click(function(){
		var uname = $("#login1 input[name=username]").val();
		$("#login input[name=username]").val(uname);

		var pwd = $("#login1 input[name=password]").val();
		$("#login input[name=password]").val(pwd);
		$("#login").submit();
	});
});
</script>

<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
</body>
</html>
