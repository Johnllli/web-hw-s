<?php

$config = require 'config.php';

$config = $GLOBALS['config'];
$page = $GLOBALS['current_page'];
$pages = $config['pages'];

try{
    $db = new mysqli(
        $config['db']['host'],
        $config['db']['username'],
        $config['db']['password'],
        $config['db']['database']
    );

    if ($db -> connect_error){
        throw new Exception("Database connection fail: " . $db -> connect_error); 
    }
}
catch(Exception $e){
    die("Error: " . $e -> getMessage());
}


$error = '';
$action = $_GET['action'] ?? 'login';//show login page

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    //for login
    if ($_POST['form_type'] === 'login'){
        $stmt = $db -> prepare ("SELECT id, fullname, password FROM users where user_name = ?");
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $result = $stmt -> get_result();

        if($result -> num_rows === 0)
        {
            $error = 'Username does not exist, plz register';
        } 
        else if(password_verify($password, $result -> fetch_assoc()['password'])){
            
            $result -> data_seek(0);
            $user = $result -> fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $username;
            $_SESSION['fullname'] = $user['fullname'];
            header("Location: index.php");

            exit;
        }
        else{
            $error = 'Password wrong';
        }
    }
    //for register
    else{
        if($_POST['password'] !== $_POST['sec_password']){
            $error = 'The two password is not the same';
        }
        else {
            $fullname = trim($_POST['fullname']);
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db -> prepare("INSERT INTO users (fullname, user_name, password) VALUES (?, ?, ?)");
            $stmt -> bind_param("sss", $fullname, $username, $hash_password);
            if($stmt -> execute()){
                
                $error = 'Registration DONE! Plz try to login.';
                $action = 'login';
            }
            else{
                $error = 'Useranme already exist try another one';
            }
        }
    }
}



?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link type="text/css" rel="stylesheet" href="style.css">
    <style>
        .switch a{
            border-radius: 4px;
            color: red;
            text-decoration: none;
        }
        .form-hold{
            width: fit-content;
            margin: 20px auto;
            padding: 20px;
            border: 1px soild #ddd;
            background-color: #f9f9f9;
            border-radius: 8px;

        }
        form{
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
    </style>
</head>
<body>
    <h1>Maybe big shop</h1>
    <nav>
        <div>
            
            <?php foreach ($pages as $key => $title): ?>
                <!-- check if is login show something else -->
                <?php if($key !== 'login' || !isset($_SESSION['user_id'])):  ?>
                    <a href="index.php?page=<?php echo $key?>">
                        <?php echo $title ?>
                    </a>
                <?php endif; ?>
            <?php endforeach ?>

            <?php if(isset($_SESSION['user_id'])): ?>
                <span>Hello, 
                    <?= $_SESSION['fullname'] ?>
                    (<?= $_SESSION['user_name'] ?>)
                    <a href="logout.php">Logout</a>
                </span>
            <?php endif; ?>    
        </div>

        
      
    </nav>


    <div class="form-hold">
        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if($action === 'login'): ?>
            <!-- login part -->
            <h2>Login</h2>
            <form method="post">
                <input type="hidden" name="form_type" value="login">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <div class="switch">NO account? <a href="?page=login&action=register">Click here to register</a> </div>
            <!-- register part -->
        <?php else: ?>
            <h2>Register</h2>
            <form method="post">
                <input type="hidden" name="form_type" value="register">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="sec_password" placeholder="Plz enter password again" required>
                <button type="submit">Register</button>
            </form>
            <div class="switch">Already have account? <a href="?page=login&action=login">Click here to Login</a> </div>
        <?php endif; ?>
    </div>
</body>
</html>