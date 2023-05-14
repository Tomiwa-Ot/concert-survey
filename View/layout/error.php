<?php
ob_start();
session_start();
?>
<?php require_once __DIR__ . '/../layout/header.php'; ?>
    <!-- 404 Error Text -->
    <div class="text-center">
        <div class="error mx-auto" data-text="404"><?= $code ?></div>
        <p class="lead text-gray-800 mb-5"><?= $message ?></p>
        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
        <a href="/">&larr; Back to Dashboard</a>
    </div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>