<?php
$config = $GLOBALS['config'];
$page = $GLOBALS['current_page'];
$pages = $config['pages'];
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $config['site_name']?> -- <?php echo ucfirst($page); ?></title>
    <link type="text/css" rel="stylesheet" href="style.css">
    <style>
        
        .login{
            display: flex;
            gap: 10px;
        }
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