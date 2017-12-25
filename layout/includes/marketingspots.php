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
 * The maintenance layout.
 *
 * @package   theme_eguru
 * @copyright 2015 LMSACE Dev Team,lmsace.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Marketing Spot 1.
$mspot1icon = theme_eguru_get_setting('mspot1icon');
$msp1title = theme_eguru_get_setting('mspot1title', 'format_text');
$msp1title = theme_eguru_lang($msp1title);
$msp1desc = theme_eguru_get_setting('mspot1desc', 'format_text');
$msp1desc = theme_eguru_lang($msp1desc);
// Marketing Spot 2.
$mspot2icon = theme_eguru_get_setting('mspot2icon');
$msp2title = theme_eguru_get_setting('mspot2title', 'format_text');
$msp2title = theme_eguru_lang($msp2title);
$msp2desc = theme_eguru_get_setting('mspot2desc', 'format_text');
$msp2desc = theme_eguru_lang($msp2desc);
// Marketing Spot 3.
$mspot3icon = theme_eguru_get_setting('mspot3icon');
$msp3title = theme_eguru_get_setting('mspot3title', 'format_text');
$msp3title = theme_eguru_lang($msp3title);
$msp3desc = theme_eguru_get_setting('mspot3desc', 'format_text');
$msp3desc = theme_eguru_lang($msp3desc);
// Marketing Spot 4.
$mspot4icon = theme_eguru_get_setting('mspot4icon');
$msp4title = theme_eguru_get_setting('mspot4title', 'format_text');
$msp4title = theme_eguru_lang($msp4title);
$msp4desc = theme_eguru_get_setting('mspot4desc', 'format_text');
$msp4desc = theme_eguru_lang($msp4desc);
?>
<div class="custom-site-expo">
	<div class="container-fluid">
    	<div class="row-fluid">
            <div class="span3">
                <div class="ebox">
                    <div class="ebox-head">
                        <div class="rcthumb"><i class="fa fa-<?php echo $mspot1icon; ?>"></i></div>
                    </div>
                    <div class="ebox-body">
                    	<h6><?php echo $msp1title; ?></h6>
                        <p><?php echo $msp1desc; ?></p>
                    </div>
                </div>
            </div>
            <div class="span3">
                <div class="ebox">
                    <div class="ebox-head">
                        <div class="rcthumb"><i class="fa fa-<?php echo $mspot2icon; ?>"></i></div>
                    </div>
                    <div class="ebox-body">
                    	<h6><?php echo $msp2title; ?></h6>
                        <p><?php echo $msp2desc; ?></p>
                    </div>
                </div>
            </div>
            <div class="span3">
                <div class="ebox">
                    <div class="ebox-head">
                        <div class="rcthumb"><i class="fa fa-<?php echo $mspot3icon; ?>"></i></div>
                    </div>
                    <div class="ebox-body">
                    	<h6><?php echo $msp3title; ?></h6>
                        <p><?php echo $msp3desc; ?></p>
                    </div>
                </div>
            </div>
            <div class="span3">
                <div class="ebox">
                    <div class="ebox-head">
                        <div class="rcthumb"><i class="fa fa-<?php echo $mspot4icon; ?>"></i></div>
                    </div>
                    <div class="ebox-body">
                    	<h6><?php echo $msp4title; ?></h6>
                        <p><?php echo $msp4desc; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--E.O.custom-site-expo-->