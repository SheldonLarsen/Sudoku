# Sudoku
### Simple Sudoku Solving Algorithm

This was built to solve variable sized Sudoku Puzzles

The Puzzles must meet the following to be processed:
* must be .txt file
* the board must be a square
* The subsquares must also be square. Valid Sizes are (4x4,9x9,16x16,25x25...)

 This will process files using one of two storage types. The first is ArrayType. To define a file as array type the file name must start with ar. The other type is XYType. These files are defined using xy as the file names first two characters. Examples for each are below. 
XYType can only solve 9x9 puzzles.

 

ArrayType Example:
```
0 4 0 0 0 0 1 7 9
0 0 2 0 0 8 0 5 4
0 0 6 0 0 5 0 0 8
0 8 0 0 7 0 9 1 0
0 5 0 0 9 0 0 3 0
0 1 9 0 6 0 0 4 0
3 0 0 4 0 0 7 0 0
5 7 0 1 0 0 2 0 0
9 2 8 0 0 0 0 6 0
```
XYType Example <row><column><value>
```
1,2,4
1,7,1
1,8,7
1,9,9
2,3,2
2,6,8
2,8,5
2,9,4
3,3,6
3,6,5
3,9,8
4,2,8
4,5,7
4,7,9
4,8,1
5,2,5
5,5,9
5,8,3
6,2,1
6,3,9
6,5,6
6,8,4
7,1,3
7,4,4
7,7,7
8,1,5
8,2,7
8,4,1
8,7,2
9,1,9
9,2,2
9,3,8
9,8,6
```

Usage: php SolveSudoku.php [param]

Valid Param values are:
  * Example.txt       (Runs a Comma separated list of .txt files
  * -ar or -ArrayType (This Runs all files in the ArrayType Folder.
  * -xy or -XYType    (This Runs all files in the XYType Folder.
  * -fringe or -hard  (This Runs all files in the FringeTests  Folder.
  * -h or -help       (This Help.)

#### Warning:
The FringeTests folder has various puzzles some test the different sizes (4x4, 16x16, etc.) However, there are two other specialized puzzles. The file ar_puzzle_9x9_HH_01.txt is the hardest puzzle made for humans it was created by Arto Inkala. The other file ar_puzzle_CH_01.txt is the hardest for this algorithm. This puzzle will take more than an hour to solve.
