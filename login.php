<?php

if (isset($_GET['submitLogin'])) {
    // echo 'u click submit button';
    if (isset($_GET['username']) && isset($_GET['exampleInputPassword1'])) {

        $username = $_GET['username'];
        $password = $_GET['exampleInputPassword1'];
        $credential = $username . '|' . $password;
        echo $credential . strlen($credential) . '<br>';

        // open files 
        $myfile = fopen("users.txt", "r") or die("Unable to open file!");

        // reads from an open file
        // $read = fread($myfile, filesize("users.txt"));

        $arr = array();

        while (!feof($myfile)) {
            array_push($arr, fgets($myfile));
        }


        fclose($myfile);

        // print_r($arr);

        foreach ($arr as $val) {
            // echo preg_replace("/\r|\n/", "", $val).'<br>';
            if ($credential === preg_replace("/\r|\n/", "", $val)) {
                echo 'found!!';
            }
            echo strlen(preg_replace("/\r|\n/", "", $val)).'<br>';
        }
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container w-50 mt-5">
        <div class="card">
            <div class="card-header text-center fw-bold">
                PHP AUTH ASSIGNMENT
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">Login</h5>
                <p class="card-text"></p>
                <form action="login.php" method="GET">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="text" class="form-control" name="username" id="username" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">Enter your email address.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" name="exampleInputPassword1" id="exampleInputPassword1">
                        <div id="passwordHelp" class="form-text">Enter your password.</div>
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" name="submitLogin" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>

</html>