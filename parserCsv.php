<?php
/**
 * Created by PhpStorm.
 * User: romek
 * Date: 03.09.16
 * Time: 17:22
 */


class csvParser {
    private $fileHandler;
    private $dataToSave = [];
    private $dao;

    const COUNT_TO_SAVE = 4;
    const COURSE_KEYS = ['NAME', 'COUNTRY', 'EMAIL', 'PHONE'];

    function __construct($fileName, $dao)
    {
        $this->fileHandler = fopen($fileName, 'r');
        $this->dao = $dao;
    }

    /**
     *
     */
    public function parsingCsv()
    {
        if ($this->fileHandler != false) {

            // get the first row, which contains the column-titles (it's unuseful information)
            fgetcsv($this->fileHandler);

            $countDataToSave = 0;
            // loop through the file line-by-line
            while(($data = fgetcsv($this->fileHandler)) != false)
            {
                unset($data[0]);
                //array_combine(self::COURSE_KEYS, $data);
                $this->dataToSave[] = array_combine(self::COURSE_KEYS, $data);
                $countDataToSave++;

                if($countDataToSave == self::COUNT_TO_SAVE) {
                    $countDataToSave = 0;
                    $this->saveParsedData();
                }

                unset($data);
            }

            if(!empty($this->dataToSave)) {
                $this->saveParsedData();
            }


        }

        $this->closeFile();
    }

    private function saveParsedData()
    {
        $this->dao->saveData($this->dataToSave);
        unset($this->dataToSave);
    }

    private function closeFile()
    {
        fclose($this->fileHandler);
    }
}