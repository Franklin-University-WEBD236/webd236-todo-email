<?php

class UserController extends Controller {

  protected $model;
  
  public function __construct() {
    parent::__construct();
    $this->model = 'UserModel';
  }

  public function get_login() {
    $this->view->renderTemplate(
      "views/user_login.php",
      array(
        'title' => 'Log in',
      )
    );
  }

  public function post_login() {
    $form = safeParam($_POST, 'form');
    $email = safeParam($form, 'email');
    $password = safeParam($form, 'password');

    $user = $this->model::findByEmail($email);
    if (!$user || !password_verify($password, $user->password)) {
      $errors = array("Bad username/password combination");
      $this->view->renderTemplate(
        "views/user_login.php",
        array(
          'title' => 'Log in',
          'form' => $form,
          'errors' => $errors,
        )
      );
    } else {
      $destination = isset($_SESSION['redirect']) ? $_SESSION['redirect'] : "/";
      restartSession();
      $_SESSION['user'] = $user;
      $this->view->flash("Login successful!");
      $this->view->redirect($destination);
    }
  }

  public function get_logout() {
    restartSession();
    $this->view->redirectRelative('');
  }

  public function get_register() {
    $this->view->renderTemplate(
      "views/user_register.php",
      array(
        'title' => 'Create an account',
        'form' => array(),
        'action' => $this->view->url('user/register'),
      )
    );
  }

  public function post_register() {
    $form = safeParam($_POST, 'form');
    if (!$form) {
      die ("no form submitted.");
    }
    $validator = new Validator();
    $validator->required('firstName', safeParam($form, 'firstName'), "First name is required.");
    $validator->required('lastName', safeParam($form, 'lastName'), "Last name is required.");
    $validator->password('password1', safeParam($form, 'password1'), "Password must have at least 8 characters, a number, an uppercase, and a symbol.");
    $validator->same('password2', safeParam($form, 'password1'), safeParam($form, 'password2'), "Passwords must match.");
    $validator->email('email1', safeParam($form, 'email1'), "Invalid email address given.");
    $validator->same('email2', safeParam($form, 'email1'), safeParam($form, 'email2'), "Email addresses must match.");
    if (!$validator->hasErrors()) {
      $user = new UserModel(array(
        'email' => trim($form['email1']),
        'password' => password_hash($form['password1'], PASSWORD_DEFAULT),
        'firstName' => trim($form['firstName']),
        'lastName' => trim($form['lastName']),
      ));
      $user->insert();
      restartSession();
      $_SESSION['user'] = $user;
      $this->view->flash("Welcome to To Do List, {$user['firstName']}.");
      $this->view->redirectRelative("");
    } else {
      $this->view->renderTemplate(
        "views/user_register.php",
        array(
          'title' => 'Create an account',
          'form' => $form,
          'errors' => $validator->allErrors(),
          'action' => $this->view->url('user/register'),
        )
      );
    }
  }

  public function get_edit($id) {
    $this->ensureLoggedIn();
    if ($id != $_SESSION['user']->id) {
      die ("Can't edit someone elses profile.");
    }
    $user = $this->model::findUserById($id);
    $this->view->renderTemplate(
      "views/user_edit.php",
      array(
        'title' => 'Edit your profile',
        'action' => $this->view->url("user/edit/${user['id']}"),
        'form' => array(
          'firstName' => $user['firstName'],
          'lastName'  => $user['lastName'],
          'email1'    => $user['email'],
          'email2'    => $user['email'],
        )
      )
    );
  }

  public function get_password($id) {
    $this->ensureLoggedIn();
    if ($id != $_SESSION['user']->id) {
      die("Can't change someone elses password.");
    }
    $user = $this->model::findUserByID($id);
    $this->view->renderTemplate(
      "views/user_change_password.php",
      array(
        'title' => 'Change your password',
        'form' => array(
          'currentPassword' => '',
          'newPassword1' => '',
          'newPassword2' => '',
        ),
        'id' => $id,
      )
    );
  }

  public function post_password($id) {
    $this->ensureLoggedIn();
    $user = $this->model::findUserByID($id);
    if ($id != $_SESSION['user']->id) {
      die("Can't change someone elses password.");
    }
    $form = safeParam($_POST, 'form');
    if (!$form) {
      die("No data submitted.");
    }
    $validator = new Validator();
    $validator->required('currentPassword', safeParam($form, 'currentPassword'), "Current password is required.");
    $validator->passwordMatch('currentPassword', safeParam($form, 'currentPassword'), $user->password, "Incorrect current password.");
    $validator->password('newPassword1', safeParam($form, 'newPassword1'), "Password must have at least 8 characters, a number, an uppercase, and a symbol.");
    $validator->same('newPassword2', safeParam($form, 'newPassword1'), safeParam($form, 'newPassword2'), "Passwords must match.");
    if (!$validator->hasErrors()) {
      $user->password = password_hash(safeParam($form, 'newPassword1'), PASSWORD_DEFAULT);
      $user->update();
      $_SESSION['user'] = $user;
      $this->view->flash("Password updated");
      $this->view->redirectRelative("");
    } else {
      $this->view->renderTemplate(
        "views/user_change_password.php",
        array(
          'title' => 'Change your password',
          'form' => $form,
          'id' => $id,
          'errors' => $validator->allErrors(),
        )
      );
    }
  }

  public function post_edit($id) {
    $this->ensureLoggedIn();
    $user=$_SESSION['user'];
    if ($id != $user['id']) {
      die("Can't edit somebody else.");
    }
    $form = safeParam($_POST, 'form');
    if (!$form) {
      die("no data submitted.");
    }
    $validator = new Validator();
    $validator->required('firstName', safeParam($form, 'firstName'), "First name is required.");
    $validator->required('lastName', safeParam($form, 'lastName'), "Last name is required.");
    $validator->email('email1', safeParam($form, 'email1'), "Invalid email address given.");
    $validator->same('email2', safeParam($form, 'email1'), safeParam($form, 'email2'), "Email addresses must match.");
    if (!$validator->hasErrors()) {
      $user->email = $form['email1'];
      $user->firstName = $form['firstName'];
      $user->lastName = $form['lastName'];
      $user->update();
      $this->view->flash("Profile updated");
      $_SESSION['user'] = $user;
      $this->view->redirectRelative("");
    } else {
      $this->view->renderTemplate(
        "views/user_edit.php",
        array(
          'title' => 'Edit your profile',
          'form' => $_POST['form'],
          'errors' => $validator->allErrors(),
          'action' => $this->view->url("user/edit/${user['id']}"),
        )
      );
    }
  }
  
  public function get_reset_password() {
    $this->view->renderTemplate(
      "views/user_reset_password.php",
      array(
        'title' => 'Password reset',
      )
    );
  }

  
  public function post_reset_password() {
    $form = safeParam($_POST, 'form');
    $email = safeParam($form, 'email');

    $user = $this->model::findByEmail($email);
    if ($user) {
      // send the email here
      $token = getRandomToken(50);
      $user->token = $token;
      $user->update();
      
      $link = "https://" . $_SERVER['SERVER_NAME'] . $this->view->url("user/password_token") . "/" . $token;
      $message = $this->view->renderTemplate(
        "views/user_reset_password_email.php",
        array(
          'url' => $link
        ),
        true
      );

      $emailService = new Email();
      //$emailService->test();
      $emailService->send($email, "Password reset request", $message, false);
    }
    $this->view->redirectRelative('user/sent');
  }
  
  public function get_sent() {
    $this->view->renderTemplate(
      "views/user_sent.php",
      array(
        'title' => 'Password reset',
      )
    );
  }
  
  public function get_password_token($token) {
    $this->view->renderTemplate(
      "views/user_password_token.php",
      array(
        'title' => 'Password reset',
        'token' => $token
      )
    );
  }

  public function post_password_token($token) {
    $form = safeParam($_POST, 'form');

    $user = $this->model::findUserByToken($token);
    if (!$user) {
      die("No user with that token found");
    }
    
    $validator = new Validator();
    $validator->password('newPassword1', safeParam($form, 'newPassword1'), "Password must have at least 8 characters, a number, an uppercase, and a symbol.");
    $validator->same('newPassword2', safeParam($form, 'newPassword1'), safeParam($form, 'newPassword2'), "Passwords must match.");
    if (!$validator->hasErrors()) {
      $user->password = password_hash(safeParam($form, 'newPassword1'), PASSWORD_DEFAULT);
      $user->token = null;
      $user->update();
      login($user);
      $this->view->flash("Password updated");
      $this->view->redirectRelative("");
    } else {
      $this->view->renderTemplate(
        "views/user_password_token.php",
        array(
          'title' => 'Password reset',
          'token' => $token,
          'form' => $form,
          'errors' => $validator->allErrors(),
        )
      );
    }
  }

}
?>