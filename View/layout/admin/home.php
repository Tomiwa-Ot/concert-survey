<?php require_once __DIR__ . '/../header.php'; ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Active Concerts</h1>
    <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#concertModal"><i
            class="fas fa-plus fa-sm text-white-50"></i> Add concert</a>
</div>

<?php if (count($activeConcerts) > 0): ?>
    <div class="row">
        <?php foreach ($activeConcerts as $concert): ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    <a href="/admin/event/<?= str_replace(" ", "-", $concert->getTitle()) ?>"><?= $concert->getTitle() ?></a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="/admin/home?disable=<?= str_replace(" ", "-", $concert->getTitle()) ?>">Disable</a>
                                        <a class="dropdown-item" href="/admin/home?delete=<?= str_replace(" ", "-", $concert->getTitle()) ?>">Delete</a>
                                    </div>
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
        <a href="#" data-toggle="modal" data-target="#concertModal">+ Click here to create a new event </a>
    </div>
<?php endif ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Disabled Concerts</h1>
</div>

<?php if (count($inactiveConcerts) > 0): ?>
    <div class="row">
        <?php foreach ($inactiveConcerts as $concert): ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    <a class="text-danger" href="/admin/event/<?= str_replace(" ", "-", $concert->getTitle()) ?>"><?= $concert->getTitle() ?></a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="/admin/home?enable=<?= str_replace(" ", "-", $concert->getTitle()) ?>">Enable</a>
                                        <a class="dropdown-item" href="/admin/home?delete=<?= str_replace(" ", "-", $concert->getTitle()) ?>">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<!-- Create Concert Modal-->
<div class="modal fade" id="concertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="concertForm" action="/admin/create-event" method="post" onsubmit="return validate()">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add concert</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        Enter the name of the concert below
                        <input type="text" name="name" class="form-control bg-light border-0 small" placeholder="Name">
                        <div id="errorMessage" class="text-danger" style="display: none;">Please enter letters, spaces, or numbers only.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>

<script>
    function validate() {
        let error = document.getElementById("errorMessage");
        let str = document.forms["concertForm"]["Name"].value;
        
        if (/^[a-z0-9\s]+$/i.test(str)) {
            error.style.display = 'none';
            return true;
        }

        errorMessage.style.display = 'block';
        return false;
    }
</script>