<?php
$config = $GLOBALS['config'];
$page = $GLOBALS['current_page'];
$pages = $config['pages'];
//get image
$images = [];
if(file_exists('uploads') && is_dir('uploads'))
{
    $files = scandir('uploads');
    //if ok add in array
    foreach ($files as $file)
    {
        if($file !== '.' && $file !== '..')
        {
            $images[] = $file;
        }
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' 
&& isset($_FILES['image'])
&& isset($_SESSION['user_id'])){
    $targetdir = 'uploads/';
    //if no dir create one
    if(!file_exists($targetdir)){
        mkdir($targetdir, 0777, true);
    }

    $targetfile = $targetdir . basename($_FILES["image"]["name"]);
    $uploadok = 1;
    $imagefiletype = strtolower(pathinfo($targetfile, PATHINFO_EXTENSION));

    //max size
    if ($_FILES["image"]["size"] > 2000000){
        $uploaderror = "Sorry, you file is tooo big.";
        $uploadok = 0;
    }
    //format
    if($imagefiletype != "jpg" 
    && $imagefiletype != "png" 
    && $imagefiletype != "jpeg"
    && $imagefiletype != "gif"){
        $uploaderror = "Sorry only jpg, png, jpeg, git can enter";
        $uploadok =  0;
    }
    //upload success
    if($uploadok == 1)
    {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetfile)) 
        {
            $uploadsuccess = "File uploaded success.";
        } 
        else 
        {
            $uploaderror = "Failed to save file.";
        }
    } 
    else
    {
        $uploaderror = "SOmething went wrong";
    }

    
   


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
        .mainspace{
            margin: 20px;
        }
        .message{
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success{
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;

        }
        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
        .upform{
           margin: 20px;
           padding: 20px;
           border: 1px solid #ddd;
           background-color: #f9f9f9; 
        }
        .imagespace{
            display: flex;
            flex-wrap:  wrap;
            gap: 15px;
            padding: 15px;
        }
        .imagespace img{
            width:200px;
            height: 200px;
            border: 20px soild red;
            border-radius: 4px;
            transition: transform 0.3s;
        }
        .imagespace img:hover{
            transform: scale(1.1);
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

    <h2><?= $pages['shop'] ?></h2>
    <?php if(!isset($_SESSION['user_id'])): ?>
        <div class="login-require">
            Plz <a href="index.php?page=login">login</a>
            to view the page.
        </div>
    <?php else: ?>
        <div class="mainspace">
            <h3>Images SPACE</h3>
            
            <?php if(isset($uploadsuccess)): ?>
                <div class="message success">
                    <?= $uploadsuccess ?>
                </div>
            <?php endif; ?>

            <?php if(isset($uploaderror)): ?>
                <div class="message error">
                    <?= $uploaderror ?>
                </div>
            <?php endif; ?>

            <!-- upload form -->
            <div class="upform">
                <h4>Upload New Visual item</h4>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" name="image" accept="image/*" required>
                    <button type="submit">Upload Image</button>
                </form>
            </div>    
            <h2>These are visual item you may or may not buy</h2>
            <div class="imagespace">
                <?php if(empty($images)): ?>
                    <p>NO IMAGE</p>
                <?php else: ?>
                    <?php foreach ($images as $image): ?>
                        <img src="uploads/<?= $image ?>" alt="<?= $image ?>">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>


        </div>
    <?php endif; ?>


</body>

</html>