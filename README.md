![pomfe.co](https://user-images.githubusercontent.com/5682352/36320287-92139be8-133d-11e8-880b-8dfcdc05225a.png)

# Requirements
* A web server (Nginx or Apache)
* PHP (>=5.5)
* MySQL Server

# Installation
* Drag all the files to the "root directory" of your website.
* Run sudo chown -R www-data:www-data /var/www/domain.com/html/files
* Import "schema.sql" and "update.sql" into your MySQL Database.
* Modify "settings.inc.php" inside /includes/.
* Try uploading a file: see if it adds a row into your MySQL database and try to open the file.

# Credits
* Pomf done by [Nekunekus](https://github.com/nokonoko/Pomf)
* Modifications done by Bohrmeista & Votton.

# Tips
* Remember to block the execution of PHP code on your web server.
* You can make cron jobs to remove logs [automatically](https://www.digitalocean.com/community/tutorials/how-to-use-cron-to-automate-tasks-on-a-vps). 
* You can get a free SSL using Let's Encrypt's [Certbot](https://certbot.eff.org/#ubuntutyakkety-nginx) or CloudFlare's SSL Solution. 

# Helpful resources:
* [phpMyAdmin](https://www.phpmyadmin.net/) - Easier to manage MySQL
