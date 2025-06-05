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
 * Test the condition class.
 *
 * @package    availability_capability
 * @author     Andrew Madden <andrewmadden@didasko-online.com>
 * @copyright  2025 Didasko Online
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_capability;

global $CFG;

require_once($CFG->dirroot . '/availability/tests/fixtures/mock_info_module.php');

use context_system;
use core_availability\mock_info_module;

class condition_test extends \advanced_testcase {

    /**
     * Sets up the test environment before each test.
     *
     * @return void
     */
    protected function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Tests that the condition is available when the user has the required capability.
     *
     * @return void
     */
    public function test_is_available_when_user_has_capability() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles']);
        $user = $this->get_user_with_capabilities(['moodle/course:managefiles']);
        $condition  = new condition($structure);
        $this->assertTrue($condition->is_available(false, $this->get_mock_info_module(), false, $user->id));
    }

    /**
     * Tests that the condition is available when the user has multiple required capabilities.
     *
     * @return void
     */
    public function test_is_available_when_user_has_capabilities() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles', 'moodle/course:ignorefilesizelimits']);
        $user = $this->get_user_with_capabilities(['moodle/course:managefiles', 'moodle/course:ignorefilesizelimits']);
        $condition  = new condition($structure);
        $this->assertTrue($condition->is_available(false, $this->get_mock_info_module(), false, $user->id));
    }

    /**
     * Tests that the condition is not available when the user does not have the required capability.
     *
     * @return void
     */
    public function test_is_available_when_user_does_not_have_capability() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles']);
        $user = $this->get_user_with_capabilities(['moodle/course:ignorefilesizelimits']);
        $condition  = new condition($structure);
        $this->assertFalse($condition->is_available(false, $this->get_mock_info_module(), false, $user->id));
    }

    /**
     * Tests that the inverted condition fails when the user has the required capability.
     *
     * @return void
     */
    public function test_is_available_when_user_has_capability_and_condition_inverted() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles']);
        $user = $this->get_user_with_capabilities(['moodle/course:managefiles']);
        $condition  = new condition($structure);
        $this->assertFalse($condition->is_available(true, $this->get_mock_info_module(), false, $user->id));
    }

    /**
     * Tests that the inverted condition passes when the user lacks the required capability.
     *
     * @return void
     */
    public function test_is_available_when_user_does_not_have_capability_and_condition_inverted() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles']);
        $user = $this->get_user_with_capabilities(['moodle/course:ignorefilesizelimits']);
        $condition  = new condition($structure);
        $this->assertTrue($condition->is_available(true, $this->get_mock_info_module(), false, $user->id));
    }

    /**
     * Tests that a debugging message is triggered when an undefined capability is used.
     *
     * @return void
     */
    public function test_is_available_when_capability_is_missing() {
        $structure = $this->get_mock_structure(['moodle/fake:example']);
        $user = $this->get_user_with_capabilities(['moodle/course:managefiles']);
        $condition  = new condition($structure);
        $condition->is_available(false, $this->get_mock_info_module(), false, $user->id);
        $this->assertDebuggingCalled('Capability "moodle/fake:example" was not found! This has to be fixed in code.');
    }

    /**
     * Tests that the capability description is generated correctly for a single capability.
     *
     * @return void
     */
    public function test_get_description_for_one_capability() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles']);
        $condition  = new condition($structure);
        $description = $condition->get_description(true, false, $this->get_mock_info_module());
        $this->assertEquals('The user requires the following capabilities: moodle/course:managefiles', $description);
    }

    /**
     * Tests that the description is correct when the condition is inverted for a single capability.
     *
     * @return void
     */
    public function test_get_description_for_one_capability_and_condition_inverted() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles']);
        $condition  = new condition($structure);
        $description = $condition->get_description(true, true, $this->get_mock_info_module());
        $this->assertEquals('The user must not have the following capabilities: moodle/course:managefiles', $description);
    }

    /**
     * Tests that the capability description is generated correctly for multiple capabilities.
     *
     * @return void
     */
    public function test_get_description_for_multiple_capabilities() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles', 'moodle/course:ignorefilesizelimits']);
        $condition  = new condition($structure);
        $description = $condition->get_description(true, false, $this->get_mock_info_module());
        $this->assertEquals(
                'The user requires the following capabilities: moodle/course:managefiles, moodle/course:ignorefilesizelimits',
                $description);
    }

    /**
     * Tests that the save method returns the original structure.
     *
     * @return void
     */
    public function test_save() {
        $structure = $this->get_mock_structure(['moodle/course:managefiles']);
        $condition  = new condition($structure);
        $savedstructure = $condition->save();
        $this->assertEquals($structure, $savedstructure);
    }

    /**
     * Creates a mock structure object with the specified capabilities.
     *
     * @param array $capabilities List of capabilities.
     * @return \stdClass Mock structure object.
     */
    private function get_mock_structure(array $capabilities = []): \stdClass {
        if (empty($capabilities)) {
            throw new \coding_exception('Must provide at least one capability');
        }
        return (object) [
            'type' => 'capability',
            'capabilities' => $capabilities,
        ];
    }

    /**
     * Creates a mock info module instance for testing.
     *
     * @param \stdClass|null $cm Optional course module object.
     * @return mock_info_module The mocked module info.
     */
    private function get_mock_info_module(\stdClass $cm = null): mock_info_module {
        if (empty($cm)) {
            $course = $this->getDataGenerator()->create_course();
            // Use assign as it's one of the most stable activity types.
            $assign = $this->getDataGenerator()->create_module('assign', ['course' => $course]);
            $cm = get_coursemodule_from_instance('assign', $assign->id);
        }
        get_fast_modinfo(0, 0, true);
        $modinfo = get_fast_modinfo($cm->course);
        return new mock_info_module(0, $modinfo->get_cm($cm->id));
    }

    /**
     * Creates a user and assigns them a role with the given capabilities.
     *
     * @param array $capabilities List of capabilities to assign.
     * @return \stdClass The created user.
     */
    private function get_user_with_capabilities(array $capabilities = []): \stdClass {
        if (empty($capabilities)) {
            throw new \coding_exception('Must provide at least one capability');
        }
        $capabilities = array_fill_keys($capabilities, 'allow'); // Transpose to correct data format.
        $user = $this->getDataGenerator()->create_user();
        $roleid = $this->getDataGenerator()->create_role();
        $this->getDataGenerator()->create_role_capability($roleid, $capabilities, context_system::instance());
        $this->getDataGenerator()->role_assign($roleid, $user->id);
        return $user;
    }
}
