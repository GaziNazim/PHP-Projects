<?php
require ("Entities/CoffeeEntity.php");

//Contains database related code for the Coffee page.
class CoffeeModel {

    //Get all coffee types from the database and return them in an array.
    function GetCoffeeTypes() {
        require ('Credentials.php');
        //Open connection and Select database.   
        $conn = new mysqli($host, $user, $passwd, $database);

        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SELECT DISTINCT type FROM coffee");
        $types = array();

        //Get data from database.
        while ($row = $result->fetch_assoc()) {
            array_push($types, $row["type"]);
        }

        //Close connection and return result.
        $conn->close();
        return $types;
    }

    //Get coffeeEntity objects from the database and return them in an array.
    function GetCoffeeByType($type) {
        require ('Credentials.php');
        //Open connection and Select database.     
        $conn = new mysqli($host, $user, $passwd, $database);
        // Check connection
        if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
        }

        $query = "SELECT * FROM coffee WHERE type LIKE '$type'";
        $result = $conn->query($query);
        $coffeeArray = array();

        //Get data from database.
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $name = $row["name"];
            $type = $row["type"];
            $price = $row["price"];
            $roast = $row["roast"];
            $country = $row["country"];
            $image = $row["image"];
            $review = $row["review"];

            //Create coffee objects and store them in an array.
            $coffee = new CoffeeEntity($id, $name, $type, $price, $roast, $country, $image, $review);
            array_push($coffeeArray, $coffee);
        }
        //Close connection and return result
        $conn->close();
        return $coffeeArray;
    }

    function GetCoffeeById($id) {
        require ('Credentials.php');
        //Open connection and Select database.     
        $conn = new mysqli($host, $user, $passwd, $database);
        // Check connection
        if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
        }

        $query = "SELECT * FROM coffee WHERE id = $id";
        $result = $conn->query($query) or die(mysql_error());

        //Get data from database.
        while ($row = $result->fetch_assoc() ) {
            $name = $row["name"];
            $type = $row["type"];
            $price = $row["price"];
            $roast = $row["roast"];
            $country = $row["country"];
            $image = $row["image"];
            $review = $row["review"];

            //Create coffee
            $coffee = new CoffeeEntity($id, $name, $type, $price, $roast, $country, $image, $review);
        }
        //Close connection and return result
        $conn->close();
        return $coffee;
    }

    function InsertCoffee(CoffeeEntity $coffee) {
        $query = sprintf("INSERT INTO coffee
                          (name, type, price,roast,country,image,review)
                          VALUES
                          ('%s','%s','%s','%s','%s','%s','%s')",
                ($coffee->name),
                ($coffee->type),
                ($coffee->price),
                ($coffee->roast),
                ($coffee->country),
                ("Images/Coffee/" . $coffee->image),
                ($coffee->review));
        $this->PerformQuery($query);
    }

    function UpdateCoffee($id, CoffeeEntity $coffee) {
        $query = sprintf("UPDATE coffee
                            SET name = '%s', type = '%s', price = '%s', roast = '%s',
                            country = '%s', image = '%s', review = '%s'
                          WHERE id = $id",
                          ($coffee->name),
                ($coffee->type),
                ($coffee->price),
                ($coffee->roast),
                ($coffee->country),
                ("Images/Coffee/" . $coffee->image),
                ($coffee->review));

                //This code is used for save database. That is user don't enter
                //any special charecter in input field.

                //mysqli_escape_string($coffee->name),
                //mysqli_real_escape_string($coffee->type),
               // mysqli_real_escape_string($coffee->price),
               // mysqli_real_escape_string($coffee->roast),
               // mysqli_real_escape_string($coffee->country),
               // mysqli_real_escape_string("Images/Coffee/" . $coffee->image),
               // mysqli_real_escape_string($coffee->review));
                          
        $this->PerformQuery($query);
    }

    function DeleteCoffee($id) {
        $query = "DELETE FROM coffee WHERE id = $id";
        $this->PerformQuery($query);
    }

    function PerformQuery($query) {
        require ('Credentials.php');
        $conn = new mysqli($host, $user, $passwd, $database) or die(mysql_error());
        
        $result = $conn->query($query);
        $conn->close();
    }
}
?>