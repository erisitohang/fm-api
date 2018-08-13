# Friends Management API

## Requirements

* PHP >= 7.1.0
* Database MySQL
* Composer

OR
* Composer
* Docker


## Getting Started
Follow the below procedures for installing Application

### Clone this repo
```shell
$ git@github.com:erisitohang/fm-api.git fm-api
$ cd mailbox
```
### Run Server (docker)
```shell
$ docker-compose up --build -d
```

### Run Composer

Go in to php server
```shell
$ docker exec -it friend-management_php_1 bash
```
install composer
```shell
$ composer install
```
 
### Environment Configuration
```shell
$ cp .env.example .env
```
After this file copy, update the attributes in .env to match your environment, database

### Run migration
```shell
$ php artisan migrate
```

### Seed the Database
```shell
$ php artisan db:seed
```

### Running the tests

Navigate to the project root and run vendor/bin/phpunit after installing all the composer dependencies.
```shell
$ ./vendor/bin/phpunit
```

## List of routes
```$xslt
+------+------------------+-----------------+-------------------------------------------------+---------+------------+
| Verb | Path             | NamedRoute      | Controller                                      | Action  | Middleware |
+------+------------------+-----------------+-------------------------------------------------+---------+------------+
| GET  | /                |                 | None                                            | Closure |            |
| POST | /user            | user            | App\Http\Controllers\Api\UserController         | store   |            |
| POST | /friend          | friend          | App\Http\Controllers\Api\RelationshipController | store   |            |
| POST | /friend/mine     | friend.mine     | App\Http\Controllers\Api\RelationshipController | mine    |            |
| POST | /friend/common   | friend.common   | App\Http\Controllers\Api\RelationshipController | common  |            |
| POST | /subscribe       | subscribe.store | App\Http\Controllers\Api\SubscriberController   | store   |            |
| POST | /subscribe/block | subscribe.block | App\Http\Controllers\Api\SubscriberController   | block   |            |
| POST | /feed            | feed.index      | App\Http\Controllers\Api\FeedController         | index   |            |
+------+------------------+-----------------+-------------------------------------------------+---------+------------+
```

## Usage

### Create User
POST /user
#### POST
```json
{
	"email": "test1@test.com",
	"name": "John Doe"
}
```
#### Response
```json
{
    "email": "test1@test.com",
    "name": "John Doe",
    "id": 107
}
```

### Friend connection between two email addresses(1)
POST /friend
#### POST
```json
{
  "friends":
    [
      "test1@test.com",
      "test2@test.com"
    ]
}
```
#### Response
```json
{
  "success": true
}
```

### Retrieve the friends list for an email address (2)
POST /friend/mine
#### POST
```json
{
  "email": "test1@test.com"
}
```
#### Response
```json
{
  "success": true,
  "friends" :
    [
      "test2@test.com"
    ],
  "count" : 1   
}
```

### Retrieve the common friends list between two email addresses (3)
POST /friend/common
#### POST
```json
{
  "friends":
    [
      "test1@test.com",
      "test2@test.com"
    ]
}
```
#### Response
```json
{
  "success": true,
  "friends" :
    [
      "test3@test.com",
      "test4@test.com"
    ],
  "count" : 2 
}
```

### Subscribe to updates from an email address (4)
POST /subscribe 
#### POST
```json
{
  "requestor": "test1@test.com",
  "target": "test2@test.com"
}
```
#### Response
```json
{
  "success": true
}
```

### Block updates from an email address (5)
POST /block 
#### POST
```json
{
  "requestor": "test1@test.com",
  "target": "test2@test.com"
}
```
#### Response
```json
{
  "success": true
}
```

### Retrieve all email addresses that can receive updates from an email address (6)
POST /block 
#### POST
```json
{
  "sender": "test1@test.com",
  "text": "Hello test2@test.com"
}
```
#### Response
```json
{
  "success": true,
  "recipients":
      [
        "test2@test.com",
        "test3@test.com"
      ]
}
```

