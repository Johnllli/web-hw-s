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

$errors = [];
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $message = trim($_POST['message']);
    //check 
    if(empty($message)){
        $errors[] = "Message cannot be empty";
    } elseif(strlen($message) > 1000){
        $errors[] = "Message cannot be more than 1000 characters";
    }

    //if have message
    if(empty($errors)){
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
            $_SESSION['success'] = "Message sent successful";
            header("Location: index.php?page=contact");
            exit();
        } else{
            $errors[] = "Failed to send message. Try again.";
        }

    } 

}
?>
<!DOCTYPE html>
<html>
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['site_name']?> -- <?php echo ucfirst($page); ?></title>
    <link type="text/css" rel="stylesheet" href="style.css">
    <style>

    </style>
</head>

<body>

    <h1>Maybe big shop</h1>
    <nav>
        
        <ul>
            <?php foreach ($pages as $key => $title): ?>
                <!-- check if is login show something else -->
                <?php if($key !== 'login' || !isset($_SESSION['user_id'])):  ?>
                    <li>
                        <a href="index.php?page=<?php echo $key?>">
                            <?php echo $title ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach ?>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <li class="user-info">
                    <span>Hello, 
                        <?= $_SESSION['fullname'] ?>
                        (<?= $_SESSION['user_name'] ?>)
                    </span>
                    <a href="logout.php">Logout</a>
                </li>
            <?php endif; ?>
             
        </ul>
    </nav>
    <h2>
        <?php echo $pages[$page] ?? ucfirst($page); ?>
    </h2>

    <!-- message part -->
    <div class="message_hold">
        <p>You can leave your message here</p>
        <p>But if you did not logged in you will be consider as Guest</p>
        
        <?php if(!empty($success)): ?>
            <div class="success"><?php echo $success ?></div>
        <?php endif; ?>
        
        <?php if(!empty($errors)): ?>
            <?php foreach($errors as $error): ?>
                <div class="error"><?php echo $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <!-- message form -->
        <form id="messageform" method="POST" action="index.php?page=contact">
            <textarea name="message"
            placeholder="You can leave your message here (max 1000 characters)"></textarea>
        <button type="submit">Submit</button>
        </form>

        
    </div>
    <script>
        document.getElementById('messageform').addEventListener('submit', function(e){
            const message = 
            document.querySelector('textarea[name="message"]').value.trim();

            if(message === ''){
                e.preventDefault(); // Stop form submission
                alert('Please enter some message before submit');
                return false;
            }
            return true;
        })
    </script>


</body>
</html>