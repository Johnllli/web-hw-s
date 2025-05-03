<?php
$config = $GLOBALS['config'];
$page = $GLOBALS['current_page'];
$pages = $config['pages'];
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['site_name']?> -- <?php echo ucfirst($page); ?></title>
    <link type="text/css" rel="stylesheet" href="style.css">
    <style>
        
        
        .video_hold{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .video_wrapper{
            flex: 1;
            max-width: 300px;
            margin: 20px;
        }
        .user_info{
            float: right;
        }
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
    <p>
        Welcome to Maybe big shop website, in here you maybe can buy some visual item, after you buy it, it will be in your mind visualy
    </p>



    
    <div class="video_hold">
        
        <div class="video_wrapper">
            <h3>Local video</h3>
            <video controls width="300" height="200">
                <source src="src/5secvideo.mp4" type="video/mp4">
                Maybe you brower does not suppor this.
            </video>
        </div>

        <div class="video_wrapper">
            <h3>Youtube viveo</h3>
            <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
            </iframe>
        </div>
    </div>

</body>
</html>