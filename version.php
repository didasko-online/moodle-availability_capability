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
 * Define plugin version details.
 *
 * @package    availability_capability
 * @author     Andrew Madden <andrewmadden@didasko-online.com>
 * @copyright  2025 Didasko Online
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'availability_capability';
$plugin->version = 2025060400;
$plugin->requires = 2022041900; // Moodle 4.1+
$plugin->maturity = MATURITY_ALPHA;
$plugin->release = '1.0';
