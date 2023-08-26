Task Management Web Application
Welcome to the Task Management Web Application README! This document will guide you through the process of setting up and running the project on your local machine.

Introduction
This project is a web application built using the Laravel framework that allows users to manage tasks. It provides a user-friendly interface to add, view, edit, and delete tasks. Additionally, it integrates with an external API to synchronize tasks periodically.

Prerequisites
Before you begin, make sure you have the following installed on your system:

PHP (recommended version: 7.4 or higher)
Composer
Node.js and npm
MySQL or another database of your choice
Git

Installation
1.Clone the Repository:
git clone https://github.com/waqarecp/test_project.git
cd task-management-app

2.Install Dependencies:
composer install
npm install

Configuration
1.Duplicate the .env.example file and rename it to .env:
cp .env.example .env

2.Configure the .env file with your database credentials and other settings.

3.Generate the Application Key:

php artisan key:generate

4.Set Up the Database:

Create a new database in your MySQL instance and update the .env file with the database name and credentials.

DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=

Running the Application
1.Run Migrations:
php artisan migrate

2.Start the Development Server:
php artisan serve

You can access the application in your web browser at http://localhost:8000.

Contributing
Contributions are welcome! If you have suggestions or improvements, feel free to submit a pull request.

License
This project is licensed under the MIT License. You are free to use, modify, and distribute it.

Congratulations! You've successfully set up the Task Management Web Application project. If you have any questions or encounter any issues, feel free to refer to the documentation or ask for help.

Enjoy managing your tasks with ease using this web application!


This project would get the data from external JSON API and would load into the DB.
You can add new task which will save the new task into DB tasks table and you can edit that using JS model which is quicker approach
for instant edit.