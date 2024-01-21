<?php

namespace Project\Controller;

use Project\Library\CSRF;

use function Project\Library\render;

class Contact extends BaseController
{
    /** @var string address */
    private string $address;

    /** @var int $addressId */
    private int $addressId;

    /** @var string $phone */
    private string $phone;

    /** @var int $phoneId */
    private int $phoneId;

    /** @var string $email */
    private string $email;

    /** @var int $emailId */
    private int $emailId;

    public function __construct()
    {
        parent::__construct();
    }

    public function get(): void
    {
        $this->initializeSession();
        $this->getContactDetails();

        render(
            'contact.php',
            [
                'title' => 'Contact',
                'token' => CSRF::getInputField(),
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email
            ]
        );
    }

    public function post(): void
    {
        $this->initializeSession();
        
        if (!CSRF::isTokenValid($_POST['token'])) {
            header("Location: /contact");
            return;
        }

        $statement = $this->pdo->prepare("INSERT into messages(subject, name, email, message, date) VALUES(?, ?, ?, ?)");
        $statement->execute([$_POST['subject'], $_POST['name'], $_POST['email'], $_POST['message'], date('Y-m-d H:i:s')]);

        $this->getContactDetails();

        render(
            'contact.php',
            [
                'title' => 'Contact',
                'token' => CSRF::getInputField(),
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email
            ]
        );
    }

    /**
     * Admin view for contact details
     */
    public function admin(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        $this->getContactDetails();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            render(
                'admin/contact.php',
                [
                    'title' => 'Admin | Contact',
                    'address' => $this->address,
                    "addressId" => $this->addressId,
                    'phone' => $this->phone,
                    'phoneId' => $this->phoneId,
                    'email' => $this->email,
                    'emailId' => $this->emailId,
                    'token' =>  CSRF::getInputField()
                ]
            );
            return;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::isTokenValid($_POST['token'])) {
                header("Location: /");
                return;
            }

            if(isset($_POST['address'])) {
                $statement = $this->pdo->prepare("UPDATE contact SET value=? WHERE id=?");
                $statement->execute([$_POST['address'], $_POST['address-id']]);
            }
            if(isset($_POST['email'])) {
                $statement = $this->pdo->prepare("UPDATE contact SET value=? WHERE id=?");
                $statement->execute([$_POST['email'], $_POST['email-id']]);
            }
            if(isset($_POST['phone'])) {
                $statement = $this->pdo->prepare("UPDATE contact SET value=? WHERE id=?");
                $statement->execute([$_POST['phone'], $_POST['phone-id']]);
            }
        }

        header("Location: /admin/contact");
           
    }

    /**
     * Get contact details from database
     */
    private function getContactDetails(): void
    {
        $statement = $this->pdo->prepare("SELECT * FROM contact WHERE name=?");
        $statement->execute(['address']);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->address = $data[0]['value'];
        $this->addressId = $data[0]['id'];

        $statement = $this->pdo->prepare("SELECT * FROM contact WHERE name=?");
        $statement->execute(['phone']);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->phone = $data[0]['value'];
        $this->phoneId = $data[0]['id'];

        $statement = $this->pdo->prepare("SELECT * FROM contact WHERE name=?");
        $statement->execute(['email']);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->email = $data[0]['value'];
        $this->emailId = $data[0]['id'];
    }
}