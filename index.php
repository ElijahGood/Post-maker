<?php
error_reporting( E_ALL );
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ROOT', dirname(__FILE__));
$db_config = require ROOT.'/config/db.php';

try {
    $pdo = new PDO('mysql:host='.$db_config['host'].';dbname='.$db_config['name'].'', $db_config['user'], $db_config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = "SELECT * FROM posts ORDER BY id DESC";
    $query = $pdo->prepare($stmt);
    $query->execute();
    $data = $query->fetchAll();
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>
<!DOCTYPE html>  
 <html>  
    <head>  
        <title>POSTMAKER</title>  
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>
    <body>
       <br/>
        <div class="container">
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Добавить обьявление</button>
            <div class="modal" id="myModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Добавить обьявление</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-inlin justify-content-center" action="save_post.php" name="postForm" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">  
                                <div class="form-group">
                                    <label for="image">Загрузить фото</label>
                                    <input class="form-control-file" for="postForm" type="file" name="image" id="image" required/>
                                </div>
                                <div class="form-group">
                                    <label for="postname">Заголовок</label>
                                    <input class="form-control" for="postForm" type="text" name="postname" id="postname" required/>
                                </div>
                                <div class="form-group">
                                    <label for="desc">Описание</label>
                                    <textarea class="form-control" for="postForm" type="text" name="desc" id="desc"></textarea>
                                </div>
                                <input type="submit" name="new_photo" id="new_photo" value="Сохранить" class="btn btn-primary btn-lg btn-block" required/>  
                            </form> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="container">
            
                <?php
                    if ($data) {
                        foreach ($data as $item) {
                            echo '<div class="row mt-3">
                                    <div class="col-auto">
                                        <img src="data:image/jpg;base64,'. base64_encode($item['img']) .'" height="200" width="200" class="img-thumnail" />
                                    </div>
                                    <div class="col-sm">
                                        <div class="row"><h3>'.$item['postname'].'</h3></div>
                                        <div class="row">'.$item['description'].'</div>
                                    </div>
                                </div>';
                        }
                    }
                ?>
            </div>
        </div>   
    </body>  
 </html> 
 <script>
  
    function checkForm() {
        let name = String(document.postForm.postname.value);
        let postText = String(document.postForm.desc.value);
        let file = document.postForm.image.value;
        
        if (file && name && postText) {
            name.trim();
            postText.trim();

            if (name.length > 55) {
                alert("Заголовок должен быть не более 55-ти символов.");
                return false;
            } else if (!/\S/.test(name) || !/\S/.test(postText)) {
                alert("Одно из полей состоит только из пробелов.");
                return false;
            } else {
                return true;
            }
        } else {
            alert("Все поля должны быть заполнены.");
            return false;
        }
    }
 </script>
