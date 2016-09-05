<?php

/**
 * Created by PhpStorm.
 * User: romek
 * Date: 31.07.16
 * Time: 20:18
 */
require_once "baseDAO.class.php";

class courseDAO extends baseDAO
{
    protected $_tableName = 'course';
    protected $_primaryKey = 'course_id';
    protected $_fields = 'name, country, email, phone';

    /**
     * @param $name
     * @param $email
     * @return array
     */
    public function getCourseByNameAndEmail($name, $email)
    {
        $where = "name = \"" . (string)$name . "\" AND email = \"" . (string)$email . "\"";
        $result = $this->fetch($where);

        return $result;
    }

    /**
     * Purpose: save course into DB
     * @param $courseData
     */
    public function saveData($courseData)
    {
        $insertData = [];

        foreach ($courseData as $course) {
            $dbCourse = $this->getCourseByNameAndEmail($course['NAME'], $course['EMAIL']);

            if (empty($dbCourse)) {
                $insertData[] = [
                    "\"{$course['NAME']}\"",
                    "\"{$course['COUNTRY']}\"",
                    "\"{$course['EMAIL']}\"",
                    "\"{$course['PHONE']}\""
                ];
            } else {
                $course = array_change_key_case($course, CASE_LOWER);//this solution works if fields with which we works doesn't in CamelCase
                $needToChange = array_diff_assoc($course, $dbCourse);

                if (!empty($needToChange)) {
                    foreach ($needToChange as $key => $value) {
                        $dbCourse[$key] = $value;
                    }

                    $this->update($dbCourse);
                }
            }
        }

        $this->insert($insertData);
    }
}