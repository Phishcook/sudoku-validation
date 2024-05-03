<?php

namespace App\Controller;

use Domain\Action\ValidateSudokuPlus;
use Domain\Exception\InvalidSudokuException;
use Domain\View\SudokuResponse;
use Lib\CSVReader\Exception\MalformedCSVException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/sudoku', name: 'app_sudoku_')]
class SudokuController extends AbstractController
{
    #[Route(
        '/validate',
        name: 'validate',
        methods: "POST",
    )]
    public function upload(Request $request, ValidateSudokuPlus $validateCSV): Response
    {
        $file = $request->getContent(true);

        if (!is_resource($file)) {
            throw new BadRequestException("You must upload a valid CSV file");
        }

        try {
            $valid = true;
            $message = null;
            $fileInfo = stream_get_meta_data($file);
            $validateCSV->execute($fileInfo['uri']);
        } catch (InvalidSudokuException $e) {
            $message = $e->getMessage();
            $valid = false;
        } catch (MalformedCSVException $e) {
            throw new BadRequestException("The file provided is not a valid CSV");
        }

        return new SudokuResponse($valid, $message);
    }
}