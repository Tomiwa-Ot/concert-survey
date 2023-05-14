<?php require_once __DIR__ . '/header.php'; ?>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Events</h1>
    </div>

    <?php if (count($concerts) > 0): ?>
        <div class="row">
            <?php foreach ($concerts as $concert): ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <a href="/event/<?= str_replace(" ", "-", $concert->getTitle()) ?>"><?= $concert->getTitle() ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <div class="text-center">
            <p class="lead text-gray-800 mb-5">No Events</p>
            <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
            <a href="/">&larr; Check back later</a>
        </div>
    <?php endif ?>
    
<?php require_once __DIR__ . '/footer.php'; ?>