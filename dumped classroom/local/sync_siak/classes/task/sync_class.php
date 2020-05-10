<?php

namespace local_sync_siak\task;

use core_course_category;
use stdClass;

require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/externallib.php');

class sync_class extends \core\task\scheduled_task
{
    public function get_name()
    {
        // Shown in admin screens
        return get_string('class', 'local_sync_siak');
    }

    public function execute()
    {
        // Call class API from siak SOAP and store it in an array
        $course = $this->getClass();

        // Use result array to create categories and courses
        $this->createCourses($course);

        // Call enrolment API from siak SOAP and store it in an array
        //$enrolment = $this->getEnrolment();
        //$enrolment = array_reverse($enrolment);

        // Use result array to create categories and courses
        //$this->enrolment($enrolment);
    }

    public function createCourses($data)
    {
        foreach ($data as $c) {
            global $DB;

            // Populate categories before courses
            // There are 5 levels of (sub)categories: institution, program, faculty, study program, semester

            $institution = $DB->get_record('course_categories', array('idnumber' => $c['ID_Institusi']));
            if ($institution === false) {
                $institutionData = new stdClass();
                $institutionData->idnumber = $c['ID_Institusi'];
                $institutionData->name = $c['Institusi'];
                $institutionData->parent = 0;
                $institution = core_course_category::create($institutionData);
            }

            $program = $DB->get_record('course_categories', array('idnumber' => $c['ID_Institusi'] . '_' . $c['ID_Program']));
            if ($program === false) {
                $programData = new stdClass();
                $programData->idnumber = $c['ID_Institusi'] . '_' . $c['ID_Program'];
                $programData->name = $c['Program'];
                $programData->parent = $institution->id;
                $program = core_course_category::create($programData);
            }

            $faculty = $DB->get_record('course_categories', array('idnumber' => $c['ID_Institusi'] . '_' . $c['ID_Program'] . '_' . $c['ID_Fakultas']));
            if ($faculty === false) {
                $facultyData = new stdClass();
                $facultyData->idnumber = $c['ID_Institusi'] . '_' . $c['ID_Program'] . '_' . $c['ID_Fakultas'];
                $facultyData->name = $c['Fakultas'];
                $facultyData->parent = $program->id;
                $faculty = core_course_category::create($facultyData);
            }

            $studyProgram = $DB->get_record('course_categories', array('idnumber' => $c['ID_Institusi'] . '_' . $c['ID_Program'] . '_' . $c['ID_Fakultas'] . '_' . $c['ID_Program_Studi']));
            if ($studyProgram === false) {
                $studyProgramData = new stdClass();
                $studyProgramData->idnumber = $c['ID_Institusi'] . '_' . $c['ID_Program'] . '_' . $c['ID_Fakultas'] . '_' . $c['ID_Program_Studi'];
                $studyProgramData->name = $c['Program_Studi'];
                $studyProgramData->parent = $faculty->id;
                $studyProgram = core_course_category::create($studyProgramData);
            }

            $semester = $DB->get_record('course_categories', array('idnumber' => $c['ID_Institusi'] . '_' . $c['ID_Program'] . '_' . $c['ID_Fakultas'] . '_' . $c['ID_Program_Studi'] . '_' . $c['ID_Semester']));
            if ($semester === false) {
                $semesterData = new stdClass();
                $semesterData->idnumber =  $c['ID_Institusi'] . '_' . $c['ID_Program'] . '_' . $c['ID_Fakultas'] . '_' . $c['ID_Program_Studi'] . '_' . $c['ID_Semester'];
                $semesterData->name = $c['Semester'];
                $semesterData->parent = $studyProgram->id;
                $semester = core_course_category::create($semesterData);
            }

            $category = $semester->id;

            // Prepare course data
            // Shortname is made up from course id, class number, and semester
            $shortname =  $c['ID_Program_Studi'] . '_' . $c['Kode_MK'] . '_' . $c['Kelas'];

            // Idnumber is made up from institution, class number and semester
            $idnumber = $c['ID_Kelas'];

            //fullname = 'crse_id_desc (section) class_nbr'
            $fullname = $c['Kelas'];
            $visible = 1;

            $courseData = array(
                'shortname' => $shortname,
                'fullname' => $fullname,
                'idnumber' => $idnumber,
                'category' => $category,
                'enablecompletion' => 1,
            );

            // Moodle 3.7+ require object, convert array to object
            $courseData = (object) $courseData;

            // Check if course exists
            $course = $DB->get_record('course', array('idnumber' => $idnumber));

            if ($course) {
                $courseData->id = $course->id;
                // Update course if course already exists
                update_course($courseData);
                $output = $shortname . ' is updated <br>';
            } else {
                $template = $DB->get_record('course', array('idnumber' => 'coursetemplate'));
                $source = $template->id;
                $courseData->visible = $visible;
                $duplicate = \core_course_external::duplicate_course($source, $courseData->fullname, $courseData->shortname, $courseData->category, $courseData->visible);
                $courseData->format = 'remuiformat';
                $courseData->id = $duplicate['id'];
                update_course($courseData);
                $output = $shortname . ' is created <br>';
            }
            mtrace($output);
        }
    }

    public function enrolment($data)
    {
        global $DB;

        // Get siak instructor role id
        $roleInstructor = $DB->get_record('role', array('shortname' => 'instructor'));
        $roleInstructorId = $roleInstructor->id;

        // moodle enrolment
        // 3 Editing teacher
        // 4 Non editing teacher
        // 5 Student

        usort($data, function ($a, $b) {
            return $a['LASTUPDDTTM'] - $b['LASTUPDDTTM'];
        });


        foreach ($data as $enrol) {
            $idnumber = $enrol['INSTITUTION'] . '_' . $enrol['CLASS_NBR'] . '_' . $enrol['STRM'];

            if ($enrol['TYPE'] == 'STDNT') {
                $role = 5;
            } elseif ($enrol['TYPE'] == 'INSTR') {
                $role = 3;
            }

            $user = $DB->get_record('user', array('idnumber' => $enrol['EMPLID'], 'deleted' => 0));

            if ($user == false) {
                continue;
            }
            $status = ($enrol['STDNT_ENRL_STATUS'] == 'D') ? 1 : 0;
            $enrolstatus = ($status) ? 'suspended' : 'enrolled';
            $this->enrol_user($idnumber, $user, $role, $status);
            if ($enrol['TYPE'] == 'INSTR') {
                $this->enrol_user($idnumber, $user, $roleInstructorId, $status);
            }
            echo "User $user->username is $enrolstatus to subject " . $idnumber . "\n";
        }
    }

    public function enrol_user($idnumber, $user, $roleid, $status, $enrolmethod = 'manual')
    {
        global $DB;
        // $course = $DB->get_record_sql('SELECT * FROM {course} WHERE idnumber = '.$idnumber);
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

    public function getClass()
    {
	global $CFG;
	$file = $CFG->dirroot . '/local/sync_siak/classes/task/CLASS.csv';
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
