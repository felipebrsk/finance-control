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
-   [ ] Integrate system with RabbitMQ;
-   [ ] Check the possibility of create a recurring with own currency;
-   [ ] Get auth user recurrings;
-   [ ] Get auth user spendings;
-   [ ] Get auth user earnings;
-   [ ] Get auth user spaces;
-   [ ] Get auth user categories;
-   [ ] Get auth user imports;
-   [ ] Get auth user activities;
-   [ ] Get spendings and earnings tags;
-   [ ] Implement the jobs to execute the user recurrings;
-   [ ] Configure the cron job and scheduling tasks for the container;
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
