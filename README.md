![pomfe.co](https://a.pomfe.co/emlusbu.png)

# What you need for this source:
* Nginx --> https://nginx.org/en/ (Recommended Web Server)
* Apache2 --> https://httpd.apache.org/ (Alternative Web Server)
* Caddy --> https://caddyserver.com/ (Alternative Web Server & Automatic SSL)
* PHP --> https://secure.php.net/
* MySQL Server --> https://www.mysql.com
* phpMyAdmin --> https://www.phpmyadmin.net/ (Easier to manage MySQL)

# How do you install this?
* You drag all the files to your "root directory" of the website.
* Run sudo chown -R www-data:www-data /var/www/domain.com/html/files
* Setup the MySQL config at /includes/ folder.
* Modify the JavaScript file to change the upload output "a.pomfe.co"
* Try uploading a file and see if it pops up at MySQL database and see if you are able to open the file in the first place.
* Profit


# Credits
* Pomf done by Nekunekus (https://github.com/nokonoko/Pomf)
* Modifications done by bohrmeista & Votton

# Tips
* Remember to block php execution in your NGINX/Apache2/Caddy Host Block.
* You can make cron jobs to remove logs automatically. (https://www.digitalocean.com/community/tutorials/how-to-use-cron-to-automate-tasks-on-a-vps)
* You can get a free SSL using Let's Encrypt's Certbot or CloudFlare's SSL Solution. (https://certbot.eff.org/#ubuntutyakkety-nginx)

# Apache2 users.
If you are running Apache and want to compress your output when serving files, add this to your .htaccess file:
```AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json```
