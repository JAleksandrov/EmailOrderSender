# EmailOrderSender
This script receives emails from specified email in this case it will be on the Gmail server, each email which will contain a product it will send out the correct download URL to the customer.


Server used: AWS Ubuntu.

Server Configuration:

Components required:
Apache2
PHP
PHP IMAP


sudo apt update && sudo apt upgrade -y
sudo apt-get install apache2
sudo apt-get install php libapache2-mod-php

sudo apt install php7.2-imap

You must enable IMAP using this comand else it wont work:
sudo phpenmod imap


Place the files into this directory:
/var/www/html/

What files does what?

p.php -
This is file which needs to be added to cron task every minute it will run and search for new unread inboxes.
You can amend the email and password there.

In function sendURLtoSender you need to set your personal domain for file names
The filenames are like this:
malaysia.zip
caribbean-netherlands.zip

it should be in same directory or this can be amended.

download.php -
This is where the customers use the url to download specific file depending on their token.



Add Cron:
chmod x+ p.php
apt install python3

screen -R 3
python3 cron.py

CTRL + x to exit screen
logout


