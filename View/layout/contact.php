<?php require_once __DIR__ . '/header.php'; ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
</div>

<!--Section: Contact v.2-->
<section class="mb-4">

    <p class="d-sm-flex align-items-center justify-content-between mb-4">Do you have any questions? Please do not hesitate to contact us directly. Our team will come back to you within
        a matter of hours to help you.</p>

    <div class="row">

        <!--Grid column-->
        <div class="col-md-9 mb-md-0 mb-5">
            <form id="contact-form" name="contact-form" action="/contact" method="post">

                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-6 form-group">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name">
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-6 form-group">
                        <input type="text" id="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">
                    <div class="col-md-12 form-group">
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject">
                    </div>
                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-12 form-group">
                        <textarea type="text" id="message" name="message" rows="7" class="form-control md-textarea" placeholder="Message"></textarea>
                    </div>
                </div>
                <!--Grid row-->

                <div><?= $token ?></div>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <button type="submit" class="btn btn-primary btn-user btn-block">Send</button>
                    </div>
                </div>
            </form>

            <div class="status"></div>
        </div>
        <!--Grid column-->

        <!--Grid column-->
        <div class="col-md-3 text-center">
            <ul class="list-unstyled mb-0">
                <li><i class="fas fa-map-marker-alt fa-2x"></i>
                    <p><?= $address ?></p>
                </li>

                <li><i class="fas fa-phone mt-4 fa-2x"></i>
                    <p><?= $phone ?></p>
                </li>

                <li><i class="fas fa-envelope mt-4 fa-2x"></i>
                    <p><?= $email ?></p>
                </li>
            </ul>
        </div>
        <!--Grid column-->

    </div>

</section>
<!--Section: Contact v.2-->
<?php require_once __DIR__ . '/footer.php'; ?>