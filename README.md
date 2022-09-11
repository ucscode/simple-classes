# simple-classes
A list of PHP classes created to make any project development easy

Copyright (c) by UCSCODE

### Why make things harder for yourself? 
##### Download any of the above PHP Class files and see how easy your programming task becomes

## Events
`events` is a php class that enables you paste handles in different area of your code and execute multiple functions in those areas when needed without editing the code.

```php
  # Example;
  <!doctype>
  <html>
   <head>
      <title>Your Domain</title>
      <?php events::exec("header-scripts"); ?>
   </head>
  <body>...</body>
  </html>
```
You can see from the above html script that we executed an event handle named "header-scripts". 

So let's say you want to add a google anaytics code to the <head> section, or you want to add you own custom script. 
  
Whatever it is, you don't need to edit the file again, you just need to define a listener.

```php
  <?php 

    events::addListener("header-scripts", function() {
     // print your google analytics code here
    });

    events::addListener("header-scripts", function() {
      // print your custom code here
      // print some other code here
    });
```

### sQuery
Do you always write you code like this each and everytime:
```php
  $SQL = "INSERT INTO `users` (name, email, password) VALUES ('ucscode', 'me@email.com', '****');
  // $mysqli->query( $SQL );
```
It's very common to repeat sql codes since you'll most likely be interacting with database all the time.

Well, sQuery helps you generate the string in a more simplified way. The example below shows how to get the same SQL string using sQuery class
```php
  $data = array(
    "name" => "ucscode",
    "email" => "me@email.com",
    "password" => '****'
  );

  $SQL = sQuery::insert( "users", $data );
  // $mysqli->query( $SQL );
```

### Pairs
Have you ever created a database that required too much droping and creating of columns? Especially something like admin settings? Then you should consider using pairs

Pairs requires a specific kind of table in the format:
```sql
  CREATE TABLE IF NOT EXISTS `tablename` (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT
    _key varchar(255) NOT NULL,
    _value text
  );
```
If a pair table does not exist, it will be created by the Pairs class. So how does it work?
```php
  $mysqli = new mysqli(...your config);
  $pair = new pairs($mysqli, 'tablename'); 
```
Now that pairs has been created, how do you modify values
```php
  $pairs->set("email", "new@email.com"); // true
  $pairs->get("email"); // 'new@gmail.com'
  $pairs->remove("email"); // true;
```
