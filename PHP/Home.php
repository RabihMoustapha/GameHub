<!--Login verify-->
<?php
session_start();
if ($_SESSION['isloggedin'] != 1) {
    header("Location:Login.php");
}
?>
<!--End of Login verify-->
<!--Insert the comment in database-->
<?php
require_once "Connection.php"; //include the connection
if (isset($_POST['text-area']) && isset($_POST['Name']) && isset($_POST['Date']) && isset($_FILES['File'])) { //Verify if the element is seting
    $textarea = $_POST['text-area'];
    $Name = $_POST['Name'];
    $Date = $_POST['Date'];
    if (!empty($_FILES['File']['name'])) { // if is not empty $_File
        $file = $_FILES['File']['name'];
        move_uploaded_file($_FILES['File']['tmp_name'], "../Images/" . $file); //move the uploded file to Images
    }
    $query = "INSERT INTO `home`(`Name` , `Comment` , `Date` , `Image`) VALUES ('" . $Name . "' , '" . $textarea . "' , '" . $Date . "' , '" . $file . "')"; //Add a query in home table
    $result = mysqli_query($Connection, $query); // Run the query
}
?>
<!--End of the inserting data-->
<!--Display the comment-->
<?php
require_once "Connection.php"; //Get connection to Home php from local host
if (isset($_POST['text-area']) && isset($_POST['Name']) && isset($_POST['Date'])) {
    $query1 = "SELECT * FROM home";
    $file = $_FILES['File']['name'];
    $result1 = mysqli_query($Connection, $query1);
    $nbr = mysqli_num_rows($result1);
    echo "<table border='6' cellpadding='20' class='table-comment' align='right'>
                <th>
                    Name
                </th>
                <th>
                    Comment
                </th>
                <th>
                    Date
                </th>
                <th>
                    Image
                </th>
                <th>
                    Download the image
                </th>
            </tr>";
?>
<?php
    for ($i = 0; $i < $nbr; $i++) {
        $row = mysqli_fetch_assoc($result1);
        $Image = $row['Image'];
        echo "<tr>";
        echo "<td>$row[Name]</td>";
        echo "<td><pre>$row[Comment]</pre></td>";
        echo "<td>$row[Date]</td>";
        echo "<td>$Image</td>";
        echo "<td><a href='../Images/" . $Image . "' download><img class='no-background' src='../Image.png'></a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
<!--End of display-->
<!doctype html>
<html>

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../Css/Home.css" rel="stylesheet" type="text/css">
</head>

<body>
    <header>
        <h2 class="logo">
            <i class="fa fa-sign-in" style="font-size:48px;color:red">
            </i>
        </h2>
        <nav class="navigation">
            <a class="not-visited">HOME</a>
            <a class="a1" href="About.php">ABOUT</a>
            <a class="a1" href="Service.php">SERVICES</a>
            <a class="a1" href="Contact.php">CONTACT</a>
            <a href="Log-out.php">
                <button class="btnlogin-popup">
                    LOGOUT
                </button>
            </a>
        </nav>
    </header>
    <!--Starting a form-->
    <div>
        <form name="Form" method="post" enctype="multipart/form-data">
            <table class="table" cellspacing="20">
                <tr>
                    <th>Name:</th>
                    <td>
                        <input required type="text" name="Name" placeholder="E.G write a name" required>
                    </td>
                </tr>
                <tr>
                    <th>Date:</th>
                    <td>
                        <input type="date" name="Date" required>
                    </td>
                </tr>
                <tr>
                    <th>Comment:</th>
                    <td>
                        <textarea cols="30" rows="10" name="text-area" placeholder="E.G write a comment" required></textarea>
                    </td>

                </tr>
                <tr>
                    <td colspan="3">
                        <input type="File" name="File" required>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button onclick="Form.action = 'Home.php'">Insert</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!--End the form-->
</body>

</html>