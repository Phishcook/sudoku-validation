<?php

namespace Domain\Model;

use Domain\Exception\InvalidSudokuException;

class SudokuGrid
{
    /**
     * @param array<array<int>> $fields
     */
    public function __construct(private array $fields)
    {

    }

    public function getLength(): int
    {
        return count($this->fields);
    }

    /**
     * Return all sub-grids of the SudokuPlus.
     * @return SudokuGrid[]
     */
    public function getSubGrids(): array
    {
        $newGridLength = (int) sqrt($this->getLength());

        // prevent floating point false positive
        if ($this->getLength() / $newGridLength !== $newGridLength) {
            throw new InvalidSudokuException("Not a valid Sudoku Plus grid");
        }

        $grids = [];
        for ($y = 0; $y < $newGridLength; $y++) {
            // row iteration (y axis)
            for ($x = 0; $x < $newGridLength; $x++) {
                // columnar iteration (x axis)
                $rowSlice = array_slice($this->fields, $y*$newGridLength, $newGridLength);
                $columnSlice = array_map(
                    static fn($row) => array_slice($row, $x*$newGridLength, $newGridLength),
                    $rowSlice
                );
                $grids[] = new SudokuGrid($columnSlice);
            }
        }

        return $grids;
    }

    /**
     * @return array<array<int>>
     */
    public function getRows(): array
    {
        return $this->fields;
    }

    /**
     * @return array<array<int>>
     */
    public function getColumns(): array
    {
        $columns = [];
        for ($i = 0; $i < count($this->fields); $i++) {
            $columns[] = array_column($this->fields, $i);
        }

        return $columns;
    }
}