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
 * Manage the condition.
 *
 * @package    availability_capability
 * @author     Andrew Madden <andrewmadden@didasko-online.com>
 * @copyright  2025 Didasko Online
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_capability;

use coding_exception;
use core_availability\info;

class condition extends \core_availability\condition {

    private array $capabilities;

    private \stdClass $structure;

    /**
     * Constructor.
     *
     * @param \stdClass $structure Data from JSON object.
     * @throws coding_exception
     */
    public function __construct(\stdClass $structure) {
        $this->structure = $structure;
        if (isset($structure->capabilities)) {
            $this->capabilities = $structure->capabilities;
        } else {
            throw new coding_exception("No capabilities defined for capability condition");
        }
    }

    /**
     *  Determines whether a particular item is currently available
     *  according to this availability condition.
     *
     * @param bool $not Set true if we are inverting the condition.
     * @param info $info Item we're checking.
     * @param bool $grabthelot Performance hint: if true, caches information
     *   required for all course-modules, to make the front page and similar
     *   pages work more quickly (works only for current user)
     * @param int $userid User ID to check availability for.
     * @return bool True if available.
     */
    public function is_available($not, info $info, $grabthelot, $userid) {
        // Allow inverting the condition.
        if ($not) {
            $available = false;
        } else {
            $available = true;
        }
        foreach ($this->capabilities as $capability) {
            if (!has_capability($capability, $info->get_context(), $userid)) {
                return !$available;
            }
        }
        return $available;
    }

    /**
     *  Obtains a string describing this restriction (whether or not
     *  it actually applies). Used to obtain information that is displayed to
     *  students if the activity is not available to them, and for staff to see
     *  what conditions are.
     *
     * @param bool $full Set true if this is the 'full information' view.
     * @param bool $not Set true if we are inverting the condition.
     * @param info $info Item we're checking.
     * @return string Information string (for admin) about all restrictions on
     *   this item.
     */
    public function get_description($full, $not, info $info) {
        $capabilitieslist = implode(', ', $this->capabilities); // There is always at least one.
        if ($not) {
            return get_string('capabilities_incorrect', 'availability_capability', $capabilitieslist);
        }
        return get_string('capabilities_required', 'availability_capability', $capabilitieslist);
    }

    /**
     * Obtains a representation of the options of this condition as a string,
     * for debugging.
     *
     * @return string Text representation of parameters
     */
    protected function get_debug_string() {
        return 'capabilities: ' . implode(', ', $this->capabilities);
    }

    /**
     * Saves tree data back to a structure object.
     *
     * @return \stdClass Structure object (ready to be made into JSON format)
     */
    public function save() {
        return $this->structure; // No need to alter the data;
    }
}
