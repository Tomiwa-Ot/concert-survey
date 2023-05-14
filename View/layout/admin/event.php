<?php require_once __DIR__ . '/../header.php'; ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#artistModal"><i
            class="fas fa-plus fa-sm text-white-50"></i> Add Artist</a>
</div>

<?php if (count($artists) > 0): ?>
    <div class="row">
        <?php foreach ($artists as $artist): ?>
            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <a class="d-flex align-items-center" href="#">
                            <div class="dropdown-list-image mr-3">
                                <img class="rounded-circle" style="height: 2.5rem;width: 2.5rem" src="data:image/jpeg;base64,<?= $artist->getPicture() ?>"
                                    alt="<?= $artist->getName() ?>">
                                <div class="status-indicator bg-success"></div>
                            </div>
                            <div class="font-weight-bold">
                                <div class="text-truncate"><?= $artist->getName() ?></div>
                            </div>
                        </a>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#add<?= str_replace(" ", "", $artist->getName()) ?>Modal">Add song</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#remove<?= str_replace(" ", "", $artist->getName()) ?>Modal">Remove song</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/admin/event/<?= str_replace(" ", "-", $title) ?>?delete=<?= urlencode($artist->getName()) ?>">Delete artist</a>
                            </div>
                        </div>
                    </div>

                    <!-- Add song modal-->
                    <div class="modal fade" id="add<?= str_replace(" ", "", $artist->getName()) ?>Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <form action="/admin/track/add" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add songs</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12"> 
                                            Enter the name of the song below
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-light border-0 small" id="search-<?= str_replace(' ', '', $artist->getName()) ?>-input"
                                                    placeholder="Search for..." aria-label="Search"
                                                    aria-describedby="basic-addon2">
                                                <input type="hidden" name="artist" value="<?= $artist->getName() ?>">
                                                <input type="hidden" name="event" value="<?= str_replace(" ", "_", $title) ?>">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" id="search-<?= str_replace(' ', '', $artist->getName()) ?>-button">
                                                        <i class="fas fa-search fa-sm"></i>
                                                    </button>
                                                </div>
                                            </div>  
                                        </div>
                                        <div id="error-<?= str_replace(' ', '', $artist->getName()) ?>" class="text-danger" style="display: none;">Please choose one option.</div>
                                        <br>
                                        <div id="options-container-<?= str_replace(' ', '', $artist->getName()) ?>" class="dropdown-list-image mr-3"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                              </form>
                            </div>
                        </div>
                    </div>

                    <!-- Remove song modal-->
                    <div class="modal fade" id="remove<?= str_replace(" ", "", $artist->getName()) ?>Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <form action="/admin/track/remove" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Select songs to be removed</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div> 
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <?php if ($artist->getNumberOfTracks() > 0): ?>
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
                                                            <div class="small text-gray-500"><?= $track->getDuration() ?></div>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="artist" value="<?= $artist->getName() ?>">
                                        <input type="hidden" name="event" value="<?= $title ?>">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                              </form>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <?php if ($artist->getNumberOfTracks() > 0): ?>
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="<?= str_replace(' ', '', $artist->getName()) ?>"></canvas>
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
           

        <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="text-center">
        <p class="lead text-gray-800 mb-5">No Artists</p>
        <a href="#" data-toggle="modal" data-target="#artistModal">+ Click here to add an artist</a>
    </div>
<?php endif ?>

<!-- Create Artist Modal-->
<div class="modal fade" id="artistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addArtistForm" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add artist</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        Enter the name of the artist below
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" id="search-input"
                                placeholder="Search for..." aria-label="Search"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                        <div id="error" class="text-danger" style="display: none;">Please choose one option.</div>
                        <br>
                        <div id="options-container" class="dropdown-list-image mr-3"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>

<script>
    $(document).ready(function() {

        <?php foreach ($artists as $artist): ?>
            $("#search-<?= str_replace(' ', '', $artist->getName()) ?>-button").click(function() {
                var query = document.getElementById("search-<?= str_replace(' ', '', $artist->getName()) ?>-input").value

                $("#options-container-<?= str_replace(' ', '', $artist->getName()) ?>").empty();
                $.ajax({
                    url: "/admin/search/track?title=" + encodeURIComponent(query),
                    success: function(response) {
                        var data = JSON.parse(response)
                        $.each(data, function(index, option) {
                            var listItem = $("<div>").addClass("d-flex align-items-center p-4").appendTo("#options-container-<?= str_replace(' ', '', $artist->getName()) ?>");
                            var checkbox = $("<input>").attr({type: "checkbox", name: "track", value: JSON.stringify(option)}).appendTo(listItem);
                            var image = $('<img>').attr("src", option['picture']).addClass("rounded-circle").css({height:"2.5em", width: "2.5em"}).appendTo(listItem);
                            var text = $("<span>").addClass("font-weight-bold").text(option['name'] + ' by ' + option['artist']).appendTo(listItem);
                        });
                    }
                })
            });
        <?php endforeach ?>

        $('#search-button').click(function() {
            var query = document.getElementById("search-input").value
        
            $("#options-container").empty();
            $.ajax({
                url: "/admin/search/artist?name=" + encodeURIComponent(query),
                success: function(response) {
                    var data = JSON.parse(response)
                    $.each(data, function(index, option) {
                        var listItem = $("<div>").addClass("d-flex align-items-center p-1").appendTo("#options-container");
                        var checkbox = $("<input>").attr({type: "checkbox", name: "artist", value: JSON.stringify(option)}).appendTo(listItem);
                        var image = $('<img>').attr("src", option['picture']).addClass("rounded-circle").css({height:"2.5em", width: "2.5em"}).appendTo(listItem);
                        var text = $("<span>").addClass("font-weight-bold").text(option['name']).appendTo(listItem);
                    });
                }
            })
        });
    });
</script>

<script>
    const form = document.getElementById('addArtistForm');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    const errorMessage = document.getElementById('error');

    form.addEventListener('submit', (event) => {
        var numChecked = 0;
        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                numChecked++;
                if (numChecked > 1) {
                    event.preventDefault(); // prevent the form from submitting
                    errorMessage.style.display = 'block'; // show the error message
                }
            }
        }
        
        errorMessage.style.display = 'none'; // hide the error message
    });

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    <?php if (count($artists) > 0): ?>
        <?php foreach ($artists as $artist): ?>
            var ctx = document.getElementById("<?= str_replace(' ', '', $artist->getName()) ?>");
            var <?= str_replace(' ', '', $artist->getName()) ?> = new Chart(ctx, {
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
        <?php endforeach ?>
    <?php endif ?>
</script>