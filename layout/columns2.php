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
 * A two column layout for the boost theme.
 *
 * @package   theme_eguru
 * @copyright 2016 Damyon Wiese
 * @author    LMSACE Dev Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
require_once(dirname(__FILE__) .'/includes/themedata.php');

// Add-a-block in editing mode.
if (isset($PAGE->theme->addblockposition) &&
        $PAGE->user_is_editing() &&
        $PAGE->user_can_edit_blocks() &&
        $PAGE->pagelayout !== 'mycourses'
) {
    $url = new moodle_url($PAGE->url, ['bui_addblock' => '', 'sesskey' => sesskey()]);

    $block = new block_contents;
    $block->content = $OUTPUT->render_from_template('core/add_block_button',
        [
            'link' => $url->out(false),
            'escapedlink' => "?{$url->get_query_string(false)}",
            'pageType' => $PAGE->pagetype,
            'pageLayout' => $PAGE->pagelayout,
        ]
    );

    $PAGE->blocks->add_fake_block($block, BLOCK_POS_RIGHT);
}

$extraclasses = [];
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
echo (!empty($flatnavbar)) ? $flatnavbar : "";
$secondarynavigation = false;
if (!defined('BEHAT_SITE_RUNNING')) {
    $buildsecondarynavigation = $PAGE->has_secondary_navigation();
    if ($buildsecondarynavigation) {
        $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs');
        $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    }
}

$templatecontext += [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'secondarymoremenu' => $secondarynavigation,
];
echo $OUTPUT->render_from_template('theme_eguru/columns2', $templatecontext);