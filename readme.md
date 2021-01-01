## Open Hospital Information System (OpenHIS)

An open source hospital information system for small or large organisation. Contains all the essential modules for running a hospital from patient registration to billing. Built on top of LAMP stack with LARAVEL framework for scalability and great support from the community.

The system was initially designed for secondary care facility but it can also be used for primary care facility with minor adjustment. So if you are running a private clinic, hemodialysis center, homecare or any other primary care service it should be able to handle the requirement.

<img src='https://shamsulamri.github.io/assets/img/prescription.png' class='img-fluid border border-secondary'>

### Features

- Patient registration, appointment & scheduling
- Admission, Queue, Discharge and Transfer
- Clinical Consultation  
- Order Management
- Ward & Bed Management
- Diet & Kitchen Management
- Inventory & Stock management
- Drug Information & Prescription
- Custom Forms
- Patient Billing
- Medical Record 

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

2. MySQL (5.7.32)

	For details visits https://computingforgeeks.com/how-to-install-mysql-on-ubuntu-focal/

		sudo apt update
		sudo apt install wget -y
		wget https://dev.mysql.com/get/mysql-apt-config_0.8.12-1_all.deb

	Once downloaded, install the repository by running the command below:

		sudo dpkg -i mysql-apt-config_0.8.12-1_all.deb

	Run the below command to update your system packages

		sudo apt-get update

		sudo apt install -f mysql-client=5.7.32-1ubuntu18.04 mysql-community-server=5.7.32-1ubuntu18.04 mysql-server=5.7.32-1ubuntu18.04

3. Clone the project
	
		git clone https://github.com/shamsulamri/openhis.git

4. Create database

		mysql -u root -p
		create database his_open;
		exit

5. Restore the included sql file (his_open.sql) located in the openhis folder.

		mysql -u root -p his_open < his_open.sql

6. Move the openhis folder

		sudo mv openhis/ /var/www/html/

7. Change directory to /var/www/html/openhis/ and change the folder permission 

		cd /var/www/html/openhis/
		chmod -R gu+w storage
		chmod -R guo+w storage
		chmod -R gu+w bootstrap/cache
		chmod -R guo+w bootstrap/cache

8. While in the openhis/ directory 

		cp .env.example .env

	Edit the .env and change the database parameter:

		DB_HOST=localhost
		DB_DATABASE=his_open
		DB_USERNAME=root
		DB_PASSWORD=password

9. Run the following command

		php artisan key:generate

10. Edit /etc/apache2/sites-available/000-default.conf and add the following lines:

		DocumentRoot /var/www/html/openhis/public

		<Directory "/var/www/html/openhis/public">
			Allowoverride All
		</Directory>

11. Run the following command and restart webserver

		a2enmod rewrite
		service apache2 restart

12. Open browser and enter http://localhost in the URL.

### Docker Container

I will release a docker container for easy and quick installation soon.

### Web Resources

Homepage is at:

	https://shamsulamri.github.io

Demo application is at:

	https://iodojo.com

Training Script is at:

	https://dojo.iodojo.com

The scripts are a bit rough, going through updating process right now.

### License

OpenHIS is open-sourced software licensed under the GNU General Public License v3.0

In view of the pandemic situation right now I've decided to make this project open source to help reduce stress on hospital resources and hope that it will ease many of the process related to patient treatment. More update and releases coming soon.

Have fun and stay safe.

