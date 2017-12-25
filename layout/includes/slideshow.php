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
 *
 * @package     theme_eguru
 * @copyright   2015 LMSACE Dev Team,lmsace.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$numberofslides = theme_eguru_get_setting('numberofslides');

if ($numberofslides) { ?>

    <div class="homepage-carousel">
        <div id="homepageCarousel" class="carousel slide" data-ride="carousel">
        
        <ol class="carousel-indicators">
    <?php
    for($s = 0; $s < $numberofslides; $s++):
        $clstxt = ($s == "0") ? ' class="active"' : '';
    ?>
                <li data-target="#homepageCarousel" data-slide-to="<?php echo $s; ?>" <?php echo $clstxt; ?>></li>
    <?php 
    endfor;
    ?>
        </ol>
      
        <div class="carousel-inner" role="listbox">
    <?php 
    for($s1 = 1; $s1 <= $numberofslides; $s1++):
        $clstxt2 = ($s1 == "1") ? ' active' : '';
        $slidecaption = theme_eguru_get_setting('slide' . $s1 . 'caption', true);
        $slideurl = theme_eguru_get_setting('slide' . $s1 . 'url');
        $slideurltext = theme_eguru_get_setting('slide' . $s1 . 'urltext');
        $slideimg = theme_eguru_render_slideimg($s1, 'slide' . $s1 . 'image');
        $slidecaption = theme_eguru_lang($slidecaption);
        $slideurltext = theme_eguru_lang($slideurltext);
    ?>
                <div class="item<?php echo $clstxt2; ?>" style="background-image: url(<?php echo $slideimg; ?>);">
                	<div class="container-fluid item-inner-wrap">
                        <div class="carousel-content">
                            <h2><?php echo $slidecaption; ?></h2>
                            <div class="carousel-btn">
                             <a href="<?php echo $slideurl; ?>">
							 <?php echo $slideurltext; ?>
                             <i class="fa fa-arrow-left"></i><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
    <?php
    endfor;
    ?>
          
        </div>
         
        <a class="left carousel-control" href="#homepageCarousel" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
        <a class="right carousel-control" href="#homepageCarousel" data-slide="next"><i class="fa fa-chevron-right"></i></a>
        
        </div>
    </div>
    <!--E.O.Slider-->    

<?php 
}