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
 * Define the data served to the frontend scripts.
 *
 * @package    availability_capability
 * @author     Andrew Madden <andrewmadden@didasko-online.com>
 * @copyright  2025 Didasko Online
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_capability;

class frontend extends \core_availability\frontend {

    /**
     *  Gets a list of string identifiers (in the plugin's language file) that
     *  are required in JavaScript for this plugin. The default returns nothing.
     *
     * @return string[]
     */
    protected function get_javascript_strings() {
        return [
            'description',
            'title',
        ];
    }

    /**
     * Gets additional parameters for the plugin's initInner function.
     *
     * @param $course
     * @param \cm_info|null $cm
     * @param \section_info|null $section
     * @return array|int[]|string[]
     */
    protected function get_javascript_init_params($course, \cm_info $cm = null, \section_info $section = null) {
        // This should be cached so not too onerous.
        $capabilities = get_all_capabilities();
        return array_keys($capabilities);
    }
}
