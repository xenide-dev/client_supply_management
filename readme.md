# Supply and Equipment Management System

A simple web-based management system that focused on supplies and equipments for a government. It was created using simple php with no framework on the frontend.

![cover image](https://github.com/xenide-dev/client_supply_management/blob/master/assets/images/cover.jpg)

## Future Plan

* We're planning to convert this project into framework-based like Laravel so that it can be easily maintained.

## Features

* Basic CRUD for Item / Equipment and Category; It support adding by bulk thru CSV.
* Can manage the following transaction
  * Employee's Item / Equipment Request
  * Purchase' Orders
  * Project Procurement Management Plan (PPMP)
  * Supplies
* Item / Equipment Monitoring through QR Code
* Can generate reports
* Account Management
* Log support

## Installation

* Just clone the repo and save it to www or htdoc directory
* Import the included sql file much better to use the file with 'create_database' on it so that you don't have to create a database by yourself.
* Edit the connection settings from main/connection/connection.php
* Go to your browser and type in the address bar "localhost/client_supply_management/main"
