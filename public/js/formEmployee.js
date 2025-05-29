   
$(document).ready(function() {
    let goalsByKRA = {};
    
    // Define BSC percentages by job classification
    const bscPercentages = {
        'Rank-and-File': {
            'Financial': 5,
            'Strategic': 5,
            'Operational': 70,
            'Learning': 10
        },
        'Supervisory/Specialist': {
            'Financial': 10,
            'Strategic': 10,
            'Operational': 70,
            'Learning': 5
        },
        'Managerial and Up': {
            'Financial': 20,
            'Strategic': 10,
            'Operational': 60,
            'Learning': 5
        }
    };
    
    // Function to update header percentages
    function updateHeaderPercentages(classification) {
        const percentages = bscPercentages[classification] || bscPercentages['Rank-and-File'];
        
        // Update each header text while preserving the action buttons
        $('th[colspan="22"]').each(function() {
            const $header = $(this);
            const headerText = $header.text().trim();
            const $actionBtns = $header.find('.action-btns').clone(); // Clone the action buttons
            
            if (headerText.includes('FINANCIAL')) {
                $header.html(`FINANCIAL (${percentages.Financial}%) – goals that contribute to the company's profitability`);
            } else if (headerText.includes('STRATEGIC')) {
                $header.html(`STRATEGIC OR CUSTOMER (${percentages.Strategic}%) - goals that bring the organization to the higher level of performance or state`);
            } else if (headerText.includes('OPERATIONAL')) {
                $header.html(`OPERATIONAL (${percentages.Operational}%) - goals that bring the organization to the higher level of performance or state`);
            } else if (headerText.includes('LEARNING')) {
                $header.html(`LEARNING AND GROWTH (${percentages.Learning}%) - goals that bring the organization to the higher level of performance or state`);
            }
            
            // Re-append the action buttons
            if ($actionBtns.length) {
                $header.append($actionBtns);
            }
        });
    }
    
    // Listen for job classification changes and set initial percentages
    $('#job_classification').on('change', function() {
        const classification = $(this).val();
        updateHeaderPercentages(classification);
    }).trigger('change'); // Trigger change event to set initial percentages
    
        
        // Initialize Select2 for employee search
        const $employeeSearch = $('#employee_search');
        
        $employeeSearch.select2({
            placeholder: 'Select Employee',
            allowClear: true,
            minimumInputLength: 0,
            dropdownParent: $employeeSearch.parent(),
            dropdownCssClass: 'select2-dropdown-above',
            containerCssClass: 'select2-container-above',
            ajax: {
                url: '/scorecard2/getEmployees',
                type: 'POST',
                dataType: 'json',
                delay: 0,
                data: function(params) {
                    return {
                        search: params.term || '' // Send empty string if no search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.data.map(employee => ({
                            id: employee.id,
                            text: `${employee.firstname} ${employee.lastname}`
                        }))
                    };
                },
                cache: true
            },
            tags: false,
            tokenSeparators: [],
            width: '100%'
        }).on('select2:select', function(e) {
            const employeeId = e.params.data.id;
            loadEmployeeData(employeeId);
            setTimeout(() => loadExistingGoals(employeeId), 100);
            setTimeout(updateTotals, 300);
            setTimeout(checkWeightLimit, 400);
        });

        // Set initial value if employee_id exists
        const initialEmployeeId = $('#employee_search').val();
        if (initialEmployeeId) {
            ajaxRequest('/scorecard2/getEmployeeData', { employee_id: initialEmployeeId }, function(res) {
                if (res.status === 'success' && res.data) {
                    const employee = res.data;
                    const option = new Option(
                        `${employee.firstname} ${employee.lastname}`,
                        employee.id,
                        true,
                        true
                    );
                    $('#employee_search').append(option).trigger('change');
                    loadEmployeeData(employee.id);
                    setTimeout(() => loadExistingGoals(employee.id), 100);
                }
            });
        }
        
        // Calculate rating based on period and monthly values
        function calculateRating($row) {
            const period = $row.find('.period-select').val();
            const monthInputs = $row.find('.month-input');
            const months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
            
            let rating = 0;
            const monthValues = [];
            
            // Get all month values (convert to 1 or 0)
            monthInputs.each(function(index) {
                const val = $(this).val().trim();
                if (val === '' || val === '0') {
                    monthValues.push(0);
                } else {
                    monthValues.push(1);
                }
            });
            
            switch(period) {
                case 'Monthly':
                    // Count how many months have value of 1
                    rating = monthValues.reduce((sum, val) => sum + val, 0);
                    break;
                    
                case 'Quarterly':
                    // Q1: Jan-Mar, Q2: Apr-Jun, Q3: Jul-Sep, Q4: Oct-Dec
                    const quarters = [
                        monthValues.slice(0, 3),   // Q1: Jan-Mar
                        monthValues.slice(3, 6),   // Q2: Apr-Jun
                        monthValues.slice(6, 9),   // Q3: Jul-Sep
                        monthValues.slice(9, 12)   // Q4: Oct-Dec
                    ];
                    
                    rating = 0;
                    quarters.forEach(quarter => {
                        // All 3 months in quarter must have 1, otherwise quarter = 0
                        const quarterScore = quarter.every(val => val === 1) ? 1 : 0;
                        rating += quarterScore;
                    });
                    break;
                    
                case 'Semi Annual':
                    // H1: Jan-Jun, H2: Jul-Dec
                    const firstHalf = monthValues.slice(0, 6);   // Jan-Jun
                    const secondHalf = monthValues.slice(6, 12); // Jul-Dec
                    
                    rating = 0;
                    // All 6 months in first half must have 1
                    if (firstHalf.every(val => val === 1)) rating += 1;
                    // All 6 months in second half must have 1
                    if (secondHalf.every(val => val === 1)) rating += 1;
                    break;
                    
                case 'Annual':
                    // All 12 months must have 1
                    rating = monthValues.every(val => val === 1) ? 1 : 0;
                    break;
                    
                default:
                    rating = 0;
            }
            
            return rating;
        }
        
        // Calculate score based on rating and weight
        function calculateScore($row) {
            const weight = parseFloat($row.find('.weight-input').val()) || 0;
            const rating = parseFloat($row.find('.rating-input').val()) || 0;
            
            if (weight === 0) return '#DIV/0!';
            
            // Score = (Rating / Max Rating) * Weight * 100
            // Max ratings: Monthly=12, Quarterly=4, Semi Annual=2, Annual=1
            const period = $row.find('.period-select').val();
            let maxRating = 1;
            
            switch(period) {
                case 'Monthly': maxRating = 12; break;
                case 'Quarterly': maxRating = 4; break;
                case 'Semi Annual': maxRating = 2; break;
                case 'Annual': maxRating = 1; break;
            }
            
            const score = (rating / maxRating) * weight;
            return score.toFixed(1) + '%';
        }
        
        // Update rating and score for a row
        function updateRowCalculations($row) {
            const newRating = calculateRating($row);
            $row.find('.rating-input').val(newRating);
            
            const newScore = calculateScore($row);
            const $scoreSpan = $row.find('.score-value');
            $scoreSpan.text(newScore);
            
            // Update badge color based on score
            $scoreSpan.removeClass('bg-danger bg-warning bg-success');
            if (newScore === '#DIV/0!' || newScore === '0.0%') {
                $scoreSpan.addClass('bg-danger');
            } else {
                const scoreNum = parseFloat(newScore);
                if (scoreNum >= 80) $scoreSpan.addClass('bg-success');
                else if (scoreNum >= 60) $scoreSpan.addClass('bg-warning');
                else $scoreSpan.addClass('bg-danger');
            }
            
            // Update totals after calculation
            setTimeout(updateTotals, 100);
        }
        
    // Check weight limits for each category
    function checkWeightLimit() {
        const employeeId = $employeeSearch.val();
        if (!employeeId) return;
        
        const evaluationPeriod = $('#evaluation_period').val() || '2025';
        const categories = ['financial', 'strategic', 'operational', 'learning'];
        
        categories.forEach(category => {
            ajaxRequest('/scorecard2/checkCategoryLimit', {
                employee_id: employeeId,
                evaluation_period: evaluationPeriod,
                category: category
            }, function(res) {
                if (res.status === 'success') {
                    const limitInfo = res.data;
                    let selector;
                    
                    // Determine the correct selector based on category
                    switch(category) {
                        case 'financial':
                            selector = '#scorecardTable';
                            break;
                        case 'strategic':
                            selector = '#goalBodyStrategic';
                            break;
                        case 'operational':
                            selector = '#goalBodyOperational';
                            break;
                        case 'learning':
                            selector = '#goalBodyLearning';
                            break;
                    }
                    
                    const $addBtn = $(`${selector} .add-goal-btn`);
                    
                    if (limitInfo.is_limit_reached) {
                        $addBtn
                            .prop('disabled', true)
                            .attr('title', `Weight limit reached (${limitInfo.weight_limit}%)`)
                            .removeClass('btn-success')
                            .addClass('btn-secondary');
                    } else {
                        $addBtn
                            .prop('disabled', false)
                            .attr('title', 'Add new goal')
                            .removeClass('btn-secondary')
                            .addClass('btn-success');
                    }
                }
            });
        });
    }
        
        
        // Load employee data
        function loadEmployeeData(id) {
            if (!id) return;
            
            $.ajax({
                url: '/scorecard2/getEmployeeData',
                type: 'POST',
                data: { 
                    employee_id: id,
                    evaluation_period: $('#evaluation_period').val() || '2025'
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success' && res.data) {
                        // Employee basic info
                        $('#job_title').val(res.data.position_title || res.data.job_title || '');
                        $('#department').val(res.data.department || '');
                        
                        // Scorecard-specific data (reviewer info)
                        if (res.data.scorecard) {
                            $('#reviewer').val(res.data.scorecard.reviewer || '');
                            $('#reviewer_designation').val(res.data.scorecard.reviewer_designation || '');
                            $('#job_classification').val(res.data.scorecard.job_classification || '');
                        } else {
                            // Fallback to employee data if no scorecard exists yet
                            $('#reviewer').val(res.data.reviewer || '');
                            $('#reviewer_designation').val(res.data.reviewer_designation || '');
                            $('#job_classification').val('');
                        }
                    } else {
                        $('#job_title, #department, #reviewer, #reviewer_designation, #job_classification').val('');
                    }
                },
                error: () => $('#job_title, #department, #reviewer, #reviewer_designation, #job_classification').val('')
            });
        }
        
        // Load existing goals for Financial and Learning sections
        function loadExistingGoals(id) {
            if (!id) return;
            
            // Clear both Financial and Learning sections
            $('#scorecardTable tbody').empty();
            $('#goalBodyLearning').empty();
            goalsByKRA = {};

            // Reset button states
            $('.add-goal-btn')
                .prop('disabled', false)
                .attr('title', 'Add new goal')
                .removeClass('btn-secondary')
                .addClass('btn-success');
            
            $.ajax({
                url: '/scorecard2/loadGoals',
                type: 'POST',
                data: { 
                    employee_id: id,
                    evaluation_period: $('#evaluation_period').val() || '2025'
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success' && res.data?.length) {
                        // Group goals by category and kra_id
                        const groupedByCategory = {};
                        res.data.forEach(goal => {
                            if (!groupedByCategory[goal.category]) groupedByCategory[goal.category] = {};
                            if (!groupedByCategory[goal.category][goal.kra_id]) groupedByCategory[goal.category][goal.kra_id] = [];
                            groupedByCategory[goal.category][goal.kra_id].push(goal);
                        });
                        
                        // Populate Financial section
                        if (groupedByCategory.financial) {
                            Object.entries(groupedByCategory.financial).forEach(([kraId, goals]) => {
                                goalsByKRA[kraId] = goals.length;
                                goals.forEach((goal, idx) => {
                                    const $row = (idx === 0 ? $('#kraRowTemplate') : $('#goalRowTemplate')).contents().clone();
                                    if (idx === 0) {
                                        $row.find('.kra-select').val(kraId);
                                        $row.find('.kra-cell').attr('rowspan', goals.length).attr('data-kra-id', kraId);
                                    }
                                    populateRowWithData($row, goal);
                                    $row.attr('data-goal-id', goal.id).attr('data-kra-id', kraId);
                                    $('#scorecardTable tbody').append($row);
                                    updateRowCalculations($row);
                                });
                            });
                        } else {
                            addInitialRow();
                        }
                        
                        // Populate Learning section
                        if (groupedByCategory.learning) {
                            Object.entries(groupedByCategory.learning).forEach(([kraId, goals]) => {
                                goalsByKRA[kraId] = goals.length;
                                goals.forEach((goal, idx) => {
                                    const $row = (idx === 0 ? $('#kraRowTemplateLearning') : $('#goalRowTemplateLearning')).contents().clone();
                                    if (idx === 0) {
                                        $row.find('select[name="kra"]').val(kraId);
                                        $row.find('.kra-cell').attr('rowspan', goals.length).attr('data-kra-id', kraId);
                                    }
                                    populateRowWithData($row, goal);
                                    $row.attr('data-goal-id', goal.id).attr('data-kra-id', kraId);
                                    $('#goalBodyLearning').append($row);
                                    updateRowCalculations($row);
                                    
                                    // Set initial state for Learning section rows
                                    $row.find('input, select').prop('disabled', true);
                                    $row.find('.save-goal-btn, .update-goal-btn').hide();
                                    $row.find('.edit-goal-btn, .remove-goal-btn').show();
                                });
                            });
                        }
                        
                        setTimeout(() => {
                            initSavedGoals();
                            updateTotals();
                            checkWeightLimit();
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
            
            // Set initial score display
            $row.find('.score-value').text('#DIV/0!').addClass('bg-danger');
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
                        checkWeightLimit();
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
            
            // Fixed: Use rating_period from database instead of period
            $row.find('.period-select').val(goal.rating_period || '');
            $row.find('.rating-input').val(goal.rating || '');
            $row.find('.evidence-input').val(goal.evidence || '');
            
            // Fixed: Use _value suffix for month columns
            ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec']
                .forEach(month => {
                    const val = goal[`${month}_value`] || '';
                    $row.find(`input[name="${month}"]`).val(val);
                });
        }
        
        // Clear form
        function clearForm() {
            $('#job_title, #department, #reviewer, #reviewer_designation').val('');
            $employeeSearch.val(null).trigger('change');
            $('#scorecardTable tbody').empty();
            goalsByKRA = {};
            $('#weightTotal').text('0.0%');
            $('#scoreTotal').text('#DIV/0!');
            addInitialRow();
        }
        
        // Add initial row
        function addInitialRow() {
            // Add initial row for Financial section
            const $financialRow = $('#kraRowTemplate').contents().clone();
            $financialRow.find('.kra-cell').attr('rowspan', 1);
            $financialRow.find('.score-value').text('#DIV/0!').addClass('bg-danger');
            $('#scorecardTable tbody').append($financialRow);

            // Add initial row for Learning section
            const $learningRow = $('#kraRowTemplateLearning').contents().clone();
            $learningRow.find('.kra-cell').attr('rowspan', 1);
            $learningRow.find('.score-value').text('#DIV/0!').addClass('bg-danger');
            $('#goalBodyLearning').append($learningRow);

            // Initialize both sections
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
            ajaxRequest('/scorecard2/deleteGoal', { goal_id: goalId }, function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false,
                        willClose: () => {
                            const empId = $employeeSearch.val();
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
        const isLearningSection = $row.closest('#goalBodyLearning').length > 0;
        const isStrategicSection = $row.closest('#goalBodyStrategic').length > 0;
        const isOperationalSection = $row.closest('#goalBodyOperational').length > 0;
        
        // Determine category/category based on section
        let category;
        if (isLearningSection) category = 'learning';
        else if (isStrategicSection) category = 'strategic';
        else if (isOperationalSection) category = 'operational';
        else category = 'financial';
        
        // Get KRA ID based on section
        let kraId;
        if (isLearningSection) {
            kraId = isKraRow ? $row.find('select[name="kra"]').val() : $row.data('kra-id');
        } else {
            kraId = isKraRow ? $row.find('.kra-select').val() : $row.data('kra-id');
        }
        
        const employeeId = $employeeSearch.val();
            
            if (!employeeId) return Swal.fire('Error', 'Please select an employee first', 'error');
            if (!kraId) return Swal.fire('Error', 'KRA is required', 'error');
            
            const goal = $row.find('.goal-input').val().trim();
            const weight = $row.find('.weight-input').val().trim();
            
            if (!goal) return Swal.fire('Error', 'Goal description is required', 'error');
            if (weight && isNaN(parseFloat(weight))) return Swal.fire('Error', 'Weight must be a valid number', 'error');
            
            const weightValue = parseFloat(weight) || 0;
            if (weightValue > 10) {
                return Swal.fire('Error', 'Weight cannot exceed 10%', 'error');
            }
            
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
                category: category,
                job_title: $('#job_title').val() || '',
                department: $('#department').val() || '',
                reviewer: $('#reviewer').val() || '',
                reviewer_designation: $('#reviewer_designation').val() || '',
                job_classification: $('#job_classification').val() || '',
                goal, 
                measurement: $row.find('.measurement-select').val() || 'Savings',
                weight: weightValue,
                target: $row.find('.target-input').val() || '',
                rating_period: $row.find('.period-select').val() || 'Annual',
                rating: parseFloat($row.find('.rating-input').val()) || null,
                evidence: $row.find('.evidence-input').val() || ''
            };
            
            ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec']
                .forEach(month => {
                    const val = $row.find(`input[name="${month}"]`).val();
                    data[`${month}_value`] = val && val.trim() !== '' ? val.trim() : null;
                });
            
            const $saveBtn = $row.find('.save-goal-btn');
            $saveBtn.prop('disabled', true).text('Saving...');
            
            ajaxRequest('/scorecard2/saveGoal', data, function(res) {
                if (res.status === 'success') {
                    $row.attr('data-goal-id', res.goal_id);
                    setSavedState($row);
                    updateTotals();
                    checkWeightLimit();
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
            
            const weightValue = parseFloat(weight) || 0;
            if (weightValue > 10) {
                return Swal.fire('Error', 'Weight cannot exceed 10%', 'error');
            }
            
            const data = { 
                goal_id: goalId, 
                goal,
                measurement: $row.find('.measurement-select').val() || 'Savings',
                weight: weightValue,
                target: $row.find('.target-input').val() || '',
                rating_period: $row.find('.period-select').val() || 'Annual',
                rating: $row.find('.rating-input').val() ? parseFloat($row.find('.rating-input').val()) : null,
                evidence: $row.find('.evidence-input').val() || '',
                job_classification: $('#job_classification').val() || ''
            };
            
            ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec']
                .forEach(month => {
                    const val = $row.find(`input[name="${month}"]`).val();
                    data[`${month}_value`] = val && val.trim() !== '' ? val.trim() : null;
                });
            
            const $updateBtn = $row.find('.update-goal-btn');
            $updateBtn.prop('disabled', true).text('Updating...');
            
            ajaxRequest('/scorecard2/updateGoal', data, function(res) {
                if (res.status === 'success') {
                    setSavedState($row);
                    updateTotals();
                    checkWeightLimit();
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
            // Initialize Financial section
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

            // Initialize Learning section
            $('#goalBodyLearning tr').each(function() {
                const $row = $(this);
                const goalId = $row.attr('data-goal-id');
                
                if (goalId) {
                    // For saved goals, disable inputs and show edit button
                    $row.find('input, select').prop('disabled', true);
                    $row.find('.save-goal-btn, .update-goal-btn').hide();
                    $row.find('.edit-goal-btn, .remove-goal-btn').show();
                } else {
                    // For new goals, disable inputs until edit is clicked
                    $row.find('input, select').prop('disabled', true);
                    $row.find('.save-goal-btn, .update-goal-btn').hide();
                    $row.find('.edit-goal-btn, .remove-goal-btn').show();
                }
            });
        }
        
        // Update totals
    function updateTotals() {
        const employeeId = $employeeSearch.val();
        if (!employeeId) {
            // Update totals for both Financial and Learning sections
            $('#weightTotal, #weightTotalLearning').text('0.0%');
            $('#scoreTotal, #scoreTotalLearning').text('0.0%');
            return;
        }
        
        // Get weights by perspective
        ajaxRequest('/scorecard2/getWeightsByPerspective', { 
            employee_id: employeeId,
            evaluation_period: $('#evaluation_period').val() || '2025'
        }, function(res) {
            if (res.status === 'success') {
                const weights = res.data;
                
                // Update Financial section
                const financialWeight = weights.financial || 0;
                const financialScore = calculateSectionScore('goalBody') || 0;
                $('#weightTotal').text(financialWeight.toFixed(1) + '%');
                $('#scoreTotal').text(financialScore.toFixed(1) + '%');
                
                // Update Learning section
                const learningWeight = weights.learning || 0;
                const learningScore = calculateSectionScore('goalBodyLearning') || 0;
                $('#weightTotalLearning').text(learningWeight.toFixed(1) + '%');
                $('#scoreTotalLearning').text(learningScore.toFixed(1) + '%');
            } else {
                $('#scoreTotal, #scoreTotalLearning').text('Error');
            }
        }, () => {
            $('#scoreTotal, #scoreTotalLearning').text('Error');
        });
    }

    // Helper function to calculate section score
    function calculateSectionScore(sectionId) {
        let totalScore = 0;
        $(`#${sectionId} tr`).each(function() {
            const $row = $(this);
            const scoreText = $row.find('.score-value').text();
            if (scoreText !== '#DIV/0!') {
                const score = parseFloat(scoreText) || 0;
                totalScore += score;
            }
        });
        return totalScore;
    }
    // EVENT HANDLERS
    $(document).on('click', '.add-goal-btn', function() {
        const $row = $(this).closest('tr');
        let kraId = $row.attr('data-kra-id');
        
        // Check if this is in the Learning section
        const isLearningSection = $row.closest('#goalBodyLearning').length > 0;
        
        if ($row.hasClass('kra-row') || $row.find('.kra-select').length > 0) {
            // For Learning section, get KRA value differently
            if (isLearningSection) {
                kraId = $row.find('select[name="kra"]').val();
            } else {
                kraId = $row.find('.kra-select').val();
            }
            
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
    
    // Auto-calculate rating when month values or period changes
    $(document).on('input', '.month-input', function() {
        const $row = $(this).closest('tr');
        updateRowCalculations($row);
    });
    
    $(document).on('change', '.period-select', function() {
        const $row = $(this).closest('tr');
        updateRowCalculations($row);
    });
    
    $(document).on('input', '.weight-input', function() {
        const $row = $(this).closest('tr');
        const currentWeight = parseFloat($(this).val()) || 0;
        const employeeId = $employeeSearch.val();
        
        if (!employeeId) return;
        
        // Check if current weight exceeds 10%
        if (currentWeight > 10) {
            $(this).val('10.00');
            Swal.fire({
                icon: 'warning',
                title: 'Weight Limit Exceeded',
                text: 'Maximum weight per goal is 10%',
                timer: 2000,
                showConfirmButton: false
            });
            currentWeight = 10;
        }
        
        // Get weights by perspective
        const isLearningSection = $row.closest('#goalBodyLearning').length > 0;
        const isStrategicSection = $row.closest('#goalBodyStrategic').length > 0;
        const isOperationalSection = $row.closest('#goalBodyOperational').length > 0;
        
        // Determine category/category based on section
        let category;
        if (isLearningSection) category = 'learning';
        else if (isStrategicSection) category = 'strategic';
        else if (isOperationalSection) category = 'operational';
        else category = 'financial';
        
        // Get current weights by perspective
            ajaxRequest('/scorecard2/getWeightsByPerspective', {
                employee_id: employeeId,
                evaluation_period: $('#evaluation_period').val() || '2025'
            }, function(res) {
                if (res.status === 'success') {
                    const weights = res.data;
                    const classification = $('#job_classification').val() || 'Rank-and-File';
                    const limits = bscPercentages[classification];
                    
                    // Get weight limit for current category
                    const categoryName = category.charAt(0).toUpperCase() + category.slice(1);
                    const weightLimit = limits[categoryName];
                    
                    // Calculate total weight for this category excluding current goal
                    const otherGoalsWeight = (weights[category] || 0) - (parseFloat($row.attr('data-original-weight')) || 0);
                    const totalWeight = otherGoalsWeight + currentWeight;
                    
                    if (totalWeight > weightLimit) {
                        const maxAllowed = weightLimit - otherGoalsWeight;
                        $(this).val(maxAllowed.toFixed(2));
                        Swal.fire({
                            icon: 'warning',
                            title: 'Weight Limit Exceeded',
                            text: `Total weight for ${categoryName} cannot exceed ${weightLimit}%. Maximum allowed for this goal: ${maxAllowed.toFixed(2)}%`,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        
        updateRowCalculations($row);
        setTimeout(updateTotals, 300);
        setTimeout(checkWeightLimit, 400);
    });
    
    // Initialize
    if (!$('#scorecardTable tbody tr').length) addInitialRow();
    else initSavedGoals();
});      
   




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

    });
    // end of scorecard
   