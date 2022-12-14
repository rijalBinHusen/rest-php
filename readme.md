# ReST API using  flight php framework

Flight micro framework website [Official](https://flightphp.com/)

## The REST API spec :

| Methods  | Urls | Actions | Status |
| ------------- | ------------- | ------------- | ------------- | 
| GET | api/myguests | get all 20 guests | [x] |
| GET | api/myguests/:id | get guest by id | [x] |
| POST | api/myguests | add new guest | [x] |
| PUT | api/myguests/:id | update guest by id | [x] |
| DELETE | api/myguests/:id | remove guest by id | [x] |
<!-- | DELETE | api/myguests | remove all guests | [ ] | -->
<!-- | GET | api/myguests/published | find all published guests | [ ] | -->
<!-- | GET | api/myguests?title=[kw] | find all myguests which title contains 'kw' | -->

## Todo:
- [x] Find How to using .env file in this framework
- [x] Connect to database using .env parameter
- [x] Create data to database using post method
- [x] Show data from database using get method
- [x] Show data from database by 1 parameter
- [ ] show data from database by more 1 parameter
- [x] Update data by id in database using put method
- [x] Delete data from database using delete method
- [x] Find a way to avoid directory listing

## What i learn from this project:

- Enable php for apache2
  When i run my ubuntu machine, the browser can't render file that contain phpinfo()
  So, i install the package that enable php for apache2:
  `sudo apt install libapache2-mod-php`

- Enabling web server to override the configuration by using .htaccess file
  1. Edit the apache2 conf
    `sudo nano /etc/apache2/apache2.conf`
    Replace the AllowOverride None to AllowOverride All
    ```
    <Directory /var/www/>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
    </Directory>
    ```
  2. Enable rewrite modul `sudo a2enmod rewrite` 
  3. Restart the apache `sudo service apache2 restart`
   
- Enabling cors for all origin by adding this script to .htaccess file
  ```
  Header add Access-Control-Allow-Origin "*"
  Header add Access-Control-Allow-Methods: "GET,POST,OPTIONS,DELETE,PUT"
  ```

- Enabling cors in ubuntu machine
  1. Edit the apache2 conf `sudo nano /etc/apache2/apache2.conf`
    Add script `Header set Access-Control-Allow-Origin "*"` like bellow:
    ```
    <Directory /var/www/>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
            Header set Access-Control-Allow-Origin "*"
    </Directory>
    ```
  2. Enable headers module `sudo a2enmod headers`
  3. Restart apache2 `sudo service apache2 restart`
  4. Add script to the .htaccess file
  ```
  Header add Access-Control-Allow-Origin "*"
  Header add Access-Control-Allow-Methods: "GET,POST,OPTIONS,DELETE,PUT"
  ```

- Avoid directory listing by adding script `Options -Indexes` to .htaccess file
- Prevent public to access to `.env` file by adding this script to .htaccess file:
  ```
  <Files .env>
      Order allow,deny
      Deny from all
  </Files>
  ```