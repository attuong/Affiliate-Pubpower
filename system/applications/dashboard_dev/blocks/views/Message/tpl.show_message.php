<?php if (isset($error_message) && $error_message): ?>
    <div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-warning"></i> Warning!</h4>
        <?= $error_message; ?>
    </div>
<?php endif; ?>

<?php if (isset($success_message) && $success_message): ?>
    <div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check-circle"></i> Success!</h4>
        <?= $success_message; ?>
    </div>
<?php endif; ?>

<?php if (isset($warning_message) && $warning_message): ?>
    <div class="alert alert-warning alert-dismissible">
        <h4><i class="icon fa fa-check-circle"></i> Warning!</h4>
        <?= $warning_message; ?>
    </div>
<?php endif; ?>