## Background
This project provides some API endpoints to create a job. The actual endpoint to save the job is POST /jobs, 
while the other endpoints are providing some additional functionality that clients may need.

A job represents a task or some job a consumer needs to be done by a tradesman/craftsman.
It is something like "Paint my 60m2 flat" or "Fix the bathroom sink".
Every job is categorized in a "Service". You can think of them like categories. eg: "Boat building & boat repair" or "Cleaning of gutters".
Also every job needs some additional data like a description and when the job should be done.

## The Task
You should review and refactor this project, so it matches your criteria for good code and has a state that you're fine with. 
Feel free to change anything you want.
Please write a small documentation where you explain what you have changed and why you think your refactored code is better than the original code.

## Results
If you are finished, please create a .zip archive with your name as filename and upload it here:
https://www.dropbox.com/request/H5TZGaVAcaR0WZntIfiQ  
The archive should contain the complete project including the .git folder but you can leave out the /vendor/ directory to keep it small.

## Expectations
During our review we will go through your refactoring in order to check if expected changes have been made. The expected changes are defined according to industry code quality standards and on best practices. In order to help you a bit we have defined how much findings and refactoring actions we expect at least, following are the actions required prioritized by criticality.

### Critical
We expect at least 3 fixes that are critical issues

### Medium
Depending on your experience we will expect a certain number of findings here.
Hint, make sure you consider coding principles and design patterns in your refactoring.
There is a total of at least 4 to 7 findings here depending on your experience.

### Lower
This section is for less important changes but will give you bonus points, especially the more senior the role is the more we also expect to see here.
Hint, things like documentation, code cleanup, higher test coverage.


## Run the project
### Setup
- `docker-compose up -d`
- `docker run --rm --interactive --tty --volume $PWD/jobs:/app --volume $COMPOSER_HOME:/tmp composer:1.7.2 install`
- `docker-compose exec php bin/console doctrine:migrations:migrate`

## Tests
- `docker-compose exec php bin/console doctrine:database:create --env=test`
- `docker-compose exec php vendor/bin/phpunit`

### TODO
* Use existing nginx container from registry instead of creating new one
* Move passwords from docker-compose?
* Inspect against PSRs
* Use symfony inspection
* Use scrutinizer inspection
* Use scrutinizer CI
* Test coverage 100%
* Job when to be done use datetime, not date ?
* check if mbstring php extension exist in php docker container
* Remove .idea from gitignore
* write a small documentation where you explain what you have changed and why you think your refactored code is better than the original code.
* PlantUML diagram with data flow
* Remove apache configuration files
* Remove all TODOs
* Create an errorMessageHandler
* Use custom exceptions
* Use DTOs instead of arrays for entities creation
* Validate DTOs
* Move services configuration inside bundle
* Remove app/Resources with unused views
* Swagger API documentation
* Tests with AAA
* Functional controller tests
* [Critical] curl /job - 500. Uncaught PHP Exception (NotFoundHttpException)
* [Critical] Wrap exceptions
* Strict types declaration
* Fix phpdocs everywhere
* Declare return and argument types everywhere
* Check if post action works on initial project before changes
* [Internal] Check all API routes in api.http (after tests)
* Get rid of static methods
* Generate entity ids on backend instead of client

### In progress

### Done
* [Critical] Remove symfony cache directories from git
* Cache and logs moved to projects "var" directory to follow the principle of least surprise
* [Critical] Tests ServiceControllerTest::getOneServiceFound() and ServiceControllerTest::getOneServiceNotFound() are failed because undefined variable error in \AppBundle\Controller\ServiceController::getAction()
* Test ZipcodeControllerTest::getOneZipcodeNotFound() failed due to wrong http code assertion
* Package "roave/securityadvisories" added to composer to ensure application doesn't have installed dependencies with known security vulnerabilities.
* Move tests inside bundle
* Move fixtures to tests namespace
* Static services and builders removed from controllers. DI used instead. Controllers configured as services
* Builders renamed to factories and became non static
* Rename Service entity to JobCategory for better expression, and to "not use recerved keyword" good practice. I would ask team and business side to rename it in out Domain Dictionary as well, according to DDD principle.
