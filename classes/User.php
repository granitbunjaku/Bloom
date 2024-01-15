<?php

include 'createPost.php';

class User
{
    private $crud;
    private $postDB;

    public function __construct(CRUD $crud, Posts $postDB = null) {
        $this->crud = $crud;
        $this->postDB = $postDB;
    }

    public function regiser() {
        $user = $this->crud->read("users", ['email' => $_POST['email']], 1);

        $errors = [];

        if($user) {
            $errors[] = "User already exists! Try another email!";
            return $errors;
        }

        if (strlen($_POST['fullname']) < 5) {
            $errors[] = "Too short! Please enter longer name!";
        }

        if (strlen($_POST['email']) < 11) {
            $errors[] = "Too short! Please enter longer email!";
        }

        if (strlen($_POST['password']) < 8) {
            $errors[] = "Too short! Please enter longer password!";
        }

        if ($_POST['confirm_password'] !== $_POST['password']) {
            $errors[] = "Type same password for both password fields!";
        }

        if ($_POST['gender'] != 'Male' && $_POST['gender'] != 'Female') {
            $errors[] = "Please select a gender!";
        }

        if ($_POST['role'] != 'Admin' && $_POST['role'] != 'User') {
            $errors[] = "Please select a role!";
        }

        if (!count($errors)) {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $gender = $_POST['gender'];
            $role = $_POST['role'];

            if ($this->crud->create('users', [
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password,
                'bio' => '',
                'gender' => $gender,
                'roleId' => $role == 'Admin' ? 1 : 2
            ])) {
                header('Location: login.php');
            } else {
                $errors[] = "Something went wrong!";
                return $errors;
            }
        } else {
            return $errors;
        }
    }

    public function login() {
        $errors = [];

        if (strlen($_POST['email']) < 11) {
            $errors[] = "Too short! Please enter a valid email!";
        }

        if (strlen($_POST['password']) < 8) {
            $errors[] = "Too short! Please enter a valid password!";
        }

        $user = $this->crud->read("users", ['email' => $_POST['email']], 1);

        if($user) extract($user[0]);

        if(!count($errors)) {
            if (is_array($user) && count($user) > 0) {
                if (password_verify($_POST['password'], $password)) {
                    $_SESSION['fullname'] = $fullname;
                    $_SESSION['is_loggedin'] = true;
                    $_SESSION['user_id'] = $id;
                    $_SESSION['pfp'] = $pfp;
                    $_SESSION['is_admin'] = ($roleId == Roles::ADMIN);
                    header('Location: index.php');
                } else {
                    $errors[] = "Wrong password!";
                    return $errors;
                }
            } else {
                $errors[] = "Invalid user!";
                return $errors;
            }
        } else {
            return $errors;
        }
    }

    public function editProfile($user) {
        extract($user[0]);

        $errors = [];

        if (strlen($_POST['fullname'] > 0)) {
            if (strlen($_POST['fullname']) > 5) {
                if($_POST['fullname'] !== $fullname){
                    if ($this->crud->update("users", ['fullname' => $_POST['fullname']], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                        $_SESSION['fullname'] = $_POST['fullname'];
                    }
                } else {
                    $errors[] = "Your fullname shouldn't be the same as the recent!";
                }
            } else {
                $errors[] = "Fullname should be longer than 5 characters!";
            }
        }

        if (strlen($_POST['email'] > 0)) {
            if($_POST['email'] !== $email){
                $this->crud->update("users", ['email' => $_POST['email']], ['column' => 'id', 'value' => $_SESSION['user_id']]);
            } else {
                $errors[] = "Your email shouldn't be the same as the recent!";
            }
        }

        if ($_POST['bio'] !== $bio) {
            $this->crud->update("users", ['bio' => $_POST['bio']], ['column' => 'id', 'value' => $_SESSION['user_id']]);
        } else {
            $errors[] = "Your bio shouldn't be the same as the recent!";
        }

        if(count($errors) != 3) {
            header('Location: settings.php');
        } else {
            return $errors;
        }

    }

    public function changePassword($user) {
        $errors = [];

        extract($user[0]);

        if (strlen($_POST['opassword']) === 0) {
            $errors[] = "Old password field shouldn't be empty!";
        }

        if (strlen($_POST['npassword']) === 0 || strlen($_POST['npassword']) < 8) {
            $errors[] = "New password field should be 8+ characters long!";
        }

        if ($_POST['rpassword'] !== $_POST['npassword']) {
            $errors[] = "Repeat password and new password field should be the same!";
        }

        $res = password_verify($_POST['opassword'], $password);

        if (count($errors) === 0) {
            if ($res) {
                $npassword = password_hash($_POST['npassword'], PASSWORD_BCRYPT);
                $this->crud->update("users", ['password' => $npassword], ['column' => 'id', 'value' => $_SESSION['user_id']]);
            } else {
                $errors[] = "Your old password is incorrect!";
                return $errors;
            }
        } else {
            return $errors;
        }
    }

    public function getUserProfile()
    {
        if (isset($_GET['id'])) {
            return $this->crud->read("users", ['id' => $_GET['id']]);
        }

        return null;
    }

    public function updateBanner($id)
    {
        $bannerfile = time() . $_FILES['newbanner']['name'];

        if ($this->crud->update("users", [
            'cover' => $bannerfile
        ], ['column' => 'id', 'value' => $id])) {
            move_uploaded_file($_FILES['newbanner']['tmp_name'], 'bannerpics/' . $bannerfile);
            header('Location: profile.php?id=' . $_SESSION['user_id']);
        } else {
            echo "Something went wrong!";
        }
    }

    public function updateProfilePicture($id)
    {
        $pfpfile = time() . $_FILES['newpfp']['name'];

        if ($this->crud->update("users", [
            'pfp' => $pfpfile
        ], ['column' => 'id', 'value' => $id])) {
            move_uploaded_file($_FILES['newpfp']['tmp_name'], 'profilepics/' . $pfpfile);
            $_SESSION['pfp'] = $pfpfile;
            header('Location: profile.php?id=' . $_SESSION['user_id']);
        } else {
            echo "Something went wrong!";
        }
    }

    public function getUserPosts($userId)
    {
        return $this->postDB->readPosts("u.id", $userId);
    }

}