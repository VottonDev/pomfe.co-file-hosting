![pomfe.co](https://a.pomfe.co/emlusbu.png)

# What you need for this source:
* Nginx --> https://nginx.org/en/ (Recommended Web Server)
* Apache2 https://httpd.apache.org/ (Alternative Web Server)
* PHP --> https://secure.php.net/
* MySQL Server --> https://www.mysql.com

# How do you install this?
* You simply drag all the files to your www root directory of your website and modify the settings.inc.php in /includes/. If files don't upload then you should run this: sudo chown -R www-data:www-data to your files directory (Nginx) otherwise everything should work as it is.


# Credits
* CSS done by Nekunekus & Bohrmeista
* Source itself Nekunekus (https://github.com/nokonoko/Pomf)
* Slight modifications done by myself (https://github.com/VottonDev)

# Tips
* Remember to block php executing.
* You can make cron jobs to remove logs automatically if you want to your crontab. (https://www.digitalocean.com/community/tutorials/how-to-use-cron-to-automate-tasks-on-a-vps)
* You can get a free SSL using Let's Encrypt's Certbot (https://certbot.eff.org/#ubuntutyakkety-nginx)

# Apache2 users.
If you are running Apache and want to compress your output when serving files, add this to your .htaccess file:
```AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json```
