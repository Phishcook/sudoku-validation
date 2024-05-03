Feature:
  Assert CSVs representing possible Sudoku Plus entries are valid or invalid via CLI or API

  Scenario Outline: A Sudoku Plus CSV is validated via Symfony Command
    When I run the validate sudoku command with path "<csv_path>"
    And the response should be <response_body>
    Examples:
      | csv_path | response_body |
      |  features/files/valid.csv                  |  '{"valid":true,"message":null}'   |
      |  features/files/image.png                  |  '{"valid":false,"message":"CSV row does not contain the proper amount of columns"}'   |
      |  features/files/duplicate_column.csv       |  '{"valid":false,"message":"Sudoku Column contains a duplicate"}'   |
      |  features/files/duplicate_row.csv          |  '{"valid":false,"message":"Sudoku Row contains a duplicate"}'   |
      |  features/files/duplicate_subgrid.csv      |  '{"valid":false,"message":"Sudoku Sub-grid contains a duplicate"}'   |
      |  features/files/incomplete.csv             |  '{"valid":false,"message":"Incomplete Puzzle"}'   |
      |  features/files/invalid_character.csv      |  '{"valid":false,"message":"CSV contains a non integer"}'   |
      |  features/files/not_sudoku_plus_size.csv   |  '{"valid":false,"message":"Not a valid Sudoku Plus grid"}'   |
      |  features/files/valid_4x4.csv              |  '{"valid":true,"message":null}'   |

  Scenario Outline: A Sudoku Plus CSV is validated via Symfony Command
    When I make an api call to sudoku validation api with file from "<csv_path>"
    Then the response code should be "<response_code>"
    And the response should be <response_body>
    Examples:
      | csv_path | response_code | response_body |
      |  features/files/valid.csv                 |  200  |  '{"valid":true,"message":null}'   |
      |  features/files/image.png                 |  400  |  'The file provided is not a valid CSV'   |
      |  features/files/duplicate_column.csv      |  200  |  '{"valid":false,"message":"Sudoku Column contains a duplicate"}'   |
      |  features/files/duplicate_row.csv         |  200  |  '{"valid":false,"message":"Sudoku Row contains a duplicate"}'   |
      |  features/files/duplicate_subgrid.csv     |  200  |  '{"valid":false,"message":"Sudoku Sub-grid contains a duplicate"}'   |
      |  features/files/incomplete.csv            |  200  |  '{"valid":false,"message":"Incomplete Puzzle"}'   |
      |  features/files/invalid_character.csv     |  400  |  'The file provided is not a valid CSV'   |
      |  features/files/not_sudoku_plus_size.csv  |  200  |  '{"valid":false,"message":"Not a valid Sudoku Plus grid"}'   |
      |  features/files/valid_4x4.csv             |  200  |  '{"valid":true,"message":null}'   |
