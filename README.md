# Sudoku Plus Validation

### How to Install
1) Clone repository
2) cd into the repository and run `composer install`
3) (Optional) Use HTTP service of your choice to serve the API

### Validating a Sudoku Plus
There are two methods for validating a Sudoku Plus with this application.
1) `POST api/sudoku/validate` passing data file for the CSV. Use the following cURL as an example:

```
    curl --location 'http://127.0.0.1:8000/api/sudoku/validate' \
   --header 'Content-Type: text/csv' \
   --data '@/path/to/file.csv'
```
2) Run the command `bin/console app:validate:sudoku [path/to/file.csv]`

The response/output will be JSON with the following body:
```
{
   "valid": bool
   "message": ?string
}
```

If the CSV submitted represents a properly solved Sudoku Plus, `valid` will be true, otherwise it will be false.
If not valid, the `message` property will contain a string describing why the submission was not valid.

### About the Design
The application is split into 3 primary namespaces: `App`, `Domain`, and `Lib`

`App` - All classes and components that directly extend Symfony classes belong here. This includes Controller, Commands,
Entities, DependencyInjection, Events, Forms, and so forth.

`Domain` - This is where `App` connects to for Domain specific logic. This typically contains services, View Models, 
DTOs, and any other logic that could be, for the most part, decoupled from Symfony Components

`Lib` - Classes and modules in this namespace represent components that are completely decoupled from all business 
logic. These modules serve to provide generic functionality that may or may not extend other open source libraries.
Due to this design, in theory, each folder inside `Lib` could be its own opensource project that could be utilized 
in completely unrelated projects

Access points in `App`, like Controllers and Commands are as slim as possible, responsible only for passing the Request 
into the `Domain`, as well as handling any errors that may be passed back for access point specific behavior.

### Testing
Behat Testing was chosen to test the full feature coverage. Tests for 10 different input cases are provided and these 
cases are tested against both the API and the Symfony Command. The command `./vendor/bin/behat` may be used to test the
full suite, but a `/path/to-file:line_number[-upper_range_line_number]` may be passed as an additional argument to test 
a specific test or range of tests in a file.

### Omitted
Some aspects of this project were omitted, and would be topics of interest for further development. This includes 
but is not limited to:
1) Add Unit Tests, specifically for Validation, CSV Reader. Unit tests are great for smaller classes, and keep your classes small
2) Add docker files to remove "Well it works on my machine!!"
3) CI/CD support, specifically for automated test runs of behat/PHPUnit suite
4) Build out some of Symfony's built-in tools and extended libraries! There are a few bundles to support Rest APIs,
API docs, request validation
5) Rate limit and security! Right now we have no auth layer nor anything to inhibit excessive requests. This leaves
the application open to exploitation or abuse!