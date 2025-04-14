<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Evaluation Records</title>
</head>
<body>
    <h2>All Evaluation Records</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Employee ID</th>
            <th>Job Title ID</th>
            <th>Reviewer ID</th>
            <th>Evaluation Period</th>
            <th>Department ID</th>
            <th>Reviewer Designation ID</th>
        </tr>
        <?php foreach($records as $record): ?>
            <tr onclick="window.location.href='/evaluation/viewRecord?id=<?php echo $record['id']; ?>'" style="cursor:pointer;">
                <td><?php echo $record['id']; ?></td>
                <td><?php echo $record['employee_id']; ?></td>
                <td><?php echo $record['job_title_id']; ?></td>
                <td><?php echo $record['reviewer_id']; ?></td>
                <td><?php echo $record['evaluation_period']; ?></td>
                <td><?php echo $record['department_id']; ?></td>
                <td><?php echo $record['reviewer_designation_id']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="/evaluation/form">Add New Evaluation</a></p>
    <p><a href="/auth/logout">Logout</a></p>
</body>
</html>
