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
 * lib.php
 *
 * This is built using the boost template to allow for new theme's using
 * Moodle's new Boost theme engine
 *
 * @package     theme_eguru
 * @copyright   2015 LMSACE Dev Team, lmsace.com
 * @author      LMSACE Dev Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Eguru main scss contents.
 * @param string $theme
 * @return string
 */
function theme_eguru_get_main_scss_content($theme) {
    global $CFG, $OUTPUT, $PAGE;

    $scss = '';
    $parentconfig = theme_config::load('boost');
    $scss .= theme_boost_get_main_scss_content($parentconfig);

    $themescssfile = $CFG->dirroot.'/theme/eguru/scss/preset/eguru.scss';
    if (file_exists($themescssfile)) {
        $scss .= file_get_contents($themescssfile);
    }
    /*--color_scheme--*/
    $patterns = theme_eguru_get_setting('patternselect');

    if (!empty($patterns)) {
        $filename = 'color_scheme-'.$patterns.'.scss';
    } else {
        $filename = 'color_scheme-default.scss';
    }
    if ($filename == 'color_scheme-1.scss') {
        $scss .= file_get_contents($CFG->dirroot.'/theme/eguru/scss/preset/color_scheme-1.scss');

    } else if ($filename == 'color_scheme-2.scss') {
        $scss .= file_get_contents($CFG->dirroot.'/theme/eguru/scss/preset/color_scheme-2.scss');

    } else if ($filename == 'color_scheme-3.scss') {
        $scss .= file_get_contents($CFG->dirroot.'/theme/eguru/scss/preset/color_scheme-3.scss');

    } else if ($filename == 'color_scheme-4.scss') {
        $scss .= file_get_contents($CFG->dirroot.'/theme/eguru/scss/preset/color_scheme-4.scss');

    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/eguru/scss/preset/color_scheme-default.scss');
    }
    return $scss;
}

/**
 * override the scss values with variables.
 * @return string
 */
function theme_eguru_get_pre_scss() {
    global $CFG;

    $scss = '';
    $scss = theme_eguru_set_fontwww();
    return $scss;
}

/**
 * Get extra scss from settings.
 * @param string $theme
 * @return string
 */
function theme_eguru_get_extra_scss($theme) {
    return !empty($theme->settings->customcss) ? $theme->settings->customcss : '';
}

/**
 * Page init functions runs every time page loads.
 * @param moodle_page $page
 * @return null
 */
function theme_eguru_page_init(moodle_page $page) {
    $page->requires->jquery();
    $page->requires->js('/theme/eguru/javascript/theme.js');
}

/**
 * Loads the CSS Styles and replace the background images.
 * If background image not available in the settings take the default images.
 *
 * @param string $css
 * @param string $theme
 * @return string $css
 */
function theme_eguru_process_css($css, $theme) {
    // Set the background image for the logo.
    $logo = $theme->setting_file_url('logo', 'logo');

    // Set custom CSS.
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = theme_eguru_pre_css_set_fontwww($css);

    return $css;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_eguru_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    static $theme;

    if (empty($theme)) {
        $theme = theme_config::load('eguru');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        } else if ($filearea === 'footerlogo') {
            return $theme->setting_file_serve('footerlogo', $args, $forcedownload, $options);
        } else if ($filearea === 'style') {
            theme_eguru_serve_css($args[1]);
        } else if ($filearea === 'pagebackground') {
            return $theme->setting_file_serve('pagebackground', $args, $forcedownload, $options);
        } else if (preg_match("/slide[1-9][0-9]*image/", $filearea) !== false) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

/**
 * Serves CSS for image file updated to styles.
 *
 * @param string $filename
 * @return string
 */
function theme_eguru_serve_css($filename) {
    global $CFG;
    if (!empty($CFG->themedir)) {
        $thestylepath = $CFG->themedir . '/eguru/style/';
    } else {
        $thestylepath = $CFG->dirroot . '/theme/eguru/style/';
    }
    $thesheet = $thestylepath . $filename;

    /* http://css-tricks.com/snippets/php/intelligent-php-cache-control/ - rather than /lib/csslib.php as it is a static file who's
      contents should only change if it is rebuilt.  But! There should be no difference with TDM on so will see for the moment if
      that decision is a factor. */

    $etagfile = md5_file($thesheet);
    // File.
    $lastmodified = filemtime($thesheet);
    // Header.
    $ifmodifiedsince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
    $etagheader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

    if ((($ifmodifiedsince) && (strtotime($ifmodifiedsince) == $lastmodified)) || $etagheader == $etagfile) {
        theme_eguru_send_unmodified($lastmodified, $etagfile);
    }
    theme_eguru_send_cached_css($thestylepath, $filename, $lastmodified, $etagfile);
}

/**
 * Set browser cache used in php header.
 * @param string $lastmodified
 * @param string $etag
 *
 */
function theme_eguru_send_unmodified($lastmodified, $etag) {
    $lifetime = 60 * 60 * 24 * 60;
    header('HTTP/1.1 304 Not Modified');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Content-Type: text/css; charset=utf-8');
    header('Etag: "' . $etag . '"');
    if ($lastmodified) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    }
    die;
}

/**
 * Cached css.
 * @param string $path
 * @param string $filename
 * @param integer $lastmodified
 * @param string $etag
 */
function theme_eguru_send_cached_css($path, $filename, $lastmodified, $etag) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/configonlylib.php');
    // For min_enable_zlib_compression.
    // 60 days only - the revision may get incremented quite often.
    $lifetime = 60 * 60 * 24 * 60;

    header('Etag: "' . $etag . '"');
    header('Content-Disposition: inline; filename="'.$filename.'"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Pragma: ');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Accept-Ranges: none');
    header('Content-Type: text/css; charset=utf-8');
    if (!min_enable_zlib_compression()) {
        header('Content-Length: ' . filesize($path . $filename));
    }

    readfile($path . $filename);
    die;
}

/**
 * Returns an object containing HTML for the areas affected by settings.
 *
 * Do not add Clean specific logic in here, child themes should be able to
 * rely on that function just by declaring settings with similar names.
 *
 * @param renderer_base $output Pass in $OUTPUT.
 * @param moodle_page $page Pass in $PAGE.
 * @return stdClass An object with the following properties:
 *      - navbarclass A CSS class to use on the navbar. By default ''.
 *      - heading HTML to use for the heading. A logo if one is selected or the default heading.
 *      - footnote HTML to use as a footnote. By default ''.
 */
function theme_eguru_get_html_for_settings(renderer_base $output, moodle_page $page) {
    global $CFG;
    $return = new stdClass;

    $return->navbarclass = '';
    if (!empty($page->theme->settings->invert)) {
        $return->navbarclass .= ' navbar-inverse';
    }

    if (!empty($page->theme->settings->logo)) {
        $return->heading = html_writer::link($CFG->wwwroot, '', array('title' => get_string('home'), 'class' => 'logo'));
    } else {
        $return->heading = $output->page_heading();
    }

    $return->footnote = '';
    if (!empty($page->theme->settings->footnote)) {
        $return->footnote = '<div class="footnote text-center">'.format_text($page->theme->settings->footnote).'</div>';
    }

    return $return;
}

/**
 * Loads the CSS Styles and put the font path
 *
 * @return string $fontwww
 */
function theme_eguru_set_fontwww() {
    global $CFG, $PAGE;

    $themewww = $CFG->wwwroot."/theme";
    $theme = theme_config::load('eguru');
    $fontwww = '$fontwww: "'. $themewww.'/eguru/fonts/"'.";\n";
    return $fontwww;
}

/**
 * Add font folder path into css file using moodle pre css method.
 * @param string $css
 * @return string
 */
function theme_eguru_pre_css_set_fontwww($css) {
    global $CFG, $PAGE;

    $themewww = $CFG->wwwroot."/theme";
    $tag = '[[setting:fontwww]]';
    $theme = theme_config::load('eguru');
    $css = str_replace($tag, $themewww.'/eguru/fonts/', $css);
    return $css;
}

/**
 * Logo Image URL Fetch from theme settings
 *
 * @param string $type
 * @return image $logo
 */
function theme_eguru_get_logo_url($type='header') {
    global $OUTPUT;
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('eguru');
    }

    if ($type == "header") {
        $logo = $theme->setting_file_url('logo', 'logo');
        $logo = empty($logo) ? $OUTPUT->image_url('home/logo', 'theme') : $logo;
    } else if ($type == "footer") {
        $logo = $theme->setting_file_url('footerlogo', 'footerlogo');
        $logo = empty($logo) ? $OUTPUT->image_url('home/footerlogo', 'theme') : $logo;
    }
    return $logo;
}

/**
 * Renderer the slider images.
 * @param integer $p
 * @param string $sliname
 * @return null
 */
function theme_eguru_render_slideimg($p, $sliname) {
    global $PAGE, $OUTPUT;

    $nos = theme_eguru_get_setting('numberofslides');
    $i = $p % 3;
    $slideimage = $OUTPUT->image_url('home/slide'.$i, 'theme');

    // Get slide image or fallback to default.
    if (theme_eguru_get_setting($sliname)) {
        $slideimage = $PAGE->theme->setting_file_url($sliname , $sliname);
    }
    return $slideimage;
}

/**
 * Functions helps to get the admin config values which are related to the
 * theme
 * @param array $setting
 * @param bool $format
 * @return bool
 */
function theme_eguru_get_setting($setting, $format = true) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/weblib.php');
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('eguru');
    }
    if (empty($theme->settings->$setting)) {
        return false;
    } else if (!$format) {
        return $theme->settings->$setting;
    } else if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    } else if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    } else {
        return format_string($theme->settings->$setting);
    }
}

/**
 * Return the current theme url
 *
 * @return string
 */
function theme_eguru_theme_url() {
    global $CFG, $PAGE;
    $themeurl = $CFG->wwwroot.'/theme/'. $PAGE->theme->name;
    return $themeurl;
}

/**
 * Display Footer Block Custom Links
 * @param string $menuname Footer block link name.
 * @return string The Footer links are return.
 */
function theme_eguru_generate_links($menuname = '') {
    global $CFG, $PAGE;
    $htmlstr = '';
    $menustr = theme_eguru_get_setting($menuname);
    $menusettings = explode("\n", $menustr);
    foreach ($menusettings as $menukey => $menuval) {
        $expset = explode("|", $menuval);
        if (!empty($expset) && isset($expset[0]) && isset($expset[1])) {
            list($ltxt, $lurl) = $expset;
            $ltxt = trim($ltxt);
            $ltxt = theme_eguru_lang($ltxt);
            $lurl = trim($lurl);
            if (empty($ltxt)) {
                continue;
            }
            if (empty($lurl)) {
                $lurl = 'javascript:void(0);';
            }

            $pos = strpos($lurl, 'http');
            if ($pos === false) {
                $lurl = new moodle_url($lurl);
            }
            $htmlstr .= '<li><a href="'.$lurl.'">'.$ltxt.'</a></li>'."\n";
        }
    }
    return $htmlstr;
}

/**
 * Fetch the hide course ids
 *
 * @return array
 */
function theme_eguru_hidden_courses_ids() {
    global $DB;
    $hcourseids = array();
    $result = $DB->get_records_sql("SELECT id FROM {course} WHERE visible='0' ");
    if (!empty($result)) {
        foreach ($result as $row) {
            $hcourseids[] = $row->id;
        }
    }
    return $hcourseids;
}

/**
 * Remove the html special tags from course content.
 * This function used in course home page.
 *
 * @param string $text
 * @return string
 */
function theme_eguru_strip_html_tags( $text ) {
    $text = preg_replace(
        array(
            // Remove invisible content.
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            // Add line breaks before and after blocks.
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text
    );
    return strip_tags( $text );
}

/**
 * Cut the Course content.
 *
 * @param string $str
 * @param integer $n
 * @param char $end_char
 * @return string $out
 */
function theme_eguru_course_trim_char($str, $n = 500, $endchar = '&#8230;') {
    if (strlen($str) < $n) {
        return $str;
    }

    $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
    if (strlen($str) <= $n) {
        return $str;
    }

    $out = "";
    $small = substr($str, 0, $n);
    $out = $small.$endchar;
    return $out;
}

/**
 * Returns the language values from the given lang string or key.
 * @param string $key
 * @return string
 */
function theme_eguru_lang($key = '') {
    $pos = strpos($key, 'lang:');
    if ($pos !== false) {
        list($l, $k) = explode(":", $key);
        if (get_string_manager()->string_exists($k, 'theme_eguru')) {
            $v = get_string($k, 'theme_eguru');
            return $v;
        } else {
            return $key;
        }
    } else {
        return $key;
    }
}