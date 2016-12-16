# pomfe.co

Pomfe.co's Source Code

# What you need for this source:
* Nginx --> http://nginx.org/en/ (Recommended)
* PHP --> http://php.net/PHP
* MySQL Server --> https://www.mysql.com


# Credits
* CSS done by Nekunekus & Bohrmeista
* Source itself Nekunekus (https://github.com/nokonoko/Pomf)

# Notes
* Remember to block php executing.
* You can make cron jobs to remove logs automatically if you want.
* This works in Apache2 too.

# Apache2 users.
If you are running Apache and want to compress your output when serving files, add to your .htaccess file:
```AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json```
