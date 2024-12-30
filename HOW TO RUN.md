HOW TO RUN LARAVEL (social_media_app)

1. Download the code from https://github.com/IamGotu/4072-ITE313-LABORATORY-EXERCISE-5

2. If xampp is not installed download it here https://www.apachefriends.org/index.html and install it (skipped if already downloaded/installed)

3. Open the xampp and start the module Apache and MySQL

4. If MySQL module is not starting, open Task Manager

5. End task mysqld, then start the MySQL module again

6. Install NodeJS here: https://nodejs.org/en

7. In the terminal located in folder social_media_app type "npm install" to have node module

8. Enable the zip extension in PHP. Go to xampp find Config(aligned to the module Apache) press it to open PHP (ini.php). Then change ;extension=zip to extension=zip

9. Navigate to your project folder: example, C:\xampp\htdocs\4072-ITE313-LABORATORY-EXERCISE-5\social_media_app.

10. Right-click the folder and choose Properties.

11. Go to the "Security" tab and ensure that your user account has the appropriate permissions (read, write, modify).

12. If necessary, edit the permissions to allow full control for your user account.

13. Click Edit, select your user, and check Full Control. Click Apply and then OK to save the changes.

14. In the terminal located in folder social_media_app type "composer install" to have vendor

15. In the terminal located in folder social_media_app type "composer require pusher/pusher-php-server" and npm install --save laravel-echo pusher-js

16. Inside folder social_media_app find the file ".env.example" and change it to ".env"

17. In the terminal located in folder social_media_app type "php artisan migrate" to migrate the database

18. In the terminal located in folder social_media_app type "npm run dev"

19. In the terminal located in folder social_media_app type "php artisan serve"

20. If it says no application encryption key has been specified

21. In the terminal located in folder social_media_app type "php artisan key:generate"

22. Then try again in the terminal located in folder social_media_app the "php artisan serve"

23. Open the running server or Ctrl+click the following link


HOW TO RUN CODEIGNITER (codeigniter)

1. Download the code from https://github.com/IamGotu/4072-ITE313-LABORATORY-EXERCISE-5

2. Download xampp here https://www.apachefriends.org/index.html and install it

3. Install intl. extension

4. Then go file explorer to open php.ini located at C:\xampp\php\php.ini

5. In the file searcg for ";extension=intl"

6. Remove the ";" and save it as "extension=intl"

7. Open the xampp and start the module Apache and MySQL

8. If MySQL module is not starting, open Task Manager

9. End task mysqld, then start the MySQL module again

10. Import the database located at codeigniter/app/Database/codeigniter_database.sql

11. Move the folder codeigniter to C:\xampp\htdocs

12. Copy/paste this to the browser: http://localhost/codeigniter/public/


HOW TO RUN NODE.JS/ANGULAR.JS (social_media_node)

1. Download the code from https://github.com/IamGotu/4072-ITE313-LABORATORY-EXERCISE-2

2. Download (https://www.apachefriends.org/index.html) and install xampp 

3. Start the module Apache and MySQL

4. If MySQL module is not starting, open Task Manager

5. End task mysqld, then start the MySQL module again

6. Import the database located at social_media_node/config/social_media_app.sql

7. Make sure to download and install nodejs https://nodejs.org/en 

7. Right click the folder social_media_node then Open in Integrated Terminal

8. In the terminal type "node server.js"

7. Ctrl+click the following link