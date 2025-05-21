
<link rel="stylesheet" href="/public/css/formEmployee.css?v=<?php echo time(); ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="main-form mt-2">
                    <!-- Header Section -->
                    <table>
                        <tr class="header-row">
                            <td width="25%" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">
                                <img src="/public/img/logo.png" class="company-logo">
                            </td>
                            <td colspan="3" style="border-bottom: 1px solid #000;">
                                <h3 class="main-title">BALANCED SCORECARD (INDIVIDUAL)</h3>
                                <h4 class="subtitle">PERFORMANCE APPRAISAL FORM (SPECIALIST LEVEL)</h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label">Name</td>
                            <td>
                                <select class="form-value" id="employee_select" name="employee_id" required>
                                    <option value="">Select Name</option>
                                    <?php foreach($data['employees'] as $employee): ?>
                                        <option value="<?= $employee['id'] ?>"><?= $employee['lastname'] . ', ' . $employee['firstname'] . ' ' . $employee['middlename'] . ' ' . $employee['suffix'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="form-label">Evaluation Period</td>
                            <td><input type="text" class="form-value" id="evaluation_period" value="2025" readonly></td>
                        </tr>
                        
                        <tr>
                            <td class="form-label">Position Title</td>
                            <td><input type="text" class="form-value" id="job_title" readonly></td>
                            <td class="form-label">Department</td>
                            <td><input type="text" class="form-value" id="department" readonly></td>
                        </tr>
                        
                        <tr>
                            <td class="form-label">Reviewer</td>
                            <td><input type="text" class="form-value" id="reviewer" value=""></td>
                            <td class="form-label">Reviewer Designation</td>
                            <td><input type="text" class="form-value" id="reviewer_designation" value=""></td>
                        </tr>
                    </table>

<!-- Scorecard Rating Section -->
    <?php include 'scorecard.php'; ?>
    <script src="/public/js/formEmployee.js"></script>
    <script src="/public/js/reload.js"></script>
    <?php include 'scorecard-include.php'; ?>


                    