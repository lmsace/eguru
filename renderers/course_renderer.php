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
 * course_renderer.php
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

require_once($CFG->dirroot . "/course/renderer.php");

/**
 * This class has function for core course renderer
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_eguru_core_course_renderer extends core_course_renderer {

    /**
     * Renderer function for the frontpage available courses.
     * @return string
     */
    public function frontpage_available_courses() {
        /* available courses */
        global $CFG, $OUTPUT;
        require_once($CFG->libdir. '/coursecatlib.php');

        $chelper = new coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->set_courses_display_options(array(
            'recursive' => true,
            'limit' => $CFG->frontpagecourselimit,
            'viewmoreurl' => new moodle_url('/course/index.php'),
            'viewmoretext' => new lang_string('fulllistofcourses')
        ));

        $chelper->set_attributes(array('class' => 'frontpage-course-list-all'));
        $courses = coursecat::get(0)->get_courses($chelper->get_courses_display_options());
        $totalcount = coursecat::get(0)->get_courses_count($chelper->get_courses_display_options());

        $rcourseids = array_keys($courses);
        $newcourse = get_string('availablecourses');

        $header = '<div id="frontpage-course-list">
        <h2>'.$newcourse.'</h2>
        <div class="courses frontpage-course-list-all">';

        $footer = '
        </div>
        </div>';

        $content = '';

        if (count($rcourseids) > 0) {
                $content .= '<div class="row">';
            foreach ($rcourseids as $courseid) {

                $rowcontent = '';

                    $course = get_course($courseid);

                    $no = get_config('theme_eguru', 'patternselect');
                    $nimgp = (empty($no)||$no == "default") ? 'cs00/no-image' : 'cs0'.$no.'/no-image';

                    $noimgurl = $OUTPUT->pix_url($nimgp, 'theme');
                    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid ));

                if ($course instanceof stdClass) {
                    require_once($CFG->libdir. '/coursecatlib.php');
                    $course = new course_in_list($course);
                }

                $imgurl = '';
                $context = context_course::instance($course->id);

                foreach ($course->get_course_overviewfiles() as $file) {
                    $isimage = $file->is_valid_image();
                    $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                        '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                    if (!$isimage) {
                        $imgurl = $noimgurl;
                    }
                }

                if (empty($imgurl)) {
                    $imgurl = $noimgurl;
                }

                $rowcontent .= '<div class="col-md-3 col-sm-6">
                    <div class="fp-coursebox">
                    <div class="fp-coursethumb">
                    <a href="'.$courseurl.'">
                    <img src="'.$imgurl.'" width="243" height="165" alt="'.$course->fullname.'">
                    </a>
                    </div>
                    <div class="fp-courseinfo">
                    <h5><a href="'.$courseurl.'">'.$course->fullname.'</a></h5>
                    </div>
                    </div>
                    </div>';
                $content .= $rowcontent;
            }
                $content .= '</div>';
        }

        $coursehtml = $header.$content.$footer;
        echo $coursehtml;

        if (!$totalcount && !$this->page->user_is_editing() && has_capability('moodle/course:create', context_system::instance())) {
            // Print link to create a new course, for the 1st available category.
            echo $this->add_new_course_button();
        }
    }

    /**
     * Promoted courses.
     * @return string
     */
    public function promoted_courses() {
        global $CFG, $OUTPUT, $DB;

        $pcourseenable = theme_eguru_get_setting('pcourseenable');
        if (!$pcourseenable) {
            return false;
        }

        $featuredcontent = '';
        /* Get Featured courses id from DB */
        $featuredids = theme_eguru_get_setting('promotedcourses');
        $rcourseids = (!empty($featuredids)) ? explode(",", $featuredids) : array();
        if (empty($rcourseids)) {
            return false;
        }

        $hcourseids = theme_eguru_hidden_courses_ids();

        if (!empty($hcourseids)) {
            foreach ($rcourseids as $key => $val) {
                if (in_array($val, $hcourseids)) {
                    unset($rcourseids[$key]);
                }
            }
        }

        foreach ($rcourseids as $key => $val) {
            $ccourse = $DB->get_record('course', array('id' => $val));
            if (empty($ccourse)) {
                unset($rcourseids[$key]);
                continue;
            }
        }

        if (empty($rcourseids)) {
            return false;
        }

        $fcourseids = array_chunk($rcourseids, 6);
        $totalfcourse = count($fcourseids);
        $promotedtitle = theme_eguru_get_setting('promotedtitle', 'format_text');
        $promotedtitle = theme_eguru_lang($promotedtitle);

        $featuredheader = '<div class="custom-courses-list" id="Promoted-Courses">
							  <div class="container">
								<div class="titlebar with-felements">
									<h2>'.$promotedtitle.'</h2>
									<div class="slidenav pagenav">
										<button class="nav-item nav-prev slick-prev">
										<i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i>
										</button>
										<button class="nav-item nav-next slick-next">
										<i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i>
										</button>
										<div class="clearfix"></div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="promoted_courses" data-crow="'.$totalfcourse.'">';

        $featuredfooter = ' </div>
                            </div>
                            </div>';

        if (!empty($fcourseids)) {
            foreach ($fcourseids as $courseids) {
                $rowcontent = '<div><div class="row">';

                foreach ($courseids as $courseid) {
                    $course = get_course($courseid);
                    $no = get_config('theme_eguru', 'patternselect');
                    $nimgp = (empty($no)|| $no == "default") ? 'cs00/no-image' : 'cs0'.$no.'/no-image';

                    $noimgurl = $OUTPUT->pix_url($nimgp, 'theme');

                    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid ));

                    if ($course instanceof stdClass) {
                        require_once($CFG->libdir. '/coursecatlib.php');
                        $course = new course_in_list($course);
                    }

                    $imgurl = '';

                    $summary = theme_eguru_strip_html_tags($course->summary);
                    $summary = theme_eguru_course_trim_char($summary, 75);

                    $context = context_course::instance($course->id);
                    $nostudents = count_role_users(5, $context);

                    foreach ($course->get_course_overviewfiles() as $file) {
                        $isimage = $file->is_valid_image();
                        $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                        '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                        if (!$isimage) {
                            $imgurl = $noimgurl;
                        }
                    }
                    if (empty($imgurl)) {
                        $imgurl = $noimgurl;
                    }
                        $coursehtml = '<div class="col-md-2">
                            <div class="course-box">
                            <div class="thumb"><a href="'.$courseurl.'">
							<img src="'.$imgurl.'" width="135" height="135" alt="'.$course->fullname.'"></a></div>
                            <div class="info">
                            <h5><a href="'.$courseurl.'">'.$course->fullname.'</a></h5>
                            </div>
                            </div>
                            </div>';

                        $rowcontent .= $coursehtml;
                }
                    $rowcontent .= '</div></div>';
                    $featuredcontent .= $rowcontent;
            }
        }
        $featuredcourses = $featuredheader.$featuredcontent.$featuredfooter;
        return $featuredcourses;
    }
}