# Welcome to ToDo & Co contributing guide

Thank you for investing your time in contributing to our project!

In this guide you will get an overview of the contribution workflow from opening an issue, creating a PR, reviewing, and merging the PR.

## Getting started

To start our project see [README](https://github.com/MytiX/TodoList/blob/master/README.md) on [Todo & Co repository](https://github.com/MytiX/TodoList) 

## Create a new issue

If you spot a problem with the docs, you can open a new issue using a relevant [issue form](https://github.com/MytiX/TodoList/issues/new). 

## Solve an issue

Scan through our [existing issues](https://github.com/MytiX/TodoList/issues) to find one that interests you. You can narrow down the search using `labels` as filters. We don’t assign issues to anyone. If you find an issue to work on, you are welcome to open a PR with a fix.

## Run PHPUnit Test
Before committing your changes, you must run unit tests and php-cs-fixer
```
    make phpunit
```
```
    vendor/bin/php-cs-fixer fix src/
```

## Code quality

Go on [Codacy dashboard](https://app.codacy.com/gh/MytiX/TodoList/dashboard) to see if your code is up to the standard developement.

## Pull Request

When you're finished with the changes, create a pull request, also known as a PR.
- Assign one person to rewiew our code
- Don't forget to link PR to issue if you are solving one.
- As you update your PR and apply changes, mark each conversation as resolved.

### Your PR is merged!

Congratulations :tada::tada: The ToDo & Co team thanks you. 
