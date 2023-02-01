<table border="1">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Requests</th>
            <th scope="col">Impressions</th>
            <th scope="col">Fill Rate</th>
            <th scope="col">eCPM</th>
            <th scope="col">Revenue</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($reports)): ?>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?= $report['date'] ?></td>
                    <td><?= number_format($report['impressions'] + $report['passback']) ?></td>
                    <td><?= number_format($report['impressions']) ?></td>
                    <td><?= round($report['impressions'] / ($report['impressions'] + $report['passback']) * 100, 2) ?>%</td>
                    <td>$<?= round($report['ecpm'], 8) ?></td>
                    <td>$<?= number_format($report['revenue'], 4) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th>Total</th>
                <th><?= number_format(sum_array_of_array($reports, 'impressions') + sum_array_of_array($reports, 'passback')) ?></th>
                <th><?= number_format(sum_array_of_array($reports, 'impressions')) ?></th>
                <th><?= round(sum_array_of_array($reports, 'impressions') / (sum_array_of_array($reports, 'impressions') + sum_array_of_array($reports, 'passback')) * 100, 2) ?>%</th>
                <th>$<?= round(sum_array_of_array($reports, 'revenue') / sum_array_of_array($reports, 'impressions') * 1000, 9) ?></th>
                <th>$<?= number_format(sum_array_of_array($reports, 'revenue'), 4) ?></th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
