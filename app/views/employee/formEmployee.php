<?php
$selectedEmployeeId = $_GET['employee_id'] ?? ($_POST['employee_id'] ?? null);
$evaluationPeriod = $_GET['evaluation_period'] ?? date('Y');
$existingGoals = [];
$employeeData = null;

if ($selectedEmployeeId) {
    $employeeData = $this->scoreCardModel->getEmployeeById($selectedEmployeeId);
    $existingGoals = $this->scoreCardModel->getGoalsForDisplay($selectedEmployeeId, $evaluationPeriod);
}
?>

<link rel="stylesheet" href="/public/css/formEmployee.css?v=<?php echo time(); ?>">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="main-form mt-2">
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
                            <select class="employee-select" id="employee_search" name="employee_id" style="width: 100%;">
                            </select>
                        </td>
                        <td class="form-label">Evaluation Period</td>
                        <td><input type="text" class="form-value" id="evaluation_period" value="2025" readonly></td>
                    </tr>
                    <tr>
                        <td class="form-label">Position Title</td>
                        <td><input type="text" class="form-value" id="job_title" value="<?= $employeeData['position_title'] ?? '' ?>" readonly></td>
                        <td class="form-label">Department</td>
                        <td><input type="text" class="form-value" id="department" value="<?= $employeeData['department'] ?? '' ?>" readonly></td>
                    </tr>
                    
                    <tr>
                        <td class="form-label">Reviewer</td>
                        <td><input type="text" class="form-value" id="reviewer" value=""></td>
                        <td class="form-label">Reviewer Designation</td>
                        <td><input type="text" class="form-value" id="reviewer_designation" value=""></td>
                    </tr>
                    <tr>
                        <td class="form-label">Job Classification</td>
                        <td>
                            <select class="form-value" id="job_classification">
                                <option value="">Select Classification</option>
                                <option value="Rank-and-File">Rank and File</option>
                                <option value="Supervisory/Specialist">Supervisory/Specialist</option>
                                <option value="Managerial and Up">Managerial and Up</option>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>


     <script src="/public/js/formEmployee.js"></script>
     <?php include 'scorecard.php'; ?>
     <?php include 'scorecard-include.php'; ?>

