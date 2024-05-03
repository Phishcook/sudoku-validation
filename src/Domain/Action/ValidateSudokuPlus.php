<?php

namespace Domain\Action;

use Domain\Exception\InvalidSudokuException;

class ValidateSudokuPlus
{
    public function __construct(private readonly CreateSudokuGridFromCSV $createSudokuGrid)
    {

    }

    public function execute(string $filepath): void
    {
        $sudokuGrid = $this->createSudokuGrid->execute($filepath);
        // For the "Parent" grid, or the entire overall puzzle, we must validate each row and each column to ensure
        // numbers 1-n are used without duplicate
        $length = $sudokuGrid->getLength();
        foreach($sudokuGrid->getRows() as $row) {
            if ($length !== count($row)) {
                throw new InvalidSudokuException("Sudoku row has the incorrect number of values");
            }
            if ($length !== count(array_unique($row))) {
                throw new InvalidSudokuException("Sudoku Row contains a duplicate");
            }
        }

        foreach($sudokuGrid->getColumns() as $row) {
            if ($length !== count($row)) {
                throw new InvalidSudokuException("Sudoku row has the incorrect number of values");
            }
            if ($length !== count(array_unique($row))) {
                throw new InvalidSudokuException("Sudoku Column contains a duplicate");
            }
        }

        // Now that we asserted all rows and columns meet Sudoku rules, we must check each grid
        foreach($sudokuGrid->getSubGrids() as $subGrid) {
            $rows = $subGrid->getRows();
            $numbersInSubGrid = array_merge(...$rows);
            if ($length !== count($numbersInSubGrid)) {
                throw new InvalidSudokuException("Sudoku sub-grid has the incorrect number of values");
            }
            if ($length !== count(array_unique($numbersInSubGrid))) {
                throw new InvalidSudokuException("Sudoku Sub-grid contains a duplicate");
            }
        }
    }
}