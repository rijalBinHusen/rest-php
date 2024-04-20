# ReST API using  flight php framework

Flight micro framework website [Official](https://flightphp.com/)

## Unit testing
I do unit testing with php unit [PHP unit test](https://phpunit.de/manual/4.8/en/database.html)

## SQL Full text search
I learn Full text search sql from [here](https://www.w3resource.com/mysql/mysql-full-text-search-functions.php)

## Set FULL TEXT to some column that exist on database
To create a full-text index, you can use the CREATE FULLTEXT INDEX statement. For example, the following statement creates a full-text index on the content column of the articles table:

```CREATE FULLTEXT INDEX ft_idx_articles_content ON articles (content);```

## define variable on utils/constant_named.php, example:
```php
// JWT constant name

define("JWT_SECRET", "YOUR-JWT-SECRET-KEY");
define("JWT_ISSUER", "johndoe");
define("JWT_AUD", "your-site.com");
define("JWT_ALGO", "HS256");

// key to encrypt descrypt string
define("ENCRYPT_DECRYPT_PHONE", "wGk11X03ju");
define("APP_SCRIPT_URL", "https://script.google.com/macros/s/AKfycbwMpwdiDkgwm7PC");

// database config

define("MYSQL_HOST", "localhost");
define("MYSQL_DB_NAME", "myreport");
define("MYSQL_DB_USER", "root");
define("MYSQL_DB_PASSWORD", "null");

```