<?php

namespace Domain\Action;

use Domain\Exception\InvalidSudokuException;
use Domain\Model\SudokuGrid;
use Lib\CSVReader\CSVReader;
use Lib\CSVReader\Exception\MalformedCSVException;

class CreateSudokuGridFromCSV
{
    public function __construct(){}

    public function execute(string $fileName): SudokuGrid
    {
        $csv = CSVReader::createFromFilePath($fileName);
        $totalRows = count([...$csv]);
        foreach ($csv as $row) {
            if (count($row) !== $totalRows) {
                throw new MalformedCSVException("CSV row does not contain the proper amount of columns");
            }
            foreach($row as $value) {
                if ($value === null) {
                    throw new InvalidSudokuException("WTF");
                }
                if (trim($value) === '') {
                    throw new InvalidSudokuException("Incomplete Puzzle");
                }
                if (!ctype_digit($value)) {
                    throw new MalformedCSVException("CSV contains a non integer");
                }
                if ((int) $value > $totalRows || (int) $value <= 0) {
                    throw new MalformedCSVException("CSV contains an integer that is out of bounds!");
                }
            }
        }

        $csv->rewind();
        return new SudokuGrid([...$csv]);
    }
}