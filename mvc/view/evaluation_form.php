<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Evaluation Form</title>
</head>
<body>
    <h2>Evaluation Form</h2>
    <?php if(isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="/evaluation/save">
        <label>Name:</label>
        <select name="employee_id" required>
            <?php foreach($employees as $emp): ?>
                <option value="<?php echo $emp['id']; ?>">
                    <?php echo $emp['firstname'] . ' ' . $emp['lastname']; ?>
                </option>
            <?php endforeach; ?>
        </select><br/>

        <label>Position Title:</label>
        <select name="job_title_id" required>
            <?php foreach($job_titles as $jt): ?>
                <option value="<?php echo $jt['id']; ?>">
                    <?php echo $jt['job_title']; ?>
                </option>
            <?php endforeach; ?>
        </select><br/>

        <label>Reviewer:</label>
        <select name="reviewer_id" required>
            <?php foreach($employees as $emp): ?>
                <option value="<?php echo $emp['id']; ?>">
                    <?php echo $emp['firstname'] . ' ' . $emp['lastname']; ?>
                </option>
            <?php endforeach; ?>
        </select><br/>

        <label>Evaluation Period:</label>
        <input type="number" name="evaluation_period" required><br/>

        <label>Department:</label>
        <select name="department_id" required>
            <?php foreach($departments as $dept): ?>
                <option value="<?php echo $dept['id']; ?>">
                    <?php echo $dept['department']; ?>
                </option>
            <?php endforeach; ?>
        </select><br/>

        <label>Reviewer Designation:</label>
        <select name="reviewer_designation_id" required>
            <?php foreach($job_titles as $jt): ?>
                <option value="<?php echo $jt['id']; ?>">
                    <?php echo $jt['job_title']; ?>
                </option>
            <?php endforeach; ?>
        </select><br/>
        
        <button type="submit">Save Evaluation</button>
    </form>
    <p><a href="/evaluation/listRecords">Back to List</a></p>
</body>
</html>
