<?php 

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

    
} catch(Exception $e) {
    die("Error: " . $e -> getMessage());
}


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $message = trim($_POST['message']);
    //if have message
    if(!empty($message)){
        //see if guest or not
        $is_guest = !isset($_SESSION['user_id']);
        $user_id = $_SESSION['user_id'] ?? NULL;
        $fullname = $_SESSION['fullname'] ?? NULL;
        $user_name = $_SESSION['user_name'] ?? NULL;

        $stmt = $db -> prepare(
            "INSERT INTO Contact(isguest, user_id, fullname, user_name, message)
            values(?, ?, ?, ?, ?)
            "
        );
        $stmt -> bind_param("iisss", $is_guest, $user_id, $fullname, $user_name, $message);

        if($stmt -> execute()){
            echo "<p>Message Sent!</p>";
        } else{
            echo "<p>Error: Failed to send</p>";
        }

    } else{
        echo "<p>Error: Message is empty</p>";
    }

}
?>
<!DOCTYPE html>
<html>
<head> 
    <meta charset="UTF-8">
    <title><?php echo $config['site_name']?> -- <?php echo ucfirst($page); ?></title>
    <link type="text/css" rel="stylesheet" href="style.css">
    <style>

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
    <h2>
        <?php echo $pages[$page] ?? ucfirst($page); ?>
    </h2>

    
    <div class="message_hold">
        <p>You can leave your message here</p>
        <p>But if you did not logged in you will be consider as Guest</p>
        <form method="POST" action="">
            <textarea name="message" placeholder="You can leave your message here." required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>



</body>
</html>