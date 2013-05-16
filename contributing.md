# Contributing

We are more than happy to accept external contributions to the project in the form of feedback, bug reports and even better - pull requests. At this time we are primarily focusing on improving the user-experience and stability of MageSpec for our first release. Please keep this in mind if submitting feature requests, which we're happy to consider for future versions.

## Issue submission

In order for us to help you please check that you've completed the following steps:

* Made sure you're on the latest version `composer.phar update`
* Used the search feature to ensure that the bug hasn't been reported before
* Included as much information about the bug as possible, including any output you've received, what OS and version you're on, etc.

[Submit your issue](https://github.com/MageTest/MageSpec/issues/new)

## Style Guide

MageSpec follows the standards defined in the [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md), [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1.md) and [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2.md) documents.

## Pull Request Guidelines

* Please check to make sure that there aren't existing pull requests attempting to address the issue mentioned. We also recommend checking for issues related to the issue on the tracker, as a team member may be working on the issue in a branch or fork.
* Non-trivial changes should be discussed in an issue first
* Develop in a topic/feature branch, not master, we recommend to follow [this](http://nvie.com/posts/a-successful-git-branching-model/) approach if possible
* Add relevant tests to cover the change
* Lint the code by running `php -l` or using your preferred tool of choice
* Make sure test-suite passes whether is a phpunit or a phpspec one
* Squash your commits
* Write a convincing description of your PR and why we should land it