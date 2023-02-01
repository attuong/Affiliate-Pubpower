<div class="row">
    <div class="col-md-8">
        <form id="filter" action="">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <select name="f_permission" class="form-control select2 mr-2" onchange="$('#filter').submit();" data-placeholder="Select a Permission" data-allow-clear="true" data-minimum-results-for-search="-1">
                        <option></option>
                        <option value="publisher" <?= isset($filters['permission']) && in_array($filters['permission'], ['publisher']) ? 'selected=""' : ''; ?>>Publisher</option>
                        <option value="agency"<?= isset($filters['permission']) && in_array($filters['permission'], ['agency']) ? 'selected=""' : ''; ?>>Agency</option>
                        <option value="admin"<?= isset($filters['permission']) && in_array($filters['permission'], ['admin']) ? 'selected=""' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <select name="f_status" class="form-control select2 mr-2" onchange="$('#filter').submit();" data-placeholder="Select a Status" data-allow-clear="true" data-minimum-results-for-search="-1">
                        <option></option>
                        <option value="active" <?= isset($filters['permission']) && in_array($filters['status'], ['active']) ? 'selected=""' : ''; ?>>Active</option>
                        <option value="pending" <?= isset($filters['permission']) && in_array($filters['status'], ['pending']) ? 'selected=""' : ''; ?>>Pending</option>
                        <option value="banned" <?= isset($filters['permission']) && in_array($filters['status'], ['banned']) ? 'selected=""' : ''; ?>>Banned</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <?php if ($agencies): ?>
                        <select name="f_agency" class="form-control select2 mr-2" onchange="$('#filter').submit();" data-placeholder="Select a Account Manager" data-allow-clear="true">
                            <option></option>
                            <?php foreach ($agencies as $agency): ?>
                                <option value="<?= $agency->id; ?>" <?= isset($filters['agency']) && $filters['agency'] == $agency->id ? 'selected=""' : ''; ?>><?= $agency->email; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-5">
                    <input name="f_keyword" type="text" class="form-control" onchange="$('#filter').submit();" placeholder="Search by email, userID" value="<?= isset($filters['keyword']) && $filters['keyword'] ? $filters['keyword'] : ''; ?>">
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <div class="form-row pull-right">
            <div class="form-group col-md-12">
                <a href="<?= URL_USER_CREATE; ?>" title="Create new user" class="btn btn-success btn-flat"><i class="fas fa-user-plus mr-2"></i> Create User</a>
            </div>
        </div>
    </div>
</div>