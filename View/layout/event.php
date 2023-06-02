<?php require_once __DIR__ . '/header.php'; ?>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <?= $title ?>
        </h1>
    </div>

    <?php if (isset($_GET['artist'])): ?>
        <!-- Content Row -->
        <div class="row">
            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Choose your favourite songs</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <?php if ($artist->getNumberOfTracks() > 0): ?>
                            <form id="voteForm" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
                                <?php foreach ($artist->getTracks() as $track): ?>
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-list-image mr-3">
                                            <input type="checkbox" name="song[]" value="<?= $track->getTitle() ?>">
                                            <img class="rounded-circle" style="height: 2.5em;width: 2.5em;" src="<?= $track->getPicture() ?>"
                                                alt="<?= $track->getTitle() ?>">
                                            <div class="status-indicator bg-success"></div>
                                        </div>
                                        <div class="font-weight-bold">
                                            <div><?= $track->getTitle() ?></div>
                                            <div class="small text-gray-500"><?= sprintf("%02d", $track->getDuration()) ?></div>
                                        </div>
                                    </div>
                                    <br>
                                <?php endforeach ?>
                                <div id="error" class="text-danger" style="display: none;">Please choose at least one option.</div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Vote</button>
                            </form>
                        <?php else: ?>
                            <div class="text-center h5 mb-0 font-weight-bold text-gray-800">No songs</div>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Votes</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <?php if ($artist->getNumberOfTracks() > 0): ?>
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="pieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <?php foreach ($artist->getTracks() as $track): ?>
                                    <span class="mr-2">
                                        <i style="color: <?= $track->getColor() ?>;" class="fas fa-circle"></i> <?= $track->getTitle() ?>
                                    </span>
                                <?php endforeach ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center h5 mb-0 font-weight-bold text-gray-800">No songs</div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>

        
    <?php else: ?>
        <!-- Content Row -->
        <div class="row">
            <?php foreach ($artists as $artist): ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <a href="<?= $_SERVER['REQUEST_URI'] . "?artist=" . $artist->getName() ?>"><?= $artist->getName() ?></a>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $artist->getNumberOfTracks() ?> Song<?php if($artist->getNumberOfTracks() > 1 || $artist->getNumberOfTracks() === 0): ?>s<?php endif ?></div>
                                </div>
                                <div class="col-auto mr-3">
                                    <img class="rounded-circle" style="height: 4.5rem;width: 4.5rem" src="data:image/jpeg;base64,<?= $artist->getPicture() ?>"
                                        alt="<?= $artist->getName() ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

<?php require_once __DIR__ . '/footer.php'; ?>

<?php if (isset($_GET['artist'])): ?>
    <script>
        const form = document.getElementById('voteForm');
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        const errorMessage = document.getElementById('error');

        form.addEventListener('submit', (event) => {
            let checked = false;
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                checked = true;
                break;
                }
            }
            if (!checked) {
                event.preventDefault(); // prevent the form from submitting
                errorMessage.style.display = 'block'; // show the error message
            } else {
                errorMessage.style.display = 'none'; // hide the error message
            }
        });


        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // Pie Chart Example
        var ctx = document.getElementById("pieChart");
        var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                <?php foreach ($artist->getTracks() as $key => $track): ?>
                    "<?= $track->getTitle() ?>"<?php if ($key !== array_key_last($artist->getTracks())): ?>,<?php endif ?>
                <?php endforeach ?>
            ],
            datasets: [{
            data: [
                <?php foreach ($artist->getTracks() as $key => $track): ?>
                    <?= $track->getScore() ?><?php if ($key !== array_key_last($artist->getTracks())): ?>,<?php endif ?>
                <?php endforeach ?>
            ],
            backgroundColor: [
                <?php foreach ($artist->getTracks() as $key => $track): ?>
                    "<?= $track->getColor() ?>"<?php if ($key !== array_key_last($artist->getTracks())): ?>,<?php endif ?>
                <?php endforeach ?>
            ],
            hoverBackgroundColor: [
                <?php foreach ($artist->getTracks() as $key => $track): ?>
                    "<?= $track->getColor() ?>"<?php if ($key !== array_key_last($artist->getTracks())): ?>,<?php endif ?>
                <?php endforeach ?>
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            },
            legend: {
            display: false
            },
            cutoutPercentage: 80,
        },
        });

    </script>
<?php endif ?>