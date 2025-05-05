# Huggingface AI form

A simple PHP application with a form talking to a LLM through Huggingface's API.

## Install

### Composer

In the project directory, run `composer install`

### Environment variables

Get an API token from [Huggingface](https://huggingface.co/settings/tokens).

Make a file named `.env` in the project root directory. Add the token and the path to your sqlite database file.

    SQLITE_DB_PATH="/path/to/your/sqlite/file.db"
    HF_API_TOKEN="yourApiToken"

### Database

Read the SQL file into the Sqlite3 database.

`sqlite3 hff.db < setup.sql`

### Cronjob

A cronjob should be run every minute to update posts that should be published.

Add this line to your crontab and change to the correct paths.

`* * * * * php /pathToThisProjectOnYourMachine/huggingface-form/scripts/addContentToPost.php >> /pathToThisProjectOnYourMachine/projects/huggingface-form/huggingface.log`

## Tests

The script `tests.sh` contains the test suite.

There is a config file for the Github Workflow, `.github/workflows/php.yml`.

## Start the project

You can start the project with the PHP dev server `php -S localhost:3000 -t public/`.
