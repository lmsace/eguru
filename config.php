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
 * config.php
 *
 * This is built using the boost template to allow for new theme's using
 * Moodle's new Boost theme engine
 *
 * @package     theme_eguru
 * @copyright   2015 LMSACE Dev Team, lmsace.com
 * @author      LMSACE Dev Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();

$THEME->name = 'eguru';

$THEME->sheets = [];

$THEME->editor_sheets = [];

$THEME->parents = ['boost'];

$THEME->enable_dock = false;

$THEME->yuicssmodules = array();

$THEME->rendererfactory = 'theme_overridden_renderer_factory';

$THEME->requiredblocks = '';

$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;

$THEME->prescsscallback = 'theme_eguru_get_pre_scss';

$THEME->extrascsscallback = 'theme_eguru_get_extra_scss';

$THEME->csstreepostprocessor = 'theme_eguru_css_tree_post_processor';

$THEME->scss = function($theme) {
    return theme_eguru_get_main_scss_content($theme);
};

$no = get_config('theme_eguru', 'patternselect'); // Selected theme pattern no returned.

$THEME->csspostprocess = 'theme_eguru_process_css';

$THEME->layouts = array(
    // Most backwards compatible layout without the blocks - this is the layout used by default.
    'base' => array(
        'file' => 'columns1.php',
        'regions' => array(),
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'frontpage.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true),
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array(),
        'options' => array('langmenu' => true),
    ),
);

$THEME->blockrtlmanipulations = array(
    'side-pre' => 'side-post',
    'side-post' => 'side-pre'
);