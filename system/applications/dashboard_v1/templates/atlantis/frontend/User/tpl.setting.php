<?php if ($success_message): ?>
    <script type="text/javascript">
        $(document).ready(function () {
            swal("Good job!", "<?= $success_message; ?>", {
                icon: "success",
                buttons: {
                    confirm: {
                        className: 'btn btn-success'
                    }
                }
            });
        });
    </script>
<?php endif; ?>
<?php if (isset($error_message)): ?>
    <script type="text/javascript">
        $(document).ready(function () {
            swal("Error!!!", "<?= $error_message; ?>", {
                icon: "error",
                buttons: {
                    confirm: {
                        className: 'btn btn-danger'
                    }
                }
            });
        });
    </script>
    <style>
        .swal-text {
            color: #F27374;
        }
    </style>
<?php endif; ?>
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Users</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="<?= ROOTDOMAIN; ?>">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="<?= URL_USER; ?>">Users</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <span>Setting</span>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header <?= $theme_settings->card_header; ?>">
                    <div class="card-title text-white pull-left"><i class="fas fa-user-edit mr-2"></i> Setting User</div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">

                                <form method="POST" action="">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="Email" title="Required field">Email <sup class="text-danger">*</sup></label>
                                            <input type="email" disabled="" value="<?= isset($inputs['email']) ? $inputs['email'] : $user->email; ?>" class="form-control" id="Email" placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="Password" title="Required field">Password <sup class="text-danger">*</sup></label>
                                            <input type="password" name="password" class="form-control" id="Password" placeholder="Password">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="RetypePassword" title="Required field">Retype Password <sup class="text-danger">*</sup></label>
                                            <input type="password" name="retype_password" class="form-control" id="RetypePassword" placeholder="Retype Password">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="FirstName">First Name</label>
                                            <input type="text" name="first_name" value="<?= isset($inputs['first_name']) ? $inputs['first_name'] : $user->first_name; ?>" class="form-control" id="FirstName" placeholder="First Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="LastName">Last Name</label>
                                            <input type="text" name="last_name" value="<?= isset($inputs['last_name']) ? $inputs['last_name'] : $user->last_name; ?>" class="form-control" id="LastName" placeholder="Last Name">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="Address">Address</label>
                                            <input type="text" name="address" value="<?= isset($inputs['address']) ? $inputs['address'] : $user->address; ?>" class="form-control" id="Address">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="Note">Note</label>
                                            <textarea class="form-control" name="note" rows="3" id="Note" ><?= isset($inputs['note']) ? $inputs['note'] : $user->note; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <button class="btn btn-danger btn-flat" type="reset"><i class="fa fa-retweet mr-2"></i> Reset</button>
                                            <button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-check-double mr-2"></i> Submit</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>