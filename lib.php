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
 * Main function library for plugin.
 *
 * @package    availability_capability
 * @author     Andrew Madden <andrewmadden@didasko-online.com>
 * @copyright  2025 Didasko Online
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Code to run before page loads.
 *
 * @return void
 * @throws coding_exception
 */
function availability_capability_before_http_headers() {
    global $PAGE;

    $PAGE->requires->css('/availability/condition/capability/styles.css');
}
