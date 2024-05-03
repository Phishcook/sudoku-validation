<?php

namespace App\Command;

use Domain\Action\ValidateSudokuPlus;
use Domain\Exception\InvalidSudokuException;
use Domain\View\SudokuResponse;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the "name" and "description" arguments of AsCommand replace the
// static $defaultName and $defaultDescription properties
#[AsCommand(
    name: 'app:validate:sudoku:csv',
    description: 'Imports and validates if a CSV is a valid Sudoku Plus',
    hidden: false,
    aliases: ['app:validate:sudoku']
)]
class ValidateSudokuCSV extends Command
{
    public function __construct(private ValidateSudokuPlus $validateCSV)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command imports a CSV and validates the syntax')
            ->addArgument('csv-path', InputArgument::REQUIRED, 'Path to the CSV to import and validate')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csvPath = $input->getArgument('csv-path');
        $message = null;

        try {
            $this->validateCSV->execute($csvPath);
            $valid = true;
        } catch(InvalidSudokuException $e) {
            $valid = false;
            $message = $e->getMessage();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $valid = false;
        }

        $output->write(new SudokuResponse($valid, $message));
        return Command::SUCCESS;
    }
}