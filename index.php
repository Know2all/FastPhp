<?php
    include 'Config.php';
    include 'Manager.php';
    $model = new Manager($conn);
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['create'])): 
        // print_r($_POST);
        $data = array(
            "username"=>$_POST['username'],
            "password"=>$_POST['password'],
        );
        $result = $model->create("tbl_user",$data);
        if($result){
            echo "<script>alert('Inserted');</script>";
        }
    endif;
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update'])):
        $data = array(
            "password"=>$_POST['password'],
        );
        $username = $_POST['username'];
        $result = $model->update("tbl_user",$data,"username='{$username}'");
        if($result){
            echo "<script>alert('Updated');</script>";
        }
    endif;
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete'])):
        $username = $_POST['username'];
        $result = $model->delete("tbl_user","username='{$username}'");
        if($result){
            echo "<script>alert('Deleted');</script>";
        }
    endif;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="row p-5">
        <div class="col-6 card p-3">
            <form action="" method="post">
                <label class="form-label" for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Admin" value="Admin" required>
                <label class="form-label" for="password">Password</label>
                <input type="text" name="password" id="password"  class="form-control" placeholder="admin@123">
                <div class="flex flex-row gap-2">
                    <button type="submit" name="create" class="mt-2 btn btn-secondary">Insert</button>
                    <button type="submit" name="update" class="mt-2 btn btn-secondary">Update</button>
                    <button type="submit" name="delete" class="mt-2 btn btn-secondary">Delete</button>
                    <button type="submit" name="fetchall" class="mt-2 btn btn-secondary">FetchAll</button>
                    <button type="submit" name="fetchone" class="mt-2 btn btn-secondary">FetchOne</button>
                </div>
            </form>
        </div>
        <div class="col-6">
            <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['fetchall'])): ?>
            <?php $data = $model->fetchAll("tbl_user"); ?>
            <table class="table table-bordered">
                <thead>
                    <th>Sno</th>
                    <th>Name</th>
                    <th>Password</th>
                </thead>
                <tbody>
                    <?php if($model->affected_rows > 0):?>
                    <?php foreach($data as $index => $item):?>
                    <tr>
                        <td><?php echo $index; ?></td>
                        <td><?php echo $item['username'];?></td>
                        <td><?php echo $item['password'];?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr class="text-center">
                        <td colspan="3">No User Found Please try valid username</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php elseif($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['fetchone'])): ?>
            <?php 
                $username = $_POST['username'];
                $data = $model->fetchOne("tbl_user","username='{$username}'"); 
            ?>
            <table class="table table-bordered">
                <thead>
                    <th>Sno</th>
                    <th>Name</th>
                    <th>Password</th>
                </thead>
                <tbody>
                    <?php if($model->affected_rows > 0):?>
                    <tr>
                        <td><?php echo 1; ?></td>
                        <td><?php echo $data['username'];?></td>
                        <td><?php echo $data['password'];?></td>
                    </tr>
                    <?php else: ?>
                    <tr class="text-center">
                        <td colspan="3">No User Found Please try valid username</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-6 px-5">
            <h6>Insert Logic</h6>
            <pre>
                include 'Config.php'; //data base configuration
                include 'Manager.php'; //model file for communicate with database
                $model = new Manager($conn);
                if ($_SERVER['REQUEST_METHOD'] == "POST"): 
                    // print_r($_POST); its is used to the data received from client side correctly
                    $data = array(
                        "username"=>$_POST['username'], // the key username is actually in columname in database
                        "password"=>$_POST['password'], //the key password is actually in columname in database
                    );
                    $result = $model->create("tbl_user",$data); //passed new data as an array it returns true if affected rows > 0
                    if($result){
                        echo "Inserted";
                    }
                endif;
            </pre>
        </div>
        <div class="col-6">
            <h6>Update Logic</h6>
            <pre>
                include 'Config.php'; //data base configuration
                include 'Manager.php'; //model file for communicate with database
                $model = new Manager($conn);
                if ($_SERVER['REQUEST_METHOD'] == "POST"): 
                    // print_r($_POST); its is used to the data received from client side correctly
                    $data = array(
                        "password"=>$_POST['password'], //the key password is actually in columname in database
                    );
                    $username = $_POST['username'];
                    $result = $model->update("tbl_user",$data,"username='{$username}' AND columName='{variable}'"); // first parameter is tablename,second parameter is new data,third parameter is condition 
                    if($result){
                        echo "Updated";
                    }
                endif;
            </pre>
        </div>
    </div>
    <div class="row">
        <div class="col-6 px-5">
            <h6>Delete Logic</h6>
            <pre>
                include 'Config.php'; //data base configuration
                include 'Manager.php'; //model file for communicate with database
                $model = new Manager($conn);
                if ($_SERVER['REQUEST_METHOD'] == "POST"): 
                    // print_r($_POST); its is used to the data received from client side correctly
                    $username = $_POST['username'];
                    $result = $model->delete("tbl_user","username='{$username}'"); // first parameter is tablename , second paramater is condition
                    if($result){
                        echo "Deleted";
                    }
                endif;
            </pre>
        </div>
        <div class="col-6">
            <h6>FetchAll Logic</h6>
            <pre>
                include 'Config.php'; //data base configuration
                include 'Manager.php'; //model file for communicate with database
                $model = new Manager($conn);
                $data = $model->fetchAll("tbl_user","id='1' AND username='anwar'"); // it takes two arguments 1.tableName 2.condition
                if($model->affected_rows > 0){
                    print_r($data); // its displays an associated array of data
                    foreach($data as $index => $item){
                        echo "Sno : ".$item['key'];
                    }
                }else{
                    echo "No Data";
                }
                endif;
            </pre>
        </div>
    </div>
</body>
</html>