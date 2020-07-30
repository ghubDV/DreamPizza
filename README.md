This represents a Pizza ordering app that brings a new ordering technique through Twitter.
To make the application work correctly on your machine, please read the instructions below:

!!!You'll need a web server solution stack to run this website... XAMPP or WAMP will do!!!

There are some configurations needed in order to make the application work correctly.
Please follow these steps before running the application on your server:

1. Import the MySQL database schema from the db folder inside the project. Schema file is called "dreampizza_schema.sql"

2. After importing the database it'll be necessary to configure the dbconn.php file inside the root directory of the project. You'll need to specify your database server, name and credentials

3. PHPMailer configurations are also required, so the app will be able to send e-mails to its users:
	- open the "sendMail.php" file from the project root folder in your text editor
	- there you'll need to set the SMTP server, the e-mail credentials that the application will use and the SMTP port
	- also, don't forget to modify the setFrom function arguments... those will indicate the e-mail adress from where it was sent, as well as the displayed name for the e-mail sender

4. For the Twitter ordering functionality to work you'll need to set your API credentials in the "get_tweets.php" file inside the root folder

5. The Google Maps API will need to be configured aswell. Inside the "contact.php" and "myaccount.php" files located in the project root folder. You Google Maps API key needs to be set for the maps to work.

6. It's possible that further PHP extensions may need to be enabled. Make sure that you have PHP v5.5+ installed.
