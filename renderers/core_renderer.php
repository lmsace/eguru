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
 * This is built using the bootstrapbase template to allow for new theme's using
 * Moodle's new Bootstrap theme engine
 *
 * @package     theme_eguru
 * @copyright   2015 LMSACE Dev Team, lmsace.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class theme_eguru_core_renderer extends theme_bootstrapbase_core_renderer {

    public function earlier_user_menu() {
        global $USER, $CFG, $OUTPUT;

        if ($CFG->branch > "27") {
            return '';
        }
        $uname = fullname($USER, true);
        $dlink = new moodle_url("/my");
        $plink = new moodle_url("/user/profile.php", array("id" => $USER->id));
        $lo = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
		$dashboard = get_string('myhome');
		$profile = get_string('profile');
		$logout = get_string('logout');

        $content = '<li class="dropdown no-divider">
        <a class="dropdown-toggle"
        data-toggle="dropdown"
        href="#">
        '.$uname.'
        <i class="fa fa-chevron-down"></i><span class="caretup"></span>
        </a>
        <ul class="dropdown-menu">
        <li><a href="'.$dlink.'">'.$dashboard.'</a></li>
        <li><a href="'.$plink.'">'.$profile.'</a></li>
        <li><a href="'.$lo.'">'.$logout.'</a></li>
        </ul>
        </li>';

        return $content;
    }

}
