# [TIMELY](https://timely.lucabettini.com)

You are viewing the server side code. Client side repo is available [here](https://github.com/lucabettini/timely-client).

<br>

This API was created as a personal project in September 2021. Built with Laravel 8, it's a completely RESTful API with JWT authentication that provides endpoints to perform all basics CRUD operations on different types of tasks, behaving like a digital time tracker (like [Toggl](https://toggl.com/track/toggl-desktop/)) and schedule manager (like [TickTick](https://www.ticktick.com/)).

The API serves 34 endpoints in total to the [main website](https://timely.lucabettini.com) built with React, all successfully [tested](https://github.com/lucabettini/timely-backend/tree/main/tests/Feature) with PhpUnit, using [factories](https://github.com/lucabettini/timely-backend/tree/main/database/factories) to inject provisional records in the MySQL database.

![Tests](https://i.imgur.com/uE7Aqwf.jpeg)

<br>
<br>

## DESIGN

Even if it's a simple app, I tried to implementing with design best practices usually applied to larger codebases in mind, organizing the code with the repository and service patterns. Every [controller](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/RecurringTaskController.php) has an associated [repository](https://github.com/lucabettini/timely-backend/blob/main/app/Modules/Tasks/Repositories/RecurringTaskRepository.php) that interacts with the database through the right [model](https://github.com/lucabettini/timely-backend/blob/main/app/Models/RecurringTask.php).

In some cases, I split the logic inside the controller in one or more services (e.g. [CompleteRecurringTaskService](https://github.com/lucabettini/timely-backend/blob/main/app/Modules/Tasks/Services/CompleteRecurringTaskService.php)), which interact with the right methods inside the repository. The code inside the controller is stripped down to the bare minimum, with no data access code or complex logic, making them easy to read.

Being an API that needs to interact with a React frontend through responses in JSON, even in case of errors, I created a [middleware](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Middleware/AcceptJson.php) to automatically set the right headers in the request, the added it to the api group in the [Http Kernel](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Kernel.php).
Every route was added to the api group inside the boot method of [RouteServiceProvider](https://github.com/lucabettini/timely-backend/blob/main/app/Providers/RouteServiceProvider.php).

Finally, to prevent the N+1 problem, model relationships are eager loaded whenever possible, returning only the necessary data from the API using custom JSON [resources](https://github.com/lucabettini/timely-backend/tree/main/app/Http/Resources).

<br>
<br>

## TASKS

Every user is connected with a one-to-many relationship to a number of tasks, which have the following fields:

| FIELD         | EXAMPLE 1                 | EXAMPLE 2         |
| ------------- | ------------------------- | ----------------- |
| Name          | John's birthday           | Pay car insurance |
| Bucket        | Birthdays                 | Payments          |
| Area          | Family and friends        | Personal finance  |
| Description   | Dinner party, start at 11 |                   |
| Scheduled_for | ISO timestamp             | ISO timestamp     |
| Completed     | False                     | True              |
| Tracked       | False                     | True              |

Every task can be tracked or recurring (or both). If the task is tracked, a one-to-many relationship is established with the time_units table, that allows to add a series of tracked 'sessions' with start_time and end_time.

Two accessors defined inside the Task model add the total time unit count and total duration of all time units as additional fields when the model is returned as JSON.

As of now, a user is allowed to track only one task at the same time, but this has to do more with how the front end was designed than the server's or DB's structure. A GET endpoint was provided that returns the started time unit to mantain the client in sync during multiple sessions.

If the track is recurring, a one-to-one relationship is establish with a row inside the recurring_tasks table, with the following fields:

| FIELD            | EXAMPLE |
| ---------------- | ------- |
| Frequency        | year    |
| Interval         | 1       |
| Occurrences left | 5       |
| End date         | null    |

A tasks repeats every n days/weeks/months/years, where n is the interval number, for the number of occurrences specified or until the end date. If both are null, the task repeats forever.

When a task marked as recurring is completed, a new one is created with a different date. The recurring task entry is changed (if necessary), the foreign key of the completed task is removed and the one of the new task is added.

Below is a simple schema of endpoints pertaining to tasks, recurring tasks and time units:

|        | ROUTE                        | ACTION                                               | CONTROLLER                                                                                                                                |
| ------ | ---------------------------- | ---------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| GET    | /tasks                       | Get all tasks                                        | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /tasks/open                  | Get incomplete tracked tasks                         | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /tasks/overdue               | Get all incomplete tasks with due date in the past   | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /tasks/today                 | Get all tasks with due date today                    | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /tasks/tomorrow              | Get all tasks with due date tomorrow                 | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /tasks/week                  | Get all tasks with due date in the next 7 days       | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /tasks/{id}                  | Get a single task with full infos and all time units | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /areas                       | Get a list of areas names                            | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /area/{area}                 | Get infos about an area                              | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| GET    | /area/{area}/bucket/{bucket} | Get all tasks inside a bucket                        | [GetTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/GetTaskController.php)             |
| PATCH  | /area                        | Change area name                                     | [EditTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/EditTaskController.php)           |
| PATCH  | /bucket                      | Edit bucket name                                     | [EditTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/EditTaskController.php)           |
| DELETE | /bucket                      | Delete all tasks inside a bucket                     | [EditTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/EditTaskController.php)           |
| POST   | /tasks                       | Add task                                             | [EditTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/EditTaskController.php)           |
| PATCH  | /tasks/{id}/complete         | Mark task as completed                               | [EditTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/EditTaskController.php)           |
| PATCH  | /tasks/{id}/incomplete       | Mark task as not completed                           | [EditTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/EditTaskController.php)           |
| PUT    | /tasks/{id}                  | Edit task                                            | [EditTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/EditTaskController.php)           |
| DELETE | /tasks/{id}                  | Delete task                                          | [EditTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/EditTaskController.php)           |
| POST   | /tasks/{id}/recurring        | Add recurring task                                   | [RecurringTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/RecurringTaskController.php) |
| PATCH  | /tasks/{id}/complete         | Complete recurring task                              | [RecurringTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/RecurringTaskController.php) |
| PUT    | /tasks/{id}/recurring        | Edit recurring task                                  | [RecurringTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/RecurringTaskController.php) |
| DELETE | /tasks/{id}/recurring        | Delete recurring task                                | [RecurringTaskController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/RecurringTaskController.php) |
| GET    | /time_unit/{id}              | Get started time unit                                | [TimeUnitController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/TimeUnitController.php)           |
| POST   | /tasks/{task_id}/time_unit   | Add time unit                                        | [TimeUnitController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/TimeUnitController.php)           |
| PUT    | /time_unit/{id}              | Edit time unit                                       | [TimeUnitController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/TimeUnitController.php)           |
| DELETE | /time_unit/{id}              | Delete time unit                                     | [TimeUnitController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Tasks/TimeUnitController.php)           |

<br>
<br>

## USERS AND AUTHENTICATION

Since this is an API, the default Laravel session guard could not be used. I decided to create a custom one inside the [AuthServiceProvider](https://github.com/lucabettini/timely-backend/blob/main/app/Providers/AuthServiceProvider.php) class. When the guard returns null, a 401 response is automatically forwarded to the client; when a valid User istance is returned, this becomes available as $request->user() in the controller. This solution allows to change the guard in the future without touching controllers or repositories other than Login and Register.

Upon registration or login, the [TokenService](https://github.com/lucabettini/timely-backend/blob/main/app/Modules/Users/Services/TokenService.php) creates a JWT using the [firebase/php-jwt](https://github.com/firebase/php-jwt) package. The JWT contains the user email and a unique ID. The token is sent back to the client and stored in session storage.

Upon logout, the token unique ID is added to a blacklist in the DB. After every request, the guard checks the token integrity, expiration date and his presence in the blacklist before returning the right user.

Every user can register, login, logout, delete the account (with all tasks), change his email or username (providing the current password as proof of identity), change his current password (while authenticated) or reset it (if forgotten).

Below is a a simple schema of authentication endpoints:

|        | ROUTE                   | CONTROLLER                                                                                                                      | ACTION                          |
| ------ | ----------------------- | ------------------------------------------------------------------------------------------------------------------------------- | ------------------------------- |
| POST   | /register               | [RegisterController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Users/RegisterController.php) | Register a new user             |
| POST   | /login                  | [LoginController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Users/LoginController.php)       | Login user                      |
| POST   | /editAccount            | [AccountController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Users/AccountController.php)   | Edit profile                    |
| DELETE | /deleteAccount          | [AccountController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Users/AccountController.php)   | Delete account                  |
| POST   | /logout                 | [LogoutController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Users/LogoutController.php)     | Logout user                     |
| POST   | /changePassword         | [PasswordController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Users/PasswordController.php) | Change password while logged in |
| POST   | /forgotPassword         | [PasswordController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/Users/PasswordController.php) | Send reset email                |
| POST   | /reset-password/{token} | [PasswordController](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Controllers/UsersPasswordController.php)  | Change forgotten password       |

<br>
<br>

## SECURITY & VALIDATION

All private routes are grouped together and assigned to the auth middleware, which uses the [custom auth guard](https://github.com/lucabettini/timely-backend/blob/main/app/Providers/AuthServiceProvider.php).

The API is protected against DDOS attacks by a simple rate limiter inside [Route Service Provider](https://github.com/lucabettini/timely-backend/blob/main/app/Providers/RouteServiceProvider.php). A [middleware](https://github.com/lucabettini/timely-backend/blob/main/app/Http/Middleware/EnforceHttps.php) enforce s htpps redirection and the [cors settings](https://github.com/lucabettini/timely-backend/blob/main/config/cors.php) allow requests only from the client website URL.

Data inside every request are validated either at the controller level or through a dedicated [request](https://github.com/lucabettini/timely-backend/tree/main/app/Http/Requests). To prevent timezones errors, the server receives every date in UTC and returns only ISO strings, while the client displays them in local time.

<br>

---

Made by [Luca Bettini](https://lucabettini.com).
