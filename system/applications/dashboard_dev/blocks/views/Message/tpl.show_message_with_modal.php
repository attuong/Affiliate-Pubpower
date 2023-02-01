
<?php if (isset($success_message) && $success_message): ?>
    <script>
        $(document).ready(function () {
            ShowNotifiSuccess('<?= $success_message ?>');
//            notifiSuccess('<?= $success_message ?>');
        });
    </script>
<?php endif; ?>

<?php if (isset($error_message) && $error_message): ?>
    <script>
        $(document).ready(function () {
            notifiError('<?= $error_message ?>');
        });
    </script>
    <?php
 endif ?>