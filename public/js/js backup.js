$(document).ready(function() {
    // Handle save button click for new records
    $(document).on('click', '.save-goal-btn', function() {
        let $btn = $(this);
        let $row = $btn.closest('tr');
        
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save this goal?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                saveGoalData($row);
            }
        });
    });
    
    // Handle edit button click
    $(document).on('click', '.edit-goal-btn', function() {
        let $btn = $(this);
        let $row = $btn.closest('tr');
        
        // Enable form fields
        $row.find('input, select').prop('disabled', false);
        
        // Hide edit button, show update button
        $btn.hide();
        $row.find('.update-goal-btn').show();
        $row.find('.save-goal-btn').hide();
    });
    
    // Handle update button click
    $(document).on('click', '.update-goal-btn', function() {
        let $btn = $(this);
        let $row = $btn.closest('tr');
        
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this goal?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                updateGoalData($row);
            }
        });
    });
    
    // Add a goal row under the same KRA
    $(document).on('click', '.add-goal-btn', function() {
        let $btn = $(this);
        let $currentRow = $btn.closest('tr');
        let isKraRow = $currentRow.hasClass('kra-row');
        
        // Get the KRA ID
        let kraId;
        if (isKraRow) {
            kraId = $currentRow.find('.kra-select').val();
            // If KRA hasn't been selected yet, alert user
            if (!kraId) {
                Swal.fire('Error', 'Please select a KRA first', 'error');
                return;
            }
            // Store the KRA ID on the row for future reference
            $currentRow.attr('data-kra-id', kraId);
        } else {
            // For goal rows, get KRA ID from the data-kra-id attribute
            kraId = $currentRow.data('kra-id');
        }
        
        // Clone the goal row template
        let $template = $('#goalRowTemplate').clone().html();
        let $newRow = $($.parseHTML($template));
        
        // Set the KRA ID for the new goal row
        $newRow.attr('data-kra-id', kraId);
        
        // Insert the new row after the current row
        $currentRow.after($newRow);
        
        // Show save button, hide edit/update buttons
        $newRow.find('.save-goal-btn').show();
        $newRow.find('.edit-goal-btn').hide();
        $newRow.find('.update-goal-btn').hide();
    });
    
    // Remove goal row
    $(document).on('click', '.remove-goal-btn', function() {
        let $btn = $(this);
        let $row = $btn.closest('tr');
        
        // Don't remove if it's the last row for a KRA
        let kraId = $row.data('kra-id');
        let $kraRows = $('tr[data-kra-id="' + kraId + '"]');
        
        if ($kraRows.length <= 1 && $row.hasClass('kra-row')) {
            Swal.fire('Error', 'Cannot remove the last row for this KRA', 'error');
            return;
        }
        
        // If the row has a goal ID, it exists in database and needs to be deleted
        let goalId = $row.attr('data-goal-id');
        
        if (goalId) {
            // Confirm deletion from database
            Swal.fire({
                title: 'Delete Goal',
                text: "Are you sure you want to delete this goal? This cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete the goal
                    $.ajax({
                        url: '/scorecard/deleteGoal',
                        type: 'POST',
                        data: { goal_id: goalId },
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                $row.remove();
                                Swal.fire('Deleted!', 'Goal has been deleted.', 'success');
                            } else {
                                Swal.fire('Error', res.message || 'Failed to delete goal', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'There was a problem connecting to the server', 'error');
                        }
                    });
                }
            });
        } else {
            // Just remove the row from DOM as it's not saved to database yet
            $row.remove();
        }
    });
    
    // Function to save goal data (for new goals only)
    function saveGoalData($row) {
        // Determine if it's a KRA row or goal row
        let isKraRow = $row.hasClass('kra-row');
        
        // Get KRA ID properly based on row type
        let kraId;
        if (isKraRow) {
            kraId = $row.find('.kra-select').val();
            // Store the KRA ID on the row for future reference
            $row.attr('data-kra-id', kraId);
        } else {
            // For goal rows, get KRA ID from the data-kra-id attribute
            kraId = $row.data('kra-id');
        }
        
        // Get employee ID
        let employeeId = $('#employee_select').val();
        
        // Validate required fields
        if (!employeeId) {
            Swal.fire('Error', 'Please select an employee first', 'error');
            return;
        }
        
        if (!kraId) {
            Swal.fire('Error', 'KRA is required', 'error');
            return;
        }
        
        // Get position title, department, reviewer, and reviewer designation
        const positionTitle = $('#job_title').val() || '';
        const department = $('#department').val() || '';
        const reviewer = $('#reviewer').val() || '';
        const reviewerDesignation = $('#reviewer_designation').val() || '';
        
        // Get perspective (tab)
        const perspective = $('.nav-link.active').data('perspective') || 'financial';
        
        // Collect form data
        let data = {
            employee_id: employeeId,
            evaluation_period: $('#evaluation_period').val(),
            kra_id: kraId,
            position_title: positionTitle,
            department: department,
            reviewer: reviewer,
            reviewer_designation: reviewerDesignation,
            perspective: perspective,
            goal: $row.find('.goal-input').val(),
            measurement: $row.find('.measurement-select').val(),
            weight: $row.find('.weight-input').val(),
            target: $row.find('.target-input').val(),
            period: $row.find('.period-select').val(),
            jan: $row.find('input[name="jan"]').val(),
            feb: $row.find('input[name="feb"]').val(),
            mar: $row.find('input[name="mar"]').val(),
            apr: $row.find('input[name="apr"]').val(),
            may: $row.find('input[name="may"]').val(),
            jun: $row.find('input[name="jun"]').val(),
            jul: $row.find('input[name="jul"]').val(),
            aug: $row.find('input[name="aug"]').val(),
            sep: $row.find('input[name="sep"]').val(),
            oct: $row.find('input[name="oct"]').val(),
            nov: $row.find('input[name="nov"]').val(),
            dec: $row.find('input[name="dec"]').val(),
            rating: $row.find('.rating-input').val(),
            evidence: $row.find('.evidence-input').val()
        };
        
        // Send Ajax request
        $.ajax({
            url: '/scorecard/saveGoal',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    // Store goal ID for new goals
                    if (res.goal_id) {
                        $row.attr('data-goal-id', res.goal_id);
                    }
                    
                    // Disable inputs
                    $row.find('input, select').prop('disabled', true);
                    
                    // Update button visibility
                    $row.find('.save-goal-btn').hide();
                    $row.find('.update-goal-btn').hide();
                    $row.find('.edit-goal-btn').show();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your goal has been saved successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Update the score calculation
                    updateScore($row);
                } else {
                    Swal.fire('Error', res.message || 'Error with goal operation', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire('Error', 'There was a problem connecting to the server. Please try again.', 'error');
            }
        });
    }
    
    // Function to update existing goal data (completely separate from save)
    function updateGoalData($row) {
        // Get goal ID (required for updates)
        let goalId = $row.attr('data-goal-id');
        
        if (!goalId) {
            Swal.fire('Error', 'Cannot update: Goal ID not found', 'error');
            return;
        }
        
        // Collect form data for update
        let data = {
            goal_id: goalId,
            goal: $row.find('.goal-input').val(),
            measurement: $row.find('.measurement-select').val(),
            weight: $row.find('.weight-input').val(),
            target: $row.find('.target-input').val(),
            period: $row.find('.period-select').val(),
            jan: $row.find('input[name="jan"]').val(),
            feb: $row.find('input[name="feb"]').val(),
            mar: $row.find('input[name="mar"]').val(),
            apr: $row.find('input[name="apr"]').val(),
            may: $row.find('input[name="may"]').val(),
            jun: $row.find('input[name="jun"]').val(),
            jul: $row.find('input[name="jul"]').val(),
            aug: $row.find('input[name="aug"]').val(),
            sep: $row.find('input[name="sep"]').val(),
            oct: $row.find('input[name="oct"]').val(),
            nov: $row.find('input[name="nov"]').val(),
            dec: $row.find('input[name="dec"]').val(),
            rating: $row.find('.rating-input').val(),
            evidence: $row.find('.evidence-input').val()
        };
        
        // Send Ajax request to update endpoint
        $.ajax({
            url: '/scorecard/updateGoal',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    // Disable inputs
                    $row.find('input, select').prop('disabled', true);
                    
                    // Update button visibility
                    $row.find('.save-goal-btn').hide();
                    $row.find('.update-goal-btn').hide();
                    $row.find('.edit-goal-btn').show();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your goal has been updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Update the score calculation
                    updateScore($row);
                } else {
                    Swal.fire('Error', res.message || 'Error updating goal', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire('Error', 'There was a problem connecting to the server. Please try again.', 'error');
            }
        });
    }
    
    // Function to update the score display
    function updateScore($row) {
        const weight = parseFloat($row.find('.weight-input').val()) || 0;
        const rating = parseFloat($row.find('.rating-input').val()) || 0;
        const period = $row.find('.period-select').val();
        
        let divisor = 1; // Default for Annual
        switch (period) {
            case 'Semi Annual':
                divisor = 2;
                break;
            case 'Quarterly':
                divisor = 4;
                break;
            case 'Monthly':
                divisor = 12;
                break;
        }
        
        if (weight > 0 && rating > 0) {
            const score = (rating / divisor) * weight;
            $row.find('.score-value')
                .text(score.toFixed(2))
                .removeClass('bg-danger')
                .addClass('bg-success');
        } else {
            $row.find('.score-value')
                .text('#DIV/0!')
                .removeClass('bg-success')
                .addClass('bg-danger');
        }
    }
    
    // Function to initialize saved goals with proper button states
    function initSavedGoals() {
        $('.kra-row, .goal-row').each(function() {
            const $row = $(this);
            if ($row.attr('data-goal-id')) {
                // This is a saved goal, show edit button and hide save button
                $row.find('.save-goal-btn').hide();
                $row.find('.edit-goal-btn').show();
                $row.find('.update-goal-btn').hide();
                $row.find('input, select').prop('disabled', true);
            } else {
                // This is a new goal
                $row.find('.save-goal-btn').show();
                $row.find('.edit-goal-btn').hide();
                $row.find('.update-goal-btn').hide();
            }
        });
    }
    
    // Update the KRA dropdowns when something is selected
    $(document).on('change', '.kra-select', function() {
        const $select = $(this);
        const $row = $select.closest('tr');
        const kraId = $select.val();
        
        if (kraId) {
            $row.attr('data-kra-id', kraId);
        }
    });
    
    // Load goals for a perspective when employee or period changes
    $('#employee_select, #evaluation_period').change(function() {
        loadGoalsForCurrentPerspective();
    });
    
    // Load goals for current perspective
    function loadGoalsForCurrentPerspective() {
        const employeeId = $('#employee_select').val();
        const evaluationPeriod = $('#evaluation_period').val();
        const perspective = $('.nav-link.active').data('perspective') || 'financial';
        
        if (employeeId && evaluationPeriod) {
            $.ajax({
                url: '/scorecard/loadGoals',
                type: 'POST',
                data: {
                    employee_id: employeeId,
                    evaluation_period: evaluationPeriod,
                    perspective: perspective
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        // Clear existing goals
                        $('#goals-table tbody').empty();
                        
                        // Group goals by KRA
                        const goalsByKra = {};
                        res.data.forEach(function(goal) {
                            if (!goalsByKra[goal.kra_id]) {
                                goalsByKra[goal.kra_id] = [];
                            }
                            goalsByKra[goal.kra_id].push(goal);
                        });
                        
                        // Process goals by KRA
                        Object.keys(goalsByKra).forEach(function(kraId) {
                            const goalsForKra = goalsByKra[kraId];
                            const firstGoal = goalsForKra[0];
                            
                            // Create KRA row for first goal
                            const $kraRow = createKraRow(firstGoal);
                            $('#goals-table tbody').append($kraRow);
                            
                            // Add additional goals under same KRA if any
                            if (goalsForKra.length > 1) {
                                for (let i = 1; i < goalsForKra.length; i++) {
                                    const $goalRow = createGoalRow(goalsForKra[i]);
                                    $('#goals-table tbody').append($goalRow);
                                }
                            }
                        });
                        
                        // Initialize goal rows
                        initSavedGoals();
                    }
                }
            });
        }
    }
    
    // Function to create a KRA row with goal data
    function createKraRow(goalData) {
        const $template = $($.parseHTML($('#kraRowTemplate').clone().html()));
        
        // Set KRA
        $template.find('.kra-select').val(goalData.kra_id);
        $template.attr('data-kra-id', goalData.kra_id);
        $template.attr('data-goal-id', goalData.id);
        
        // Fill in goal data
        $template.find('.goal-input').val(goalData.goal);
        $template.find('.measurement-select').val(goalData.measurement);
        $template.find('.weight-input').val(goalData.weight);
        $template.find('.target-input').val(goalData.target);
        $template.find('.period-select').val(goalData.rating_period);
        
        // Fill monthly values
        $template.find('input[name="jan"]').val(goalData.jan_value);
        $template.find('input[name="feb"]').val(goalData.feb_value);
        $template.find('input[name="mar"]').val(goalData.mar_value);
        $template.find('input[name="apr"]').val(goalData.apr_value);
        $template.find('input[name="may"]').val(goalData.may_value);
        $template.find('input[name="jun"]').val(goalData.jun_value);
        $template.find('input[name="jul"]').val(goalData.jul_value);
        $template.find('input[name="aug"]').val(goalData.aug_value);
        $template.find('input[name="sep"]').val(goalData.sep_value);
        $template.find('input[name="oct"]').val(goalData.oct_value);
        $template.find('input[name="nov"]').val(goalData.nov_value);
        $template.find('input[name="dec"]').val(goalData.dec_value);
        
        // Set rating and evidence
        $template.find('.rating-input').val(goalData.rating);
        $template.find('.evidence-input').val(goalData.evidence);
        
        // Update score
        if (goalData.score) {
            $template.find('.score-value')
                .text(parseFloat(goalData.score).toFixed(2))
                .removeClass('bg-danger')
                .addClass('bg-success');
        }
        
        return $template;
    }
    
    // Function to create a goal row (non-KRA row) with goal data
    function createGoalRow(goalData) {
        const $template = $($.parseHTML($('#goalRowTemplate').clone().html()));
        
        // Set data attributes
        $template.attr('data-kra-id', goalData.kra_id);
        $template.attr('data-goal-id', goalData.id);
        
        // Fill in goal data
        $template.find('.goal-input').val(goalData.goal);
        $template.find('.measurement-select').val(goalData.measurement);
        $template.find('.weight-input').val(goalData.weight);
        $template.find('.target-input').val(goalData.target);
        $template.find('.period-select').val(goalData.rating_period);
        
        // Fill monthly values
        $template.find('input[name="jan"]').val(goalData.jan_value);
        $template.find('input[name="feb"]').val(goalData.feb_value);
        $template.find('input[name="mar"]').val(goalData.mar_value);
        $template.find('input[name="apr"]').val(goalData.apr_value);
        $template.find('input[name="may"]').val(goalData.may_value);
        $template.find('input[name="jun"]').val(goalData.jun_value);
        $template.find('input[name="jul"]').val(goalData.jul_value);
        $template.find('input[name="aug"]').val(goalData.aug_value);
        $template.find('input[name="sep"]').val(goalData.sep_value);
        $template.find('input[name="oct"]').val(goalData.oct_value);
        $template.find('input[name="nov"]').val(goalData.nov_value);
        $template.find('input[name="dec"]').val(goalData.dec_value);
        
        // Set rating and evidence
        $template.find('.rating-input').val(goalData.rating);
        $template.find('.evidence-input').val(goalData.evidence);
        
        // Update score
        if (goalData.score) {
            $template.find('.score-value')
                .text(parseFloat(goalData.score).toFixed(2))
                .removeClass('bg-danger')
                .addClass('bg-success');
        }
        
        return $template;
    }
    
    // Initialize page
    initSavedGoals();
    
    // If an employee is already selected, load their goals
    if ($('#employee_select').val()) {
        loadGoalsForCurrentPerspective();
    }
});