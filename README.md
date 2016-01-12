![Parsclick](public_html/images/misc/parsclick-logo.png "Parsclick")

Parsclick
===================
This project is made by Hassan Azimi for Parsclick website


How To Install
===================
1. Download the zip file.
2. Create a database called `parsclick` or any other name.
3. Import the `parsclick.sql` file.
4. The `public` directory must be sitting inside the server's public root directory.
5. The `includes` directory must be sitting outside the server's public root directory.

How To Install On Heroku
===================

1. `heroku login`
2. `git clone https://github.com/hassanazimi/Parsclick.git`
3. `cd Parsclick`
4. `heroku create`
5. `git push heroku master`
6. `heroku open`
7. `heroku run bash --parsclick`
8. Create a PostgreSQL database and import the database structure from `Database/Parsclick_postgres_structure_only.sql`
9. We have `Procfile` in the project. If you don't have the file, you can create it and add these:
 9.1. `web: vendor/bin/heroku-php-apache2` for apache server
 9.2. `web: vendor/bin/heroku-php-nginx` for nginx server
 9.3. `web: vendor/bin/heroku-php-apache2 public/` to specify the root of the app
  
