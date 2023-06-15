<?php
session_start();
if ($_SESSION['isloggedin'] != 1) {
    header("Location:Login.php");
}
?>
<!doctype html>
<html>

<head>
    <title>Main</title>
    <link href="../Css/Main.css" rel="stylesheet" type="text/css">
    <script src="../Java Script/Main.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <header>
        <h2 class="logo">
            <i class="fa fa-usb" style="font-size:48px;color:red">
            </i>
        </h2>
        <nav class="navigation">
            <form name="Form" method="post">
                <table cellspacing="10">
                    <tr>
                        <td>
                            <button onclick="Form.action = 'View.php'" class="btnlogin-popup">VIEW</button>
                        </td>
                        <!--Teleport-->
                        <td>
                            <button onclick="teleport()" class="btnlogin-popup">TELEPORT</button>
                        </td>
                        <!--End of teleport-->
                        <!--Log-out-->
                        <td>
                            <a href="Log-out.php">
                                <button class="btnlogin-popup">
                                    LOGOUT
                                </button>
                            </a>
                        </td>
                        <!--End of log-out-->
                    </tr>

                </table>
            </form>
        </nav>
    </header>
</body>

</html>