<?php

namespace local_sync_siak\task;

use core_course_category;
use stdClass;

require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/externallib.php');
require_once($CFG->dirroot . '/user/externallib.php');

class sync_enrol extends \core\task\scheduled_task
{
    public function get_name()
    {
        // Shown in admin screens
        return get_string('enrol', 'local_sync_siak');
    }

    public function execute()
    {
        //$this->deleteUsers();
        // Call class API from siak SOAP and store it in an array
        $enrol = $this->getEnrol();

        // Use result array to create categories and courses
        $this->syncUsers($enrol);
    }

    public function syncUsers($data)
    {
        foreach ($data as $user) {
            if ($user['ID_User'] ==  'NULL' || is_null($user['ID_User']) || $user['ID_User'] ==  '') {
                continue;
            }

            $this->createLocalUser($user);
            $this->enrolment($user);
        }
    }

    public function deleteUsers()
    {
        global $DB;
        $users = $DB->get_records('user', ['auth' => 'oauth2', 'deleted' => 0]);
        $count = count($users);
        foreach ($users as $user) {
            delete_user($user);
            $count--;
            mtrace($count);
        }
    }

    public function createLocalUser($user)
    {
        global $DB;
        $userdata = array(
            'username' => strtolower($user['ID_User']),
            'firstname' => $user['Nama'],
            'lastname' => $user['Nama'],
            'email' => strtolower($user['NIP']).'@its.ac.id',
            'auth' => 'oidc',
            'idnumber' => $user['NIP'],
        );

        $record = $DB->get_record('user', array('username' => $user['ID_User'], 'auth' => 'oidc'));

        if ($record == true) {
            echo "<br>User " . $user['Nama'] . " already exist <br>";
            $userdata['id'] = $record->id;
            $userdata['auth'] = $record->auth;
            $user = \core_user_external::update_users(array($userdata));
        } else {
            echo "<br>User " . $user['Nama'] . " created <br>";
            $user = \core_user_external::create_users(array($userdata));
        }
    }

    public function enrolment($enrol)
    {
        global $DB;

        // moodle enrolment
        // 3 Editing teacher
        // 4 Non editing teacher
        // 5 Student

        $idnumber = $enrol['Kelas_ID'];

        if ($enrol['Peran'] == 'Mahasiswa') {
            $role = 5;
        } elseif ($enrol['Peran'] == 'Dosen') {
            $role = 3;
        }

        $user = $DB->get_record('user', array('username' => $enrol['ID_User'], 'deleted' => 0));

        if ($user == true) {
            $status = ($enrol['Status_Enrollment'] == 'Enroll') ? 1 : 0;
            $enrolstatus = ($status) ? 'suspended' : 'enrolled';
            $this->enrol_user($idnumber, $user, $role, $status);
            echo "User $user->firstname is $enrolstatus to subject " . $idnumber . "\n";
        }
    }

    public function enrol_user($idnumber, $user, $roleid, $status, $enrolmethod = 'manual')
    {
        global $DB;
        $course = $DB->get_record('course', array('idnumber' => $idnumber));
        if ($course === false) {
            return;
        }
        $context = \context_course::instance($course->id);
        //if (!is_enrolled($context, $user)) {
        if (true) {
            $enrol = enrol_get_plugin($enrolmethod);
            if ($enrol === null) {
                return false;
            }
            $instances = enrol_get_instances($course->id, true);
            $manualinstance = null;
            foreach ($instances as $instance) {
                if ($instance->enrol == $enrolmethod) {
                    $manualinstance = $instance;
                    break;
                }
            }
            if ($manualinstance === null) {
                $instanceid = $enrol->add_default_instance($course);
                if ($instanceid === null) {
                    $instanceid = $enrol->add_instance($course);
                }
                $instance = $DB->get_record('enrol', array('id' => $instanceid));
            }
            try {
                $enrol->enrol_user($instance, $user->id, $roleid, 0, 0, $status);
            } catch (\Exception $e) {
                echo $e;
                echo "<br>";
                echo "User might already be enrolled? Continuing... <br>";
            }
        }
        return true;
    }

    public function getEnrol()
    {
        global $CFG;
        $file = $CFG->dirroot . '/local/sync_siak/classes/task/ENROL.csv';
        $file = fopen($file, "r");
        $header = fgetcsv($file, null, ";");
        $data = array();

        while ($row = fgetcsv($file, null, ";")) {
            $data[] = array_combine($header, $row);
        }

        fclose($file);
        return $data;
    }
}
