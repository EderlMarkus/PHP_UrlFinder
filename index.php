<?php
require_once "php/dao/AccessObject.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $accessObject = new accessObject($_POST["urlName"]);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Test Gentics</title>

    <!-- Bootstrap -->
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous"
    />


    <link rel="stylesheet" href="css/style.css?123">
    <script src="./js/script.js?123"></script>
    <script src="./js/table.js?123"></script>

  </head>
  <body>
    <div class="container">
      <form method="post">
        <input required class="form-control mt-4" name="urlName" type="url" />
        <small id="urlNameHelp" class="form-text text-muted">Bitte eine gültige URL eingeben.</small>
        <button class="btn btn-primary mt-4 mb-4" type="submit">Suchen</button>
      </form>
    </div>
    <div class="container">

    <?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    echo "<h3> Your search: " . $accessObject->post . "</h3>";
}

?>
    <table class="table">

            <?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    //Filling of Table can be done by Client
    echo "<script>
    fillTable(" . ($accessObject->getUrlsAsJSON()) . ");
    </script>";
}
?>

    </table>
    </div>

  </body>
</html>

<?php

?>

