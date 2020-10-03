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



