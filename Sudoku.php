<?php

class Sudoku
{
    private $grid;
    private $size;
    private $squareSize;

    /**
     * Sudoku constructor.
     * @param $grid
     * @throws Exception
     */
    public function __construct($grid)
    {
        $this->grid = $grid;
        $this->size = count($this->grid);
        $this->validateGrid();
    }

    /**
     * @throws Exception
     * Tests the Validity of the Grid
     */
    private function validateGrid()
    {
        if (empty($this->grid)) {
            throw new Exception("Game Grid Cannot be empty");
        }

        if (!is_array($this->grid)) {
            throw new Exception("Game Grid Must be an Array");
        }

        foreach ($this->grid as $row) {
            if (!isset($lastRowSize)) {
                $lastRowSize = count($row);
            }

            if ($lastRowSize != count($row)) {
                throw new Exception("All rows must be the same size");
            }

            if (count($row) != count($this->grid)) {
                throw new Exception("Board Must be square");
            }

            foreach ($row as $col) {
                if ($col > $this->size) {
                    throw new Exception("Grid Values Cannot exceed table size");
                }
            }
        }

        $this->squareSize = sqrt($this->size);
        if (intval($this->squareSize) != $this->squareSize) {
            throw new Exception("Sudoku Board Must be a traditional size. (ex. 4x4,9x9,16x16,25x25)");
        }
    }

    /**
     * @param bool $fancy
     * @return string
     * Returns the Board in one of two ways:
     * if fancy is true then it returns a dynamic ascii representation
     * if fancy is false then it returns a grid of numbers
     */
    public function toString($fancy = false)
    {
        $saveValue = "";
        if ($fancy) {
            $saveValue = $this->fancyOutput();
        } else {
            foreach ($this->grid as $rowNum => $row) {
                foreach ($row as $colNum => $value) {
                    $saveValue .= $value . " ";
                }
                $saveValue .= "\n";
            }
        }
        return $saveValue;
    }

    /**
     * @return string
     * This Generates the dynamic grid ascii output
     */
    private function fancyOutput()
    {
        //Dynamic Top Row
        $output = "\n┏" . str_repeat("━━━━━━━┳", count($this->grid) - 1) . "━━━━━━━┓";

        foreach ($this->grid as $rowNum => $row) {
            if ($rowNum != 0) {
                //Divider Row
                $output .= "\n┃" . str_repeat("━━━━━━━╋", count($this->grid) - 1) . "━━━━━━━┫";
            }

            //Start Space Row
            $output .= "\n┃" . str_repeat("       ┃", count($this->grid));
            $output .= "\n┃";

            foreach ($row as $colNum => $value) {
                if ($value == 0) {
                    $value = "";
                }
                $output .= " " . sprintf("%3s", $value) . "   ┃";
            }

            //End Space Row
            $output .= "\n┃" . str_repeat("       ┃", count($this->grid));

        }
        //End Row
        $output .= "\n┗" . str_repeat("━━━━━━━┻", count($this->grid) - 1) . "━━━━━━━┛\n";
        return $output;
    }

    /**
     * @return bool
     * @throws Exception
     * This is the router for the various algorithms
     * currently it only has a bruteForce algorithm
     */
    public function solve()
    {
        if (!$this->bruteForce()) {
            throw new Exception("Puzzle Cannot be solved");
        }
        return true;
    }

    /**
     * @return bool
     * Recursive Function to Brute Force the Puzzle
     */
    private function bruteForce()
    {
        $nextLocation = $this->nextBlank();
        //If there isn't a next location it is finished
        if ($nextLocation === false) {
            return true;
        }

        list($row, $col) = $nextLocation;
        //Test 1 to the grid size
        for ($value = 1; $value <= $this->size; $value++) {
            $this->grid[$row][$col] = $value;
            //If the grid doesn't have a conflict
            if (!$this->hasConflict($row, $col, $value)) {
                //Solved for the next Location
                if ($this->bruteForce()) {
                    return true;
                }
            }
        }
        $this->grid[$row][$col] = 0;
        return false;
    }

    /**
     * @param $row
     * @param $col
     * @param $value
     * @return bool
     * Checks the Three rules of Sudoku
     */
    private function hasConflict($row, $col, $value)
    {
        //Check the row
        if (count(array_keys($this->grid[$row], $value)) > 1) {
            return true;
        }

        if (!$this->validCol($col, $value)) {
            return true;
        }

        if (!$this->validSquare($row, $col, $value)) {
            return true;
        }
        return false;
    }

    /**
     * @param $col
     * @param $value
     * @return bool
     * Verifies that the Column in correct
     */
    private function validCol($col, $value)
    {
        $totalValues = 0;
        foreach ($this->grid as $row) {
            if ($row[$col] == $value) {
                $totalValues++;
            }
            if ($totalValues > 1) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $row
     * @param $col
     * @param $value
     * @return bool
     * Validates that the Square is correct
     */
    private function validSquare($row, $col, $value)
    {
        //Find what square needs to be tested
        $squareRow = intval($row / $this->squareSize);
        $squareCol = intval($col / $this->squareSize);

        // Determine starting and ending locations for the square
        $startRow = $squareRow * $this->squareSize;
        $endRow = ($squareRow + 1) * $this->squareSize;
        $startCol = $squareCol * $this->squareSize;
        $endCol = ($squareCol + 1) * $this->squareSize;

        //Check for duplicates withing the location
        $valueCount = 0;
        for ($rowLoc = $startRow; $rowLoc < $endRow; $rowLoc++) {
            for ($colLoc = $startCol; $colLoc < $endCol; $colLoc++) {
                if ($this->grid[$rowLoc][$colLoc] == $value) {
                    $valueCount++;
                }
                if ($valueCount > 1) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @return array|bool
     * Finds the next Blank Space in the Sudoku puzzle
     */
    private function nextBlank()
    {
        //Start at last location and search the next rows and cols for a blank
        for ($row = 0; $row < $this->size; $row++) {
            for ($col = 0; $col < $this->size; $col++) {
                if ($this->grid[$row][$col] == 0) {
                    return [$row, $col];
                }
            }
        }
        //If no blank is found return false;
        return false;
    }
}