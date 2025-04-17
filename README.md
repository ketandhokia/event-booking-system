Once you download the project from github, you can run the following command to install all the dependencies:

Make sure you have composer installed in your system (or download & install from here https://getcomposer.org/)
```bash
  composer install
```

Make sure you have installed the postgres database in your system. 
Then configure database_url in .env file

DATABASE_URL="postgresql://<usernam-here>:<password-here>@127.0.0.1:5432/<db_name-here>?serverVersion=16&charset=utf8"

Then, you can run the following command to run the migration:
```bash
  php bin/console doctrine:database:create
  php bin/console doctrine:migrations:migrate
```
Make sure you've symfony CLI installed in your system (or download & install from here https://symfony.com/download). Then you can run the following command to run the server:
```bash
  symfony server:start
```

You can check the api docs on following URL
http://127.0.0.1:8000/api/docs

You can register a new user using `POST /api/register` api. once you successfully registered, You can use the `POST /api/login_check` api to login. You will get a JWT token in the response. You can use this token to access the protected routes. You can use the `Authorize` button on the right top corner to authorize yourself to use all the endpoints.

You can run the following command to run the test cases:
```bash
  php bin/phpunit
```
