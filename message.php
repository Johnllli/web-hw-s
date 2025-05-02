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

    

} catch(Exception $e){
    die("Error: " . $e -> getMessage());
}

$query = "SELECT * FROM contact order by message_time DESC";
$result = $db -> query($query);
$s = [];
while ($row = $result -> fetch_assoc()){
    $messages[] = $row;
}


?>


<!DOCTYPE html>
<html>
<head>
    <title><?php echo $config['site_name']?> -- <?php echo ucfirst($page); ?></title>
    <link type="text/css" rel="stylesheet" href="style.css">
    <style>
        .login-require {
        padding: 20px;
        background-color: #ffebee;
        border: 1px solid #ffcdd2;
        }
        td{
            margin: 20px;
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
    <h2>
        <?php echo $pages[$page] ?? ucfirst($page); ?>
    </h2>

    <?php if(!isset($_SESSION['user_id'])): ?>
        <div class="login-require">
            Plz <a href="index.php?page=login">login</a>
            to view the page.
        </div>
    <?php else: ?>
        <div class="message_hold">
            <h3>Messgaes Will show below</h3>

            <?php if(empty($messages)): ?>
                <div class="no_message">
                    NO message in the database yet.
                </div>
            <?php else: ?>
                <table class="message_table">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Timestemp</th>
                            <th>Messgae</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr>
                                <td>
                                    <!-- name= -->
                                    <?php if($message['isguest']): ?>
                                        <span>Guest</span>
                                    <?php else: ?>
                                        <span>
                                            <?= $_SESSION['fullname'] ?>
                                            (<?= $_SESSION['user_name'] ?>)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- time -->
                                    <?= htmlspecialchars($message['message_time']) ?>
                                </td>
                                <td>
                                    <!-- message -->
                                    <?= htmlspecialchars($message['message']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>                           
        </div>
    <?php endif; ?>
</body>
</html>