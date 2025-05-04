# Huggingface AI form

A simple PHP application with a form talking to a LLM through Huggingface's API.

## Install

In the project directory, run `composer install`

Get an API token from Huggingface.

Make a file named `.env` in the project root directory. Add the token and the path to your sqlite database file.

    SQLITE_DB_PATH="/path/to/your/sqlite/file.db"
    HF_API_TOKEN="yourApiToken"

## Start the project

You can now start the project with the PHP dev server `php -S localhost:3000 -t public/`.
