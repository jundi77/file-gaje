<?php
// This file is part of the Zoom plugin for Moodle - http://moodle.org/
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
 * Definition of Zoom scheduled tasks.
 *
 * @package    mod_zoom
 * @copyright  2015 UC Regents
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$tasks = array(
    array(
        'classname' => 'mod_zoom\task\update_meetings',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '0',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*'
    ),
    array(
        'classname' => 'mod_zoom\task\get_meeting_reports',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '0,12',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
    array(
        'classname' => 'mod_zoom\task\clean_users',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '0,12',
        'day' => '25',
        'dayofweek' => '*',
        'month' => '12'
    ),
    array(
        'classname' => 'mod_zoom\task\assign_license',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '3',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
    array(
        'classname' => 'mod_zoom\task\reset_api_calls',
        'blocking' => 0,
        'minute' => '59',
        'hour' => '23',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    )
);
