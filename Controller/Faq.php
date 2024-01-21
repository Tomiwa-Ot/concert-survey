<?php

namespace Project\Controller;

use Project\Library\CSRF;
use Project\Model\FrequentlyAskedQuestion;

use function Project\Library\render;

class Faq extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return FAQ page
     */
    public function get(): void
    {
        $statement = $this->pdo->prepare("SELECT * FROM faq");
        $statement->execute();
        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $faq = [];
        foreach ($rows as $row) {
            $faq[] = new FrequentlyAskedQuestion($row['id'], $row['question'], $row['answer']);
        }

        render('faq.php', ['title' => 'Frequently asked questions', 'faq' => $faq]);
    }

    /**
     * Create FAQ
     */
    public function add(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && CSRF::isTokenValid($_POST['token'])) {
            $statement = $this->pdo->prepare("INSERT into faq(question, answer) VALUES(?, ?)");
            $statement->execute([$_POST['question'], $_POST['answer']]);

            header("Location: /admin/faq");
            return;
        }

        render('admin/create-faq.php', ['title' => 'Create FAQ', 'token' => CSRF::getInputField()]);
    }


    public function edit(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && CSRF::isTokenValid($_POST['token'])) {
            $statement = $this->pdo->prepare("UPDATE faq SET question=?, answer=? WHERE id=?");
            $statement->execute([$_POST['question'], $_POST['answer'], $_POST['id']]);

            header("Location: /admin/faq");
            return;
        }

        $statement = $this->pdo->prepare("SELECT * FROM faq WHERE id=?");
        $statement->execute([$_GET['id']]);

        if ($statement->rowCount() > 0) {
            $row = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $faq = new FrequentlyAskedQuestion($row[0]['id'], $row[0]['question'], $row[0]['answer']);

            render('admin/edit-faq.php', ['title' => 'Edit FAQ', 'faq' => $faq, 'token' => CSRF::getInputField()]);
            return;
        }
        
        header("Location: /admin/faq");
    }

    /**
     * Delete FAQ
     */
    public function delete(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        $statement = $this->pdo->prepare("DELETE FROM faq WHERE id=?");
        $statement->execute([$_GET['id']]);

        header("Location: /admin/faq");
    }
}