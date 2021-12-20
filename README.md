# arena350

A website for buying sport event tickets, written in PHP

## Setup


Install XAMPP for the relevant OS per the instructions [here](https://www.apachefriends.org/download.html).

Open XAMPP and click 'Manage Servers'. Ensure that `MySQL Server` and `Apache Server` are running.

Clone this Github repository in the local folder that the Apache server serves from. On Mac, this will be `/Applications/XAMPP/htdocs`. 

### Run the migration in MySQL

All SQL statements should be entered through PHPAdmin, which runs through XAMPP at `http://localhost/phpmyadmin/index.php?route=/server/sql`.

Create a new database in the XAMPP MySQL local server:

```
CREATE DATABASE IF NOT EXISTS `arena350`;
```

After selecting the newly created database via:

```
USE DATABASE `arena350`;
```

Under `arena350/migrations`, copy paste the contents of `db.sql` into  and hit 'Go'.
Do the same with `mockdata.sql` - this will seed the database with mock event and ticket data.

Navigate to the home page by going to `http://localhost/arena350`, this is the login page.

## Important User Flows

### Login

- User logs in with valid credentials
- User is redirected to home page

### Signup
- If a visitor doesn't have a registered user, they can sign up with their email as the username and a valid password
- On successful signup, they are redirected to the login page to re-enter their credentials

### Adding to cart
- On clicking on a displayed event, the user can view event information and add a ticket to cart
- On adding to cart, the # of tickets will be displayed in the cart icon in the top-right and the user is redirected to the cart page
- Cart items are retrieved on login and persist through the duration of a user's session
- If tickets for an event run out, the user is notified and cannot add tickets to cart

### Purchasing
- On the cart page, a user can click 'Purchase' to prevent other users from buying their tickets

Login -> onSuccess -> Home page -> Click on 
```

## Pages

### HOME page:
The user has access to the home page, she/he can see the upcoming sports event.
The events are displayed in ascending order by date. The user can click on the name of any event to open the information about it. 

### EVENT page:
The event's page has individual information about each of the sports events, and the "Add to Cart" bottom.

### CART:
The Cart stores the chosen tickets, estimates the cost of the ticket/tickets, shows the date the ticket/tickets were added to the Cart. 
This page allows the user to empty Cart with the "Empty Cart" bottom, or remove a ticket individually with the "Remove" bottom.
The user can make a purchase by clicking on the "Place Order" bottom.
Each time a ticket is added or removed the number next to the cart image (on the nav-bar) is going to change.

### PROFILE page:
The user can go to the Profile page to see the order's history displayed in ascending order by date.

### ABOUT page:
The About page consist of the information related to the Arena350, its creation, and work hours.

The user then should Log out.
