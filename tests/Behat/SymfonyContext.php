<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class SymfonyContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var string|null */
    private $response;

    /** @var int|null */
    private $resultCode;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Then the response should be :response
     */
    public function theResponseShouldBe(string $response): void
    {
        Assert::assertStringContainsString($response, $this->response);
    }

//    /**
//     * @@When I make an api call to sudoku validation api with file from :path
//     */
//    public function iMakeAnApiCallToSudokuValidationApiWithFileFrom(string $path): void
//    {
//        $response = $this->kernel->handle(Request::create('api/sudoku/validate', 'POST', files: [$path]));
//        var_dump($response);
//    }

    /**
     * @When I make an api call to sudoku validation api with file from :path
     */
    public function iMakeAnApiCallToSudokuValidationApiWithFileFrom2($path)
    {
        $response = $this->kernel->handle(Request::create(
            'api/sudoku/validate',
            'POST',
            server: ['CONTENT_TYPE' => 'text/csv'],
            content: fopen($path, 'r')
        ));
        $this->response = $response->getContent();
        $this->resultCode = $response->getStatusCode();
    }

    /**
     * @When I run the validate sudoku command with path :path
     */
    public function iRunTheValidateSudokuCommand(string $path): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => "app:validate:sudoku",
           'csv-path' => $path
        ));
        $output = new BufferedOutput();

        $this->resultCode = $application->run($input, $output);
        $this->response = $output->fetch();
    }

    /**
     * @then the exit code should be :code
     */
    public function theExitCodeShouldBe(int $code): void {
        Assert::assertEquals($code, $this->resultCode);
    }

    /**
     * @then the response code should be :code
     */
    public function theResponseCodeShouldBe(int $code): void {
        Assert::assertEquals($code, $this->resultCode);
    }
}
