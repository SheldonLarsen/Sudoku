<?php
//This Loads the Different Files types
include 'Sudoku.php';
define("FILE_PATH", './Puzzles');

try {
    //Process the various arguments
    if ($argc > 1) {
        $location = FILE_PATH . "/Unsolved/";

        switch (strtolower($argv[1])) {
            case "-help":
            case "-h":
                echo "Usage: php SolveSudoku.php [files]";
                echo "\nValid File values are:";
                echo "\n  Example.txt       (Runs a Comma separated list of .txt files";
                echo "\n  -ar or -ArrayType (This Runs all files in the ArrayType Folder.";
                echo "\n  -xy or -XYType    (This Runs all files in the XYType Folder.";
                echo "\n  -fringe or -hard  (This Runs all files in the FringeTests  Folder.";
                echo "\n  -h or -help       (This Help.)";
                echo "\n\n";
                return;
                break;

            //Set the location and get a list of files
            case "-ar":
            case "-arraytype":
                $location .= "ArrayType/";
                $puzzles = scandir($location);
                break;

            case "-xy":
            case "-xytype":
                $location .= "XYType/";
                $puzzles = scandir($location);
                break;

            case "-fringe":
            case "-hard":
                $location .= "FringeTests/";
                $puzzles = scandir($location);
                break;

            //Get the List of Files that were passed in
            default:
                $location = "";
                $puzzles = explode(',', $argv[1]);
                break;
        }
    } else {
        throw new Exception("Missing Parameters. Run php SolveSudoku.php -h for help");
    }

    //Remove The home and parent directories
    $puzzles = array_diff($puzzles, array('.', '..'));

    $totalTime = 0;
    $solved = 0;
    //Loop for each given File
    foreach ($puzzles as $fileName) {
        if (substr($fileName, -4) != '.txt') {
            throw new Exception("File Must be of type .txt");
        }

        //Open the file
        $fileLocation = $location . $fileName;
        $file = fopen($fileLocation, 'r');

        //Verify that the file was opened
        if ($file == false) {
            throw new Exception("File: {$fileLocation} could not be opened.");
        }

        //Strip off the path and return only the file name
        $fileName = basename($fileName);

        //Create the Grid if the file type is xy then assume it is 9x9
        $fileType = substr($fileName, 0, 2);
        switch ($fileType) {
            case "xy":
                $puzzleGrid = array_fill(0, 9, array_fill(0, 9, 0));
                break;

            case "ar":
                $puzzleGrid = [];
                break;
        }

        //Loop Until the end of the file
        while (!feof($file)) {
            $line = trim(fgets($file));
            if (!empty($line)) {
                switch ($fileType) {
                    case "xy":
                        $lineEntry = explode(',', $line);
                        $puzzleGrid[$lineEntry[0] - 1][$lineEntry[1] - 1] = $lineEntry[2];
                        break;

                    case "ar":
                        $puzzleGrid[] = explode(' ', $line);
                        break;
                }
            }
        }
        fclose($file);

        //Create Sudoku instance
        $sudoku = new Sudoku($puzzleGrid);

        echo "\nSolving {$fileName}";
        echo $sudoku->toString(true);

        $startTime = microtime(true);
        $sudoku->solve();
        $endTime = microtime(true);

        //Track Time Stats
        $time = $endTime - $startTime;
        $totalTime += $time;
        $solved++;

        if(!isset($maxTime) || $time > $maxTime) {
            $maxTime = $time;
        }

        if(!isset($minTime) || $time < $minTime) {
            $minTime = $time;
        }
        echo "\nSolved in {$time} second(s)";
        echo $sudoku->toString(true);

        //Save The Output to a file
        $saveFileLocation = FILE_PATH . "/Solved/" . $fileName;
        $saveFile = fopen($saveFileLocation, 'w');
        fwrite($saveFile, $sudoku->toString());
        fclose($saveFile);
    }

    echo "\nSolved {$solved} puzzle(s)";
    echo "\nTotal Time:   {$totalTime} second(s)";
    echo "\nAverage Time: " . ($totalTime/$solved) . " second(s)";
    echo "\nMax Time:     {$maxTime} second(s)";
    echo "\nMin Time:     {$minTime} second(s)\n\n";

//Catch any errors the program might have
} catch (Exception $e) {
    echo "Error: \n{$e->getMessage()}\n";
}