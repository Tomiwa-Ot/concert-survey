<?php

require_once __DIR__ . '/../Model/Concert.php';
require_once __DIR__ . '/BaseController.php';

use Project\Model\Concert;

class Home extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return index page
     */
    public function get(): void
    {
        render('index.php', ['title' => '', 'concerts' => $this->getActiveConcerts()]);
    }

    /**
     * Get all active concerts
     * 
     * @return array<Concert>
     */
    private function getActiveConcerts(): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM concerts WHERE active=?");
        $statement->execute([1]);

        $concerts = [];
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $concerts[] = new Concert($row['title'], $row['active']);
        }

        return $concerts;
    }
}