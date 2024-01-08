<p align="center">
  <img src="https://github.com/leo-tasso/Hilfe/blob/master/LogoRes/LogoHeartBorder.svg?raw=true" width="400" />
</p>

# Hilfe - Hands in love fostering empowerment
A social network website written in php to coordinate manage and support voluntary social help.
The name comes after the verb to help in german that composes the acronym: ***"Hands in love fostering empowerment"***.

The idea arose from the floods that hit the Romagna region in 2023, in that moment the use of a platform such this one could have been a great aid for the management of the help to the affected population and to the potential volunteers. 

## Setup
* Clone the repo and copy the contents of "website" inside your web server (e.g. hdocs for xampp).

* add a "secrets.php" file to the same folder containing: 
```php
<?php define("EMAIL", "fooYourEmail@gmail.com"); ?>
```

* launch the script create.sql inside docs/dbDiagram/ to create the tables inside the mysql dmbs.
* the website should be up and running

## Documentation
- inside the doc folder it is possible to find docs regarding the db tables and the logos and icons source files.
- the entry point of the website is index.php, all the other pages are inside the root folder, the rest of the structure is self explanatory.
  

