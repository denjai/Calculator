# Calculator
Commission fee calculator for cash in and cash out operations.
## Installing
* Option 1 -
  Download the app as zip file. Uzip the app in desired directory and go to the root directory of the app.

* Option2 -
  Clone the repository. Run the following command:
`git clone git@github.com:denjai/calculator.git`

Install composer if you do not have it installed yet - [https://getcomposer.org/download/](https://getcomposer.org/download/).

Run the command:

`composer dump-autoload -o`

## Usage

`php service.php input.csv`

## Running tests

`composer install`

`./vendor/bin/phpunit tests`
