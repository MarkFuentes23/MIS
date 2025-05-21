// Function to preserve form data across page reloads
function initFormPersistence() {
    // Generate a unique key for this form based on employee_id and evaluation_period
    function getFormKey() {
        const employeeId = $('#employee_select').val() || 'new';
        const period = $('#evaluation_period').val() || new Date().getFullYear();
        return `scorecard_data_${employeeId}_${period}`;
    }

    // Save all form data to localStorage
    function saveFormData() {
        const formKey = getFormKey();
        
        // Data structure to store all form information
        const formData = {
            // Basic form fields
            employee_id: $('#employee_select').val(),
            evaluation_period: $('#evaluation_period').val(),
            job_title: $('#job_title').val(),
            department: $('#department').val(),
            reviewer: $('#reviewer').val(),
            reviewer_designation: $('#reviewer_designation').val(),
            
            // Goal rows data (both saved and unsaved)
            goals: []
        };
        
        // Collect data from all KRA and goal rows
        $('.kra-row, .goal-row').each(function() {
            const $row = $(this);
            
            // Build goal object
            const goalData = {
                row_type: $row.hasClass('kra-row') ? 'kra' : 'goal',
                goal_id: $row.attr('data-goal-id') || null,
                kra_id: $row.hasClass('kra-row') ? $row.find('.kra-select').val() : $row.attr('data-kra-id'),
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
                evidence: $row.find('.evidence-input').val(),
                
                // Button states
                saved: $row.attr('data-goal-id') ? true : false,
                editing: $row.find('.update-goal-btn').is(':visible')
            };
            
            formData.goals.push(goalData);
        });
        
        // Save to localStorage
        localStorage.setItem(formKey, JSON.stringify(formData));
        console.log('Form data saved to localStorage with key:', formKey);
    }
    
    // Restore form data from localStorage
    function restoreFormData() {
        const formKey = getFormKey();
        const savedData = localStorage.getItem(formKey);
        
        if (!savedData) {
            console.log('No saved data found for key:', formKey);
            return false;
        }
        
        try {
            const formData = JSON.parse(savedData);
            console.log('Restoring form data:', formData);
            
            // Restore basic form fields
            $('#job_title').val(formData.job_title);
            $('#department').val(formData.department);
            $('#reviewer').val(formData.reviewer);
            $('#reviewer_designation').val(formData.reviewer_designation);
            
            // Handle goals restoration
            if (formData.goals && formData.goals.length > 0) {
                // First, clear any empty default rows
                if ($('.kra-row, .goal-row').length === 1 && !$('.kra-row, .goal-row').first().find('.goal-input').val()) {
                    $('.kra-row, .goal-row').remove();
                }
                
                // Function to create a new row from template
                function createRow(rowType) {
                    const template = rowType === 'kra' ? 
                        document.getElementById('kraRowTemplate') : 
                        document.getElementById('goalRowTemplate');
                    
                    const clone = document.importNode(template.content, true);
                    $('#scorecard-table tbody').append(clone);
                    return $('.kra-row, .goal-row').last();
                }
                
                // Track the last KRA ID for grouping
                let lastKraId = null;
                
                // Restore each goal row
                formData.goals.forEach(function(goalData) {
                    const $newRow = createRow(goalData.row_type);
                    
                    // Set all form values
                    if (goalData.row_type === 'kra') {
                        $newRow.find('.kra-select').val(goalData.kra_id);
                        lastKraId = goalData.kra_id;
                    } else {
                        // For goal rows, set the data-kra-id attribute
                        $newRow.attr('data-kra-id', goalData.kra_id || lastKraId);
                    }
                    
                    // Set the data-goal-id if it was saved
                    if (goalData.goal_id) {
                        $newRow.attr('data-goal-id', goalData.goal_id);
                    }
                    
                    // Set form values
                    $newRow.find('.goal-input').val(goalData.goal);
                    $newRow.find('.measurement-select').val(goalData.measurement);
                    $newRow.find('.weight-input').val(goalData.weight);
                    $newRow.find('.target-input').val(goalData.target);
                    $newRow.find('.period-select').val(goalData.period);
                    $newRow.find('input[name="jan"]').val(goalData.jan);
                    $newRow.find('input[name="feb"]').val(goalData.feb);
                    $newRow.find('input[name="mar"]').val(goalData.mar);
                    $newRow.find('input[name="apr"]').val(goalData.apr);
                    $newRow.find('input[name="may"]').val(goalData.may);
                    $newRow.find('input[name="jun"]').val(goalData.jun);
                    $newRow.find('input[name="jul"]').val(goalData.jul);
                    $newRow.find('input[name="aug"]').val(goalData.aug);
                    $newRow.find('input[name="sep"]').val(goalData.sep);
                    $newRow.find('input[name="oct"]').val(goalData.oct);
                    $newRow.find('input[name="nov"]').val(goalData.nov);
                    $newRow.find('input[name="dec"]').val(goalData.dec);
                    $newRow.find('.rating-input').val(goalData.rating);
                    $newRow.find('.evidence-input').val(goalData.evidence);
                    
                    // Handle button states
                    if (goalData.saved) {
                        // This is a saved goal
                        $newRow.find('.save-goal-btn').hide();
                        $newRow.find('.remove-goal-btn').hide();
                        
                        if (goalData.editing) {
                            // Goal is being edited
                            $newRow.find('.edit-goal-btn').hide();
                            $newRow.find('.update-goal-btn').show();
                            $newRow.find('input, select').prop('disabled', false);
                        } else {
                            // Goal is displayed normally
                            $newRow.find('.edit-goal-btn').show();
                            $newRow.find('.update-goal-btn').hide();
                            $newRow.find('input, select').prop('disabled', true);
                        }
                    } else {
                        // This is an unsaved goal
                        $newRow.find('.save-goal-btn').show();
                        $newRow.find('.edit-goal-btn').hide();
                        $newRow.find('.update-goal-btn').hide();
                        $newRow.find('.remove-goal-btn').show();
                    }
                    
                    // Update the score calculation
                    updateScore($newRow);
                });
                
                return true;
            }
            
            return false;
        } catch (error) {
            console.error('Error restoring form data:', error);
            return false;
        }
    }
    
    // Save form data when inputs change (debounced to prevent excessive saves)
    let saveTimeout;
    $(document).on('input change', '#scorecard-form input, #scorecard-form select', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveFormData, 500);
    });
    
    // Save after button clicks too (for row operations)
    $(document).on('click', '.save-goal-btn, .edit-goal-btn, .update-goal-btn, .remove-goal-btn, .add-goal-btn', function() {
        setTimeout(saveFormData, 500);
    });
    
    // Try to restore form data immediately after selecting an employee
    $(document).on('change', '#employee_select, #evaluation_period', function() {
        setTimeout(function() {
            if (!restoreFormData()) {
                console.log('No data restored, continuing with normal operation');
            }
        }, 100);
    });
    
    // Attempt to restore on page load after a short delay
    // (to ensure all DOM elements are properly initialized)
    setTimeout(function() {
        if ($('#employee_select').val()) {
            restoreFormData();
        }
    }, 500);
    
    // Handle page unload (save data before navigating away)
    $(window).on('beforeunload', saveFormData);
    
    console.log('Form persistence initialized');
}

// Initialize the persistence function when the document is ready
$(document).ready(function() {
    initFormPersistence();
});