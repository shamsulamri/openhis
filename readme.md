# OpenHIS

## Hospital Information System

### [https://iodojo.com](https://iodojo.com)

This is an open source hospital information system designed for primary or secondary care. It contains all the essential modules for running a healthcare facility from patient registration to billing. Built entirely on top of open source technologies to ensure freedom and continuous support from the community. For details please visit the link above.

### Installation & Configuration

In order to ensure the application runs first time I have decided to use the development versions but feel free to upgrade them later. The installation script below was created in Ubuntu 20.04.1 LTS.

1. PHP (5.6)

	For details visit https://tecadmin.net/install-php5-on-ubuntu/

		sudo add-apt-repository ppa:ondrej/php
		sudo apt-get update
		sudo apt-get install -y php5.6
		sudo apt-get install -y php5.6-mysql 

	Confirm the installed php version:

		php -v

2. Composer

	For details visit https://getcomposer.org/download/

	Run composer from anywhere:

		sudo mv composer.phar /usr/local/bin/

		alias composer='/usr/local/bin/composer.phar'

3. MySQL (5.7.32)

	For details visits https://computingforgeeks.com/how-to-install-mysql-on-ubuntu-focal/

		sudo apt update
		sudo apt install wget -y
		wget https://dev.mysql.com/get/mysql-apt-config_0.8.12-1_all.deb

	Once downloaded, install the repository by running the command below

		sudo dpkg -i mysql-apt-config_0.8.12-1_all.deb

	Run the command below to update your system packages

		sudo apt-get update

		sudo apt install -f mysql-client=5.7.32-1ubuntu18.04 mysql-community-server=5.7.32-1ubuntu18.04 mysql-server=5.7.32-1ubuntu18.04

4. Clone the project
	
		git clone https://github.com/shamsulamri/openhis.git

5. Create database

		mysql -u root -p
		create database his_open;
		exit

6. Restore the included sql file (his_open.sql) located in the openhis folder.

		mysql -u root -p his_open < his_open.sql

7. Move the openhis folder

		sudo mv openhis/ /var/www/html/

8. Change directory to /var/www/html/openhis/ and change the folder permission 

		cd /var/www/html/openhis/
		chmod -R gu+w storage
		chmod -R guo+w storage
		chmod -R gu+w bootstrap/cache
		chmod -R guo+w bootstrap/cache

9. While in the openhis/ directory 

		cp .env.example .env

	Edit the .env file and change the database connection parameters:

		DB_HOST=localhost
		DB_DATABASE=his_open
		DB_USERNAME=root
		DB_PASSWORD=password

10. Run the following command

		php artisan key:generate

11. Edit /etc/apache2/sites-available/000-default.conf and add the following lines:

		DocumentRoot /var/www/html/openhis/public

		<Directory "/var/www/html/openhis/public">
			Allowoverride All
		</Directory>

12. Run the following command and restart webserver

		a2enmod rewrite
		service apache2 restart

13. Finally open browser and enter http://localhost in the URL. Below is a list of users (username:password) you can start with.

	Patient Registration

		walter:password

	Physician

		shamsul:password

	Billing

		hidayat:password

	System Administrator

		sa:password

14. In order to understand how to use the application I have created a training script for the users to follow and understand the process of patient journey from registration to billing and everything in between. Please visit iodojo.com to use the training module and familirize yourself with the application. I use it as a replacement for face to face application training during this trying time. I will continue to add new content and updates the scripts regularly.

### Docker Container

Docker repository is at:

https://hub.docker.com/r/graymatter/openhis

### Web Resources

Homepage is at:

[https://iodojo.com](https://iodojo.com)

Demo application is at:

[https://his.iodojo.com](https://his.iodojo.com)

Training Script is at:

[https://dojo.iodojo.com](https://dojo.iodojo.com)


The scripts are a bit rough, going through updating process right now.

### License

OpenHIS is open-sourced software licensed under the GNU General Public License v3.0

In view of the pandemic situation right now I've decided to make this project open source to help reduce stress on hospital resources and hope that it will ease many of the process related to patient treatment. More update and releases coming soon.

Have fun and stay safe.

