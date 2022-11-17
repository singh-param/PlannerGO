<!-- login.php is used for login into user account -->
<?php 

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

$errors = array();

if (isset($_POST['login-submit'])) {
    include 'includes/library.php';
    $pdo = connectDB();

    $query="select * from cois3420_finalProject_users where username=?";
    $stmt=$pdo->prepare($query);
    $stmt->execute([$username]);

    if($row=$stmt->fetch()){
        if(password_verify($password, $row['password'])){

            session_start();
            $_SESSION['userid'] = $row['userid'];

            //set cookie if box checked
            if (isset($_POST['rememberme'])){
            setcookie("username",$username,time()+60*60*24*30);
            setcookie("password",$password,time()+60*60*24*30);
            }

            header("Location:userAccount/account.php");
            exit();
        }
        else{
            $errors['login'] = true;
        }
    }
    else{
        $errors['user'] = true;
    }  
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <?php 
    $page_title = "Login";
    include 'includes/metadata.php'; ?>
  </head>

  <body>

      <?php include 'includes/navbar.php'; ?>
      <section class="form">
        <form name="login-form" id="login-form" action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off" novalidate>
          <div>
              <label for="username">Username: <span>*</span></label>
              <input id="username" name="username" type="text"  required value="<?php if(isset($_COOKIE['username'])){ echo $_COOKIE['username']; } ?>"/>
          </div>
          <div>
              <label for="password">Password: <span>*</span></label>
              <input id="password" name="password" type="password"  required value="<?php if(isset($_COOKIE['password'])){ echo $_COOKIE['password']; } ?>"/>
          </div>
          <div>
                <span class="error <?=!isset($errors['user']) ? 'hidden' : "";?>">That user doesn't exist</span>
                <span class="error <?=!isset($errors['login']) ? 'hidden' : "";?>">Incorrect login info</span>
          </div>
          <div class="rememberme">
              <input id="rememberme" name="rememberme" type="checkbox"/>
              <label for="rememberme">Remember me</label>
          </div>
          <div>
              <button id="login-submit" name="login-submit" class="submit">Submit</button>
          </div>
        </form>
        <div class="resetpass">
            <a href="reset.html">Forgot Password?</a>
        </div>
    </section>
    <?php include 'includes/footer.php'; ?>
    </body>
</html>

