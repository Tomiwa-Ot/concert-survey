<?php

require_once __DIR__ . '/../Library/Response.php';
require_once __DIR__ . '/../Library/Spotify.php';
require_once __DIR__ . '/../Library/Database.php';

use Project\Library\Response;
use Project\Library\Spotify;
use Project\Library\Database;

/**
 * Base controller
 */
class BaseController
{
    /** @var Spotify $spotify */
    protected Spotify $spotify;

    /** @var $pdo */
    protected $pdo;

    public function __construct()
    {
        $this->spotify = new Spotify();
        $this->pdo = Database::getpdo();
    }

    /**
     * Check if admin is logged in
     * 
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        $this->initializeSession();
        return isset($_SESSION['admin']);
    }

    /**
     * Initialize session
     */
    protected function initializeSession(): void
    {  
        ob_start();
        session_start();
    }
}