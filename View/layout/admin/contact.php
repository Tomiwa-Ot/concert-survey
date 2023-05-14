<?php require_once __DIR__ . '/../header.php'; ?>
<h1 class="h3 mb-2 text-gray-800"><?= $title ?></h1>
<!--Section: Contact v.2-->
<section class="mb-4">

    <p class="d-sm-flex align-items-center justify-content-between mb-4">Update your contact info here.</p>

    <div class="row">

        <!--Grid column-->
        <div class="col-md-9 mb-md-0 mb-5">
            <form id="contact-form" name="contact-form" action="/admin/contact" method="post">

                <!--Grid row-->
                <div class="row">
                    <div class="col-md-12 form-group">
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?>">
                        <input type="hidden" name="email-id" value="<?= $emailId ?>">
                    </div>
                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">
                    <div class="col-md-12 form-group">
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone" value="<?= $phone ?>">
                        <input type="hidden" name="phone-id" value="<?= $phoneId ?>">
                    </div>
                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">
                    <div class="col-md-12 form-group">
                        <input type="text" id="address" name="address" class="form-control" placeholder="Address" value="<?= $address ?>">
                        <input type="hidden" name="address-id" value="<?= $addressId ?>">
                    </div>
                </div>
                <!--Grid row-->

                <div><?= $token ?></div>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <button type="submit" class="btn btn-primary btn-user btn-block">Update</button>
                    </div>
                </div>
            </form>

            <div class="status"></div>
        </div>
        <!--Grid column-->

    </div>

</section>
<!--Section: Contact v.2-->
<?php require_once __DIR__ . '/../footer.php'; ?>