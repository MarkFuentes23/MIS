<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Evaluation Detail</title>
</head>
<body>
    <h2>Evaluation Detail</h2>
    <?php if($record): ?>
        <p><strong>ID:</strong> <?php echo $record['id']; ?></p>
        <p><strong>Employee ID:</strong> <?php echo $record['employee_id']; ?></p>
        <p><strong>Job Title ID:</strong> <?php echo $record['job_title_id']; ?></p>
        <p><strong>Reviewer ID:</strong> <?php echo $record['reviewer_id']; ?></p>
        <p><strong>Evaluation Period:</strong> <?php echo $record['evaluation_period']; ?></p>
        <p><strong>Department ID:</strong> <?php echo $record['department_id']; ?></p>
        <p><strong>Reviewer Designation ID:</strong> <?php echo $record['reviewer_designation_id']; ?></p>
    <?php else: ?>
        <p>Record not found.</p>
    <?php endif; ?>
    <p><a href="/evaluation/listRecords">Back to List</a></p>
</body>
</html>
