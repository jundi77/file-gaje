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
 * Library of interface functions and constants for module zoom
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the zoom specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_zoom
 * @copyright  2018 UC Regents
 * @author     Rohan Khajuria
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zoom\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/zoom/locallib.php');

/**
 * Scheduled task to sychronize meeting data.
 *
 * @package   mod_zoom
 * @copyright 2018 UC Regents
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_license extends \core\task\scheduled_task
{

    /**
     * Returns name of task.
     *
     * @return string
     */
    public function get_name()
    {
        return get_string('assignlicense', 'mod_zoom');
    }

    /**
     * Reassign license.
     *
     * @return boolean
     */
    public function execute()
    {
        global $CFG, $DB;
        require_once($CFG->dirroot.'/mod/zoom/lib.php');
        require_once($CFG->dirroot.'/mod/zoom/classes/webservice.php');

        $priorities = $DB->get_records('user', ['icq' => 'Y'], '', 'email');
        $priorities = array_keys($priorities);

        $service = new \mod_zoom_webservice();

        $sql = "select DISTINCT(host_id) from mdl_zoom where date_format(convert_tz(from_unixtime(start_time) , '+00:00','+07:00'),'%Y-%m-%d') = date_format(convert_tz(from_unixtime(unix_timestamp()) , '+00:00','+07:00'),'%Y-%m-%d')";
        $hosts = $DB->get_records_sql($sql);

        mtrace("Removing license from all users");
        $service->remove_license($priorities);

        mtrace(count($hosts)." users to work with");
        foreach ($hosts as $host) {
            $service->assign_license($host);
            mtrace($host->host_id);
            sleep(1);
        }

        return true;
    }
}
