<?php
/**
 * Created by PhpStorm.
 * User: romek
 * Date: 01.08.16
 * Time: 9:25
 */

class saxParser
{
    const FIELDS_COUNTS = 4;

    private $tutors   = array();
    private $tutorsCounter = 0;
    private $elements = null;
    private $saveCounter = 0;
    private $dao;

    function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * Purpose: Called to this function when tags are opened
     * @param $parser
     * @param $name
     * @param $attrs
     */
    private function startElements($parser, $name, $attrs)
    {

        if(!empty($name)) {
            if ($name == 'COURSE') {
                // creating an array to store information
                $this->tutors[] = array();
            }
            $this->elements = $name;
        }
    }

    /**
     * Purpose: Called to this function when tags are closed
     * @param $parser
     * @param $name
     */
    private function endElements($parser, $name)
    {
        if(!empty($name)) {
            $this->elements = null;
        }
    }

    /**
     * Purpose: Called on the text between the start and end of the tags
     * @param $parser
     * @param $data
     */
    private function characterData($parser, $data)
    {
        if(!empty($data)) {
            $isDataTag = in_array($this->elements, ['NAME', 'COUNTRY', 'EMAIL', 'PHONE']);
            if ($isDataTag) {
                $this->tutors[$this->tutorsCounter][$this->elements] = trim($data);
                $this->saveCounter++;
            }

            if(count($this->tutors[$this->tutorsCounter]) == self::FIELDS_COUNTS) {
                $this->tutorsCounter++;
            }
        }
    }

    private function saveData()
    {
        $countElementToSave = floor($this->saveCounter/self::FIELDS_COUNTS); // 4 because count of fields
        $this->saveCounter -= self::FIELDS_COUNTS * $countElementToSave;
        if($this->saveCounter < 0) {
            throw new RuntimeException('Please check if data is correct in xml file.');
        }

        $data = [];
        foreach($this->tutors as $key => $value) {
            if($countElementToSave == 0) {
                break;
            }

            $data[] = $value;
            $countElementToSave -= 1;

            unset($this->tutors[$key]);
        }

       $this->dao->saveData($data);
    }

    public function parsingXml()
    {
        $parser = xml_parser_create();

        xml_set_element_handler($parser, array($this, "startElements"), array($this, "endElements"));
        xml_set_character_data_handler($parser, array($this, "characterData"));

        // open xml file
        if (!($handle = fopen("tutors.xml", "r"))) {
            die("could not open XML input");
        }

        while($data = fread($handle, 4096)) { // read xml file
            xml_parse($parser, $data);  // start parsing an xml document
            $this->saveData();
        }

        xml_parser_free($parser); // deletes the parser
    }
}