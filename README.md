# Clone the project from github

```bash
  git clone https://github.com/ketandhokia/event-booking-system.git
```

# Install dependencies
Once it downloaded, you can run the following command to install all the dependencies:

Make sure you have composer installed in your system (or download & install from here https://getcomposer.org/)
```bash
  composer install
```

# Setup Database
Make sure you have installed the postgres database in your system. 
Then configure database_url in .env file

DATABASE_URL="postgresql://<usernam-here>:<password-here>@127.0.0.1:5432/<db_name-here>?serverVersion=16&charset=utf8"

Then, you can run the following command to run the migration:
```bash
  php bin/console doctrine:database:create
  php bin/console doctrine:migrations:migrate
```

# Get running the server
Make sure you've symfony CLI installed in your system (or download & install from here https://symfony.com/download). Then you can run the following command to run the server:
```bash
  symfony server:start
```

# API Documentations

You can check the api docs on following URL
http://127.0.0.1:8000/api/docs

# Register your first user

You can register a new user using `POST /api/register` api. You need to pass following details
```bash
{
    email: 'john.doe@example.com',
    password: 'abc123'
}
```
You will get success message about registration went successful.

once you successfully registered, You can use the `POST /api/login_check` api to login.
```bash
{
    username: 'john.doe@example.com',
    password: 'abc123'
}
```
You will get a JWT token in the response. You can use this token to access the protected routes.

# How to test API-docs

As you already have registered the above user, 
You can use the `Authorize` button on the right top corner on api-docs to authorize yourself to use all the endpoints.

# Flow to test the functionality
To be able to test the workflow, please follow the below steps.
1. Add Venue using `POST /api/venue` endpoint, You will see the request params details on api-docs
2. Add an Event using `POST /api/event` endpoint.
3. Add the attendees using `POST /api/attendee` endpoint, so that attendee IDs can be used to pass during a booking request.
4. Create booking using `POST /api/event-booking` endpoint, pass the `event_id` and `attendee_ids` as an array. 
You will get success message that booking is done or an error in case something went wrong.


# Tests
You can run the following command to run the test cases:
```bash
  php bin/phpunit
```
