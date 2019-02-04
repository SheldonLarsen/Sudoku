<?php
//This was a simple script to convert array Type Puzzles to XYType Puzzles
//I got the Test Puzzles from a friend who told me good luck and here is some test data

define("FILE_PATH", './Puzzles');

$files = scandir(FILE_PATH . '/Unsolved/ArrayType/');

foreach ($files as $fileName) {

    //If file is array type then convert
    if (substr($fileName, -4) == '.txt') {
        echo "Converting {$fileName}...";
        $puzzleGrid = [];

        //open file to read into puzzle grid
        $file = fopen(FILE_PATH . "/Unsolved/ArrayType/" . $fileName, 'r');
        while (!feof($file)) {
            $line = trim(fgets($file));
            if (!empty($line)) {
                $puzzleGrid[] = explode(' ', $line);
            }
        }
        fclose($file);

        //Generate the File output in xy type
        $saveValues = "";
        foreach ($puzzleGrid as $rowNum => $row) {
            foreach ($row as $colNum => $value) {
                if ($value != 0) {
                    $saveValues .= implode(',', [$rowNum + 1, $colNum + 1, $value]) . "\n";
                }
            }
        }

        //Open, save, and close the file
        $saveFileLocation = FILE_PATH . "/Unsolved/XYType/" . "xy_puzzle" . substr($fileName, -11);
        $saveFile = fopen($saveFileLocation, 'w');
        fwrite($saveFile, $saveValues);
        fclose($saveFile);
        echo "Done\n";
    }
}