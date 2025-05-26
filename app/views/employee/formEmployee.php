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
<style>
.autocomplete-container {
    position: relative;
    width: 100%;
}

.autocomplete-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

.autocomplete-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ccc;
    border-top: none;
    border-radius: 0 0 4px 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.autocomplete-item {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.autocomplete-item:hover,
.autocomplete-item.highlighted {
    background-color: #f5f5f5;
}

.autocomplete-item:last-child {
    border-bottom: none;
}
</style>

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
                            <div class="autocomplete-container">
                                <input type="text" class="form-value autocomplete-input" id="employee_search" 
                                       placeholder="Type employee name..." autocomplete="off">
                                <div class="autocomplete-dropdown" id="employee_dropdown">
                                    <!-- Employee options will be populated here -->
                                </div>
                            </div>
                            <input type="hidden" id="employee_id" name="employee_id" value="<?= $selectedEmployeeId ?>">
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
                </table>

    <script src="/js/formEmployee.js?v=<?php echo time(); ?>" defer></script>
     <?php include 'scorecard.php'; ?>
     <?php include 'scorecard-include.php'; ?>

    <script>
        $(document).ready(function() {
            let goalsByKRA = {};
            let employees = <?php echo json_encode($data['employees']); ?>;
            let filteredEmployees = [];
            let selectedIndex = -1;
            
            const $input = $('#employee_search');
            const $dropdown = $('#employee_dropdown');
            const $employeeId = $('#employee_id');
            
            // Initialize autocomplete
            function initAutocomplete() {
                // Set initial value if employee selected
                if ($employeeId.val()) {
                    const emp = employees.find(e => e.id == $employeeId.val());
                    if (emp) $input.val(`${emp.firstname} ${emp.lastname}`);
                }
                
                // Input filtering
                $input.on('input', function() {
                    const query = $(this).val().toLowerCase().trim();
                    if (!query) return $dropdown.hide(), filteredEmployees = [];
                    
                    filteredEmployees = employees.filter(e => 
                        `${e.firstname} ${e.lastname}`.toLowerCase().includes(query)
                    );
                    populateDropdown(filteredEmployees);
                    $dropdown.show();
                    selectedIndex = -1;
                });
                
                // Focus event
                $input.on('focus', function() {
                    if (!$(this).val().trim()) {
                        filteredEmployees = employees;
                        populateDropdown(filteredEmployees);
                        $dropdown.show();
                    }
                });
                
                // Click outside
                $(document).on('click', e => {
                    if (!$(e.target).closest('.autocomplete-container').length) $dropdown.hide();
                });
                
                // Keyboard navigation
                $input.on('keydown', function(e) {
                    const $items = $dropdown.find('.autocomplete-item');
                    const len = $items.length;
                    
                    switch(e.keyCode) {
                        case 40: // Down
                            e.preventDefault();
                            selectedIndex = Math.min(selectedIndex + 1, len - 1);
                            break;
                        case 38: // Up
                            e.preventDefault();
                            selectedIndex = Math.max(selectedIndex - 1, -1);
                            break;
                        case 13: // Enter
                            e.preventDefault();
                            if (selectedIndex >= 0 && $items.eq(selectedIndex).length) {
                                selectEmployee(filteredEmployees[selectedIndex]);
                            }
                            return;
                        case 27: // Escape
                            $dropdown.hide();
                            return;
                    }
                    highlightItem();
                });
            }
            
            // Populate dropdown
            function populateDropdown(list) {
                $dropdown.empty();
                if (!list.length) return $dropdown.append('<div class="autocomplete-item">No employees found</div>');
                
                list.forEach(emp => {
                    const name = `${emp.firstname} ${emp.lastname}`;
                    $('<div class="autocomplete-item">')
                        .text(name)
                        .data('employee', emp)
                        .on('click', () => selectEmployee(emp))
                        .appendTo($dropdown);
                });
            }
            
            // Highlight item
            function highlightItem() {
                const $items = $dropdown.find('.autocomplete-item');
                $items.removeClass('highlighted');
                if (selectedIndex >= 0 && selectedIndex < $items.length) {
                    $items.eq(selectedIndex).addClass('highlighted');
                }
            }
            
            // Select employee
            function selectEmployee(emp) {
                const name = `${emp.firstname} ${emp.lastname}`;
                $input.val(name);
                $employeeId.val(emp.id);
                $dropdown.hide();
                
                loadEmployeeData(emp.id);
                setTimeout(() => loadExistingGoals(emp.id), 100);
                setTimeout(updateTotals, 300);
            }
            
            // Load employee data
            function loadEmployeeData(id) {
                if (!id) return;
                
                $.ajax({
                    url: '/scorecard/getEmployeeData',
                    type: 'POST',
                    data: { employee_id: id },
                    dataType: 'json',
                    success: res => {
                        if (res.status === 'success' && res.data) {
                            $('#job_title').val(res.data.position_title || res.data.job_title || '');
                            $('#department').val(res.data.department || '');
                            if (res.data.reviewer) $('#reviewer').val(res.data.reviewer);
                            if (res.data.reviewer_designation) $('#reviewer_designation').val(res.data.reviewer_designation);
                        } else {
                            $('#job_title, #department').val('');
                        }
                    },
                    error: () => $('#job_title, #department').val('')
                });
            }
            
            // Load existing goals
            function loadExistingGoals(id) {
                if (!id) return;
                
                $('#scorecardTable tbody').empty();
                goalsByKRA = {};
                
                $.ajax({
                    url: '/scorecard/loadGoals',
                    type: 'POST',
                    data: { 
                        employee_id: id,
                        evaluation_period: $('#evaluation_period').val() || '2025'
                    },
                    dataType: 'json',
                    success: res => {
                        if (res.status === 'success' && res.data?.length) {
                            const grouped = {};
                            res.data.forEach(goal => {
                                if (!grouped[goal.kra_id]) grouped[goal.kra_id] = [];
                                grouped[goal.kra_id].push(goal);
                            });
                            
                            Object.entries(grouped).forEach(([kraId, goals]) => {
                                goalsByKRA[kraId] = goals.length;
                                
                                goals.forEach((goal, idx) => {
                                    const $row = (idx === 0 ? $('#kraRowTemplate') : $('#goalRowTemplate')).contents().clone();
                                    
                                    if (idx === 0) {
                                        $row.find('.kra-select').val(goal.kra_id);
                                        $row.find('.kra-cell').attr('rowspan', goals.length).attr('data-kra-id', goal.kra_id);
                                    }
                                    
                                    populateRowWithData($row, goal);
                                    $row.attr('data-goal-id', goal.id).attr('data-kra-id', goal.kra_id);
                                    $('#scorecardTable tbody').append($row);
                                });
                            });
                            
                            setTimeout(() => {
                                initSavedGoals();
                                updateTotals();
                            }, 100);
                        } else {
                            addInitialRow();
                        }
                    },
                    error: addInitialRow
                });
            }
            
            // Add goal to KRA
            function addGoalToKRA(kraId) {
                goalsByKRA[kraId] = (goalsByKRA[kraId] || 0) + 1;
                
                const $kraCell = $(`td.kra-cell[data-kra-id="${kraId}"]`);
                if ($kraCell.length) $kraCell.attr('rowspan', goalsByKRA[kraId]);
                
                const $newRow = $('#goalRowTemplate').contents().clone().attr('data-kra-id', kraId);
                const $kraRows = $(`tr[data-kra-id="${kraId}"]`);
                const $lastRow = $kraRows.last();
                
                $lastRow.length ? $lastRow.after($newRow) : $('#scorecardTable tbody').append($newRow);
                initNewRow($newRow);
            }
            
            // Initialize new row
            function initNewRow($row) {
                $row.find('input, select').prop('disabled', false);
                $row.find('.save-goal-btn').show();
                $row.find('.edit-goal-btn, .update-goal-btn').hide();
                $row.find('.remove-goal-btn').show();
            }
            
            // Remove goal
            function removeGoalFromKRA(goalId, $row, kraId) {
                const isDelete = !!goalId;
                Swal.fire({
                    title: isDelete ? 'Delete Goal?' : 'Remove Row?',
                    text: isDelete ? "This action cannot be undone." : "Remove this unsaved row?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: isDelete ? 'Yes, delete!' : 'Yes, remove!',
                    confirmButtonColor: isDelete ? '#d33' : '#3085d6'
                }).then(result => {
                    if (result.isConfirmed) {
                        if (isDelete) {
                            deleteGoal(goalId);
                        } else {
                            if (kraId && goalsByKRA[kraId]) {
                                goalsByKRA[kraId]--;
                                const $kraCell = $(`td.kra-cell[data-kra-id="${kraId}"]`);
                                if ($kraCell.length && goalsByKRA[kraId] > 0) {
                                    $kraCell.attr('rowspan', goalsByKRA[kraId]);
                                } else if (goalsByKRA[kraId] === 0) {
                                    delete goalsByKRA[kraId];
                                }
                            }
                            $row.remove();
                            updateTotals();
                            Swal.fire({ icon: 'success', title: 'Removed!', timer: 1500, showConfirmButton: false });
                        }
                    }
                });
            }
            
            // Populate row with data
            function populateRowWithData($row, goal) {
                $row.find('.goal-input').val(goal.goal || '');
                $row.find('.measurement-select').val(goal.measurement || 'Savings');
                $row.find('.weight-input').val(goal.weight || '');
                $row.find('.target-input').val(goal.target || '');
                $row.find('.period-select').val(goal.period || '');
                $row.find('.rating-input').val(goal.rating || '');
                $row.find('.evidence-input').val(goal.evidence || '');
                
                ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec']
                    .forEach(month => {
                        const val = goal[`${month}_value`] || goal[month] || '';
                        $row.find(`input[name="${month}"]`).val(val);
                    });
            }
            
            // Clear form
            function clearForm() {
                $('#job_title, #department, #reviewer, #reviewer_designation, #employee_search').val('');
                $employeeId.val('');
                $dropdown.hide();
                $('#scorecardTable tbody').empty();
                goalsByKRA = {};
                $('#weightTotal').text('0.0%');
                $('#scoreTotal').text('#DIV/0!');
                addInitialRow();
            }
            
            // Add initial row
            function addInitialRow() {
                const $row = $('#kraRowTemplate').contents().clone();
                $row.find('.kra-cell').attr('rowspan', 1);
                $('#scorecardTable tbody').append($row);
                initSavedGoals();
            }
            
            // AJAX helper
            function ajaxRequest(url, data, onSuccess, onError) {
                $.ajax({
                    url, type: 'POST', data, dataType: 'json', timeout: 30000,
                    success: onSuccess,
                    error: (xhr, status, error) => {
                        let msg = 'Connection error. Please try again.';
                        if (xhr.status === 500) msg = 'Server error occurred.';
                        else if (xhr.status === 404) msg = 'Endpoint not found.';
                        else if (xhr.status === 403) msg = 'Permission denied.';
                        else if (status === 'timeout') msg = 'Request timed out.';
                        
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.message) msg = res.message;
                        } catch (e) {}
                        
                        onError ? onError(msg) : Swal.fire('Error', msg, 'error');
                    }
                });
            }
            
            // Delete goal
            function deleteGoal(goalId) {
                ajaxRequest('/scorecard/deleteGoal', { goal_id: goalId }, res => {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false,
                            willClose: () => {
                                const empId = $employeeId.val();
                                if (empId) loadExistingGoals(empId);
                            }
                        });
                    } else {
                        Swal.fire('Error', res.message || 'Failed to delete goal', 'error');
                    }
                });
            }
            
            // Save goal
            function saveGoalData($row) {
                const isKraRow = $row.hasClass('kra-row') || $row.find('.kra-select').length > 0;
                const kraId = isKraRow ? $row.find('.kra-select').val() : $row.data('kra-id');
                const employeeId = $employeeId.val();
                
                if (!employeeId) return Swal.fire('Error', 'Please select an employee first', 'error');
                if (!kraId) return Swal.fire('Error', 'KRA is required', 'error');
                
                const goal = $row.find('.goal-input').val().trim();
                const weight = $row.find('.weight-input').val().trim();
                
                if (!goal) return Swal.fire('Error', 'Goal description is required', 'error');
                if (weight && isNaN(parseFloat(weight))) return Swal.fire('Error', 'Weight must be a valid number', 'error');
                
                if (isKraRow) {
                    const $kraCell = $row.find('.kra-cell');
                    if ($kraCell.length) {
                        $kraCell.attr('data-kra-id', kraId);
                        goalsByKRA[kraId] = goalsByKRA[kraId] || 1;
                        $kraCell.attr('rowspan', goalsByKRA[kraId]);
                    }
                    $row.attr('data-kra-id', kraId);
                }
                
                const data = {
                    employee_id: employeeId,
                    evaluation_period: $('#evaluation_period').val() || new Date().getFullYear().toString(),
                    kra_id: kraId,
                    job_title: $('#job_title').val() || '',
                    department: $('#department').val() || '',
                    reviewer: $('#reviewer').val() || '',
                    reviewer_designation: $('#reviewer_designation').val() || '',
                    goal, measurement: $row.find('.measurement-select').val() || 'Savings',
                    weight: parseFloat(weight) || 0,
                    target: $row.find('.target-input').val() || '',
                    period: $row.find('.period-select').val() || 'Annual',
                    rating: parseFloat($row.find('.rating-input').val()) || null,
                    evidence: $row.find('.evidence-input').val() || ''
                };
                
                ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec']
                    .forEach(month => {
                        const val = $row.find(`input[name="${month}"]`).val();
                        data[month] = val && val.trim() !== '' ? val.trim() : null;
                    });
                
                const $saveBtn = $row.find('.save-goal-btn');
                $saveBtn.prop('disabled', true).text('Saving...');
                
                ajaxRequest('/scorecard/saveGoal', data, res => {
                    if (res.status === 'success') {
                        $row.attr('data-goal-id', res.goal_id);
                        setSavedState($row);
                        updateTotals();
                        Swal.fire({ icon: 'success', title: 'Success!', text: 'Goal saved successfully.', timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire('Error', res.message || 'Error saving goal', 'error');
                    }
                    $saveBtn.prop('disabled', false).text('Save');
                }, () => $saveBtn.prop('disabled', false).text('Save'));
            }
            
            // Update goal
            function updateGoalData($row) {
                const goalId = $row.attr('data-goal-id');
                if (!goalId) return Swal.fire('Error', 'Cannot update: Goal ID not found', 'error');
                
                const goal = $row.find('.goal-input').val().trim();
                const weight = $row.find('.weight-input').val().trim();
                
                if (weight && isNaN(parseFloat(weight))) return Swal.fire('Error', 'Weight must be a valid number', 'error');
                
                const data = { 
                    goal_id: goalId, goal,
                    measurement: $row.find('.measurement-select').val() || 'Savings',
                    weight: parseFloat(weight) || 0,
                    target: $row.find('.target-input').val() || '',
                    period: $row.find('.period-select').val() || 'Annual',
                    rating: $row.find('.rating-input').val() ? parseFloat($row.find('.rating-input').val()) : null,
                    evidence: $row.find('.evidence-input').val() || ''
                };
                
                ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec']
                    .forEach(month => {
                        const val = $row.find(`input[name="${month}"]`).val();
                        data[month] = val && val.trim() !== '' ? val.trim() : null;
                    });
                
                const $updateBtn = $row.find('.update-goal-btn');
                $updateBtn.prop('disabled', true).text('Updating...');
                
                ajaxRequest('/scorecard/updateGoal', data, res => {
                    if (res.status === 'success') {
                        setSavedState($row);
                        updateTotals();
                        Swal.fire({ icon: 'success', title: 'Success!', text: 'Goal updated successfully.', timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire('Error', res.message || 'Error updating goal', 'error');
                    }
                    $updateBtn.prop('disabled', false).text('Update');
                }, () => $updateBtn.prop('disabled', false).text('Update'));
            }
            
            // Set saved state
            function setSavedState($row) {
                $row.find('input, select').prop('disabled', true);
                $row.find('.save-goal-btn, .update-goal-btn').hide();
                $row.find('.edit-goal-btn, .remove-goal-btn').show();
            }
            
            // Initialize saved goals
            function initSavedGoals() {
                $('#scorecardTable tbody tr').each(function() {
                    const $row = $(this);
                    const goalId = $row.attr('data-goal-id');
                    
                    if (goalId) {
                        setSavedState($row);
                    } else {
                        $row.find('input, select').prop('disabled', false);
                        $row.find('.save-goal-btn, .remove-goal-btn').show();
                        $row.find('.edit-goal-btn, .update-goal-btn').hide();
                    }
                });
            }
            
            // Update totals
            function updateTotals() {
                const employeeId = $employeeId.val();
                if (!employeeId) {
                    $('#weightTotal').text('0.0%');
                    $('#scoreTotal').text('#DIV/0!');
                    return;
                }
                
                ajaxRequest('/scorecard/getTotalCalculations', { 
                    employee_id: employeeId,
                    evaluation_period: $('#evaluation_period').val() || '2025'
                }, res => {
                    if (res.status === 'success') {
                        const totalWeight = parseFloat(res.data.grand_total_weight) || 0;
                        const totalScore = parseFloat(res.data.grand_total_score) || 0;
                        
                        $('#weightTotal').text(totalWeight.toFixed(1) + '%');
                        $('#scoreTotal').text(totalWeight > 0 ? ((totalScore / totalWeight) * 100).toFixed(1) + '%' : '#DIV/0!');
                    } else {
                        $('#scoreTotal').text('Error');
                    }
                }, () => $('#scoreTotal').text('Error'));
            }
            
            // EVENT HANDLERS
            $(document).on('click', '.add-goal-btn', function() {
                const $row = $(this).closest('tr');
                let kraId = $row.attr('data-kra-id');
                
                if ($row.hasClass('kra-row') || $row.find('.kra-select').length > 0) {
                    kraId = $row.find('.kra-select').val();
                    if (!kraId) return Swal.fire({ icon: 'error', title: 'KRA Required', text: 'Please select a KRA first' });
                    if (!$row.attr('data-goal-id')) return Swal.fire({ icon: 'warning', title: 'Save Current Row First', text: 'Please save the current row before adding a new goal.' });
                } else if (!kraId) {
                    return Swal.fire({ icon: 'error', title: 'Error', text: 'Please select a KRA first' });
                }
                addGoalToKRA(kraId);
            });
            
            $(document).on('click', '.remove-goal-btn', function() {
                const $row = $(this).closest('tr');
                removeGoalFromKRA($row.attr('data-goal-id'), $row, $row.attr('data-kra-id'));
            });
            
            $(document).on('click', '.save-goal-btn', function() {
                const $row = $(this).closest('tr');
                const kraId = $row.hasClass('kra-row') ? $row.find('.kra-select').val() : $row.attr('data-kra-id');
                if (!kraId) return Swal.fire('Error', 'Please select a KRA first', 'error');
                
                Swal.fire({
                    title: 'Are you sure?', text: "Do you want to save this goal?", icon: 'question',
                    showCancelButton: true, confirmButtonText: 'Yes, save it!'
                }).then(result => {
                    if (result.isConfirmed) saveGoalData($row);
                });
            });
            
            $(document).on('click', '.edit-goal-btn', function() {
                const $row = $(this).closest('tr');
                $row.find('input, select').prop('disabled', false);
                $(this).hide();
                $row.find('.update-goal-btn, .remove-goal-btn').show();
                $row.find('.save-goal-btn').hide();
            });
            
            $(document).on('click', '.update-goal-btn', function() {
                const $row = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure?', text: "Do you want to update this goal?", icon: 'question',
                    showCancelButton: true, confirmButtonText: 'Yes, update it!'
                }).then(result => {
                    if (result.isConfirmed) updateGoalData($row);
                });
            });
            
            $(document).on('input', '.rating-input, .weight-input, .period-select', () => setTimeout(updateTotals, 300));
            
            // Initialize
            initAutocomplete();
            if (!$('#scorecardTable tbody tr').length) addInitialRow();
            else initSavedGoals();
        });         
    </script>



    <script>
    var coll = document.getElementsByClassName("collapsible");
    var i;
    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
    // end collapsible


    // start of scorecard
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all KRA sections
        initializeKRASection('goalBody', 'weightTotal', 'scoreTotal', 'addKRABtn', 'removeKRABtn', 'kraRowTemplate', 'goalRowTemplate');
        initializeKRASection('goalBodyStrategic', 'weightTotalStrategic', 'scoreTotalStrategic', 'addKRABtnStrategic', 'removeKRABtnStrategic', 'kraRowTemplateStrategic', 'goalRowTemplateStrategic');
        initializeKRASection('goalBodyOperational', 'weightTotalOperational', 'scoreTotalOperational', 'addKRABtnOperational', 'removeKRABtnOperational', 'kraRowTemplateOperational', 'goalRowTemplateOperational');
        initializeKRASection('goalBodyLearning', 'weightTotalLearning', 'scoreTotalLearning', 'addKRABtnLearning', 'removeKRABtnLearning', 'kraRowTemplateLearning', 'goalRowTemplateLearning');
        
        function initializeKRASection(bodyId, weightTotalId, scoreTotalId, addKRABtnId, removeKRABtnId, kraRowTemplateId, goalRowTemplateId) {
            const body = document.getElementById(bodyId);
            const wtCell = document.getElementById(weightTotalId);
            const scoreTotal = document.getElementById(scoreTotalId);
            const btnAddKRA = document.getElementById(addKRABtnId);
            const btnRemoveKRA = document.getElementById(removeKRABtnId);
            const kraRowTemplate = document.getElementById(kraRowTemplateId);
            const goalRowTemplate = document.getElementById(goalRowTemplateId);
            
            // Skip initialization if any required element is missing
            if (!body || !wtCell || !scoreTotal || !btnAddKRA || !btnRemoveKRA || !kraRowTemplate || !goalRowTemplate) {
                console.warn(`Skipping initialization for ${bodyId} - missing required elements`);
                return;
            }
            
            let kraCount = 0;    // Track number of KRAs
            let goalsByKRA = {}; // Track goals per KRA
            
            function addKRASection() {
                kraCount++;
                const kraId = bodyId + '-kra-' + kraCount;
                goalsByKRA[kraId] = 0;
                const tr = kraRowTemplate.content.cloneNode(true).querySelector('tr');
                tr.dataset.kraId = kraId;
                const kraCell = tr.querySelector('.kra-cell');
                kraCell.dataset.kraId = kraId;
                setupRowEventListeners(tr, kraId);
               body.appendChild(tr);
                
                goalsByKRA[kraId]++;
                
                return kraId;
            }
            
            function setupRowEventListeners(row, kraId) {
                // Add button event
                const addBtn = row.querySelector('.add-goal-btn');
                if (addBtn) {
                    addBtn.addEventListener('click', function() {
                        addGoalRow(kraId);
                    });
                }
                
                // Remove button event
                const removeBtn = row.querySelector('.remove-goal-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        removeGoalRow(kraId);
                    });
                }
                
                // Save button event
                const saveBtn = row.querySelector('.save-goal-btn');
                if (saveBtn) {
                    saveBtn.addEventListener('click', function() {
                    });
                }
                
                // Update button event
                const updateBtn = row.querySelector('.update-goal-btn');
                if (updateBtn) {
                    updateBtn.addEventListener('click', function() {
                    });
                }
            }
            
            function removeKRASection() {
                if (kraCount <= 1) {
                    alert('Cannot remove the last KRA section.');
                    return;
                }
            
                const kraIds = Array.from(body.querySelectorAll('tr[data-kra-id]'))
                    .map(row => row.dataset.kraId)
                    .filter((value, index, self) => self.indexOf(value) === index); // Get unique KRA IDs
                if (kraIds.length > 0) {
                    const lastKraId = kraIds[kraIds.length - 1];
                    const rows = Array.from(body.querySelectorAll(`tr[data-kra-id="${lastKraId}"]`));
                    rows.forEach(row => row.remove());
                    delete goalsByKRA[lastKraId];
                    kraCount--;
                }
            }
            btnAddKRA.addEventListener('click', function() {
                addKRASection();
            });           
            btnRemoveKRA.addEventListener('click', function() {
                removeKRASection();
            });
            addKRASection();
        }
    });
    // end of scorecard
    </script>