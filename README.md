# Finance control with Laravel

## Content Table

-   [Technologies](#technologies)
-   [Installation](#instalation)
-   [Features](#features)
-   [QuickDocs](#docs)
-   [Tests](#tests)
-   [Status](#status)

<a name="technologies" style="font-size:24px;">**Technologies used**</a>

-   PHP
-   Laravel
-   MySQL
-   Docker
-   RabbitMQ
-   Pusher
-   AWS

<a name="instalation" style="font-size:24px;">**Installation**</a><br>

-   Clone the repository<br>

```
$ git clone https://github.com/felipebrsk/finance-control
```

-   Switch to the repo folder<br>

```
$ cd finance-control
```

-   Up the container with Docker<br>

```
$ docker-compose up --build -d
```

You can call API routes in http://localhost:8000/ or change the port in `docker-compose.yaml`.
<br/>
<br/>

<a name="features" style="font-size:24px;">**Features**</a>

-   [x] Implement login;
-   [x] Implement register;
-   [x] Implement register with avatar;
-   [x] Implement refresh token;
-   [x] Create outline database;
-   [x] Create the categories table and model;
-   [x] Create the tags table and model;
-   [x] Create the activity table and model;
-   [x] Create the attachment table and model;
-   [x] Create the currency table and model;
-   [x] Create the earning table and model;
-   [x] Create the import table and model;
-   [x] Create the recurring table and model;
-   [x] Create the space table and model;
-   [x] Create the spending table and model;
-   [x] Create the user table and model;
-   [x] Create all relations til now in models;
-   [x] Create all models unit tests til now;
-   [x] Install docker;
-   [x] Get auth user info;
-   [x] Implement the password forgot method - with queue;
-   [x] Implement the password reset method - with queue;
-   [x] Create an user observer to create default space on user creation;
-   [x] Create a job to execute the recurrings and generate earnings or spendings;
-   [x] Get auth user activities;
-   [x] Create the crud of spendings - with tests;
-   [x] Create the crud of earnings - with tests;
-   [x] Create the crud of spaces - with tests;
-   [x] Create the crud of categories - with tests;
-   [x] Create the crud of tags - with tests;
-   [x] Create route to detach spending tags - with tests;
-   [x] Create route to detach earning tags - with tests;
-   [x] Create route to detach space tags - with tests;
-   [x] Create the crud of recurrings - with tests;
-   [x] Create route to detach recurring tags - with tests;
-   [x] Check the possibility of create a recurring with own currency (created);
-   [x] Implement the jobs to execute the user recurrings;
-   [x] Implement the pusher and sockets to broadcast events;
-   [x] Integrate system with RabbitMQ;
-   [x] Configure the cron job and scheduling tasks for the container;
-   [x] Implement the user profile update;
-   [x] Translate application to pt_BR;
-   [x] Implement the weekly report - partial;
-   [x] Create email template;
-   [x] Implement password changed email;
-   [ ] Improve the system logo;
-   [ ] Implement the email buttons - such as support email etc;
-   [ ] Improve the report with graphics - check possibilities;
-   [ ] Check the possibility of spaces limitations (quantity of spaces per user - subscription or something else);
-   [ ] Get attachmentables.

<br/>
<br/>

<a name="docs" style="font-size:24px;">**QuickDocs**</a>

### Auth

-   `POST` http://localhost:8000/login - Login
-   `POST` http://localhost:8000/register - Register

<br/>

<a name="tests" style="font-size:24px;">**Tests**</a>

-   To run the tests, simple do<br>

```
$ clear && php artisan test
```

Or

```
$ clear && vendor\bin\phpunit tests
```

<br/>
<br/>

<a name="status">**Status**</a>

🚧 Under development... 🚧
