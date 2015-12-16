sudo a2enmod ssl
sudo service apache2 restart
 
create /etc/apache2/ssl/ folder
put hostonly.crt and hostonly.key in there
Note that these work for 192.168.56.101 (VBox's host-only default IP)

(To create new cert run:
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/apache2/ssl/my.key -out /etc/apache2/ssl/my.crt
Pay attention to put the correct FQDN (IP) when asked)

place team7hostonly-ssl in /etc/apache2/sites-available/
Assumes your code is somewhere within /var/www/ 
(If you have your own key and different IP, change the fields "ServerName", "SSLCertificateFile", "SSLCertificateKeyFile")



sudo a2ensite team7hostonly-ssl
sudo service apache2 reload


point app/scripts/app.js to: 
var api = "https://192.168.56.101/"; 

