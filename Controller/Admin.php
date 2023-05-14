<?php

require_once __DIR__ . '/../Model/Concert.php';
require_once __DIR__ . '/../Model/Artist.php';
require_once __DIR__ . '/../Model/Message.php';
require_once __DIR__ . '/../Model/FrequentlyAskedQuestion.php';
require_once __DIR__ . '/../Library/CSRF.php';
require_once __DIR__ . '/BaseController.php';

use Project\Model\Artist;
use Project\Model\Concert;
use Project\Model\Message;
use Project\Library\CSRF;
use Project\Model\FrequentlyAskedQuestion;

class Admin extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return admin dashboard
     */
    public function home(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if (isset($_GET['enable'])) {
            $statement = $this->pdo->prepare("UPDATE concerts SET active=? WHERE title=?");
            $statement->execute([1, str_replace("-", " ", $_GET['enable'])]);

            header("Location: /admin/home");
            return;
        }

        if (isset($_GET['disable'])) {
            $statement = $this->pdo->prepare("UPDATE concerts SET active=? WHERE title=?");
            $statement->execute([0, str_replace("-", " ", $_GET['disable'])]);

            header("Location: /admin/home");
            return;
        }

        if (isset($_GET['delete'])) {
            $statement = $this->pdo->prepare("DELETE FROM concerts WHERE title=?");
            $statement->execute([str_replace("-", " ", $_GET['delete'])]);

            $statement = $this->pdo->prepare("DROP TABLE ". str_replace("-", "_", $_GET['delete']));
            $statement->execute([]);

            header("Location: /admin/home");
            return;
        }

        $statement = $this->pdo->prepare("SELECT * FROM concerts WHERE active=?");
        $statement->execute([1]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $activeConcerts = [];
        foreach ($rows as $row) {
            $activeConcerts[] = new Concert($row['title'], $row['active']);
        }

        $statement = $this->pdo->prepare("SELECT * FROM concerts WHERE active=?");
        $statement->execute([0]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $inactiveConcerts = [];
        foreach ($rows as $row) {
            $inactiveConcerts[] = new Concert($row['title'], $row['active']);
        }

        render(
            'admin/home.php',
            [
                'title' => 'Admin | Home',
                'activeConcerts' => $activeConcerts,
                'inactiveConcerts' => $inactiveConcerts
            ]
        );
    }

    /**
     * Return faq page
     */
    public function faq(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        $statement = $this->pdo->prepare("SELECT * FROM faq ORDER BY id DESC");
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $faq = [];
        foreach ($rows as $row) {
            $faq[] = new FrequentlyAskedQuestion($row['id'], $row['question'], $row['answer']);
        }

        render('admin/faq.php', ['title' => 'Admin | Faq', 'faq' => $faq]);
    }

    /**
     * Return messages page
     */
    public function messages(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        $statement = $this->pdo->prepare("SELECT * FROM messages ORDER BY id DESC");
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $messages = [];
        foreach ($rows as $row) {
            $messages[] = new Message($row['subject'], $row['name'], $row['email'], $row['message'], $row['read'], $row['date']);
        }

        render('admin/messages.php', ['title' => 'Admin | Messages', 'messages' => $messages]);
    }

    /**
     * Return login page
     */
    public function login(): void
    {
        if ($this->isLoggedIn())
            header("Location: /admin/home");
        else
            render('admin/login.php', ['token' => CSRF::getInputField()]);
    }

    /**
     * Authenticate admin
     */
    public function authenticate(): void
    {
        if ($this->isLoggedIn()) {
            header("Location: /admin/home");
            return;
        }        

        if (!CSRF::isTokenValid($_POST['token'])) {
            header("Location: /");
            return;
        }

        $statement = $this->pdo->prepare("SELECT * FROM users WHERE name=?");
        $statement->execute([$_POST['name']]);

        if ($statement->rowCount() > 0) {
            $row = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            if (password_verify($_POST['password'], $row[0]['password'])) {
                $_SESSION['admin'] = 'admin';
                header("Location: /admin/home");
                return;
            }
        }

        render("admin/login.php", ['token' => CSRF::getInputField()]);
    }

    /**
     * Logout admin
     */
    public function logout(): void
    {
        if ($this->isLoggedIn()) {
            $this->initializeSession();
            unset($_SESSION['admin']);
        }

        header('Location: /admin/login');
    }

    /**
     * Return password reset page
     */
    public function resetPassword(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        render('admin/reset-password.php', ['token' => CSRF::getInputField()]);
    }

    /**
     * Update admin password
     */
    public function updatePassword(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if (!CSRF::isTokenValid($_POST['token'])) {
            header("Location: /");
            return;
        }

        $statement = $this->pdo->prepare("UPDATE users SET password=? WHERE name=?");
        $statement->execute([password_hash($_POST['password'], $_SESSION['admin'])]);

        header("Location: /admin/home");
    }
}