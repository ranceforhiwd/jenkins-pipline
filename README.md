<h3>Dockerized Dolibarr w/ MariaDB and Webserver</h3>

This project is useful for developers exploring the Dolibarr API in a docker container.  The network also has a separate webserver for applications development and communcations with Dolibarr for business entity data.  Developers can use this network as a testbench for api calls and application data exchanges. 

The application container serves a webpage form to collect end-user data, then pass data with parameters to Dolibarr.  A return page is provided with information about the processing of data sent via the form.  Javascript is used as a frontend for the application alone with some minor CSS.

<h3>How to use it</he>

1.  Download or pull a branch, install on a local computer.
2.  Download the 'Products' spreadsheet located in the 'data' directory
3.  Navigate to the working directory where the files are located
4.  Run this:  docker compose -f docker-compose.yaml up
5.  Open a browser to url:  http://localhost

<h3>Configuration</h3>
Dolibarr default account:
  username: admin
  password: admin
  
Once installed and logged in, navigate to Setup - Company, then enter company information.  Also activate the following Dolibarr Modules:
  1.  Third-Party
  2.  Commercial Proposals
  3.  REST API Module
  4.  Products
  5.  Services
  6.  Import Data
  7.  Export Data
  
Final setup is to navigate to the Import Module and import products from the previously downloaded spreadsheet by following the import wizard instructions.

<h3>Testing</h3>

Open a browser tab to:  http://localhost:8080

A form should display. A result screen should display upon submission of the form.
