
// start of collapsible
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
            
            // Create a new KRA section
            function addKRASection() {
                kraCount++;
                const kraId = bodyId + '-kra-' + kraCount;
                goalsByKRA[kraId] = 0;
                
                // Clone the KRA row template
                const tr = kraRowTemplate.content.cloneNode(true).querySelector('tr');
                tr.dataset.kraId = kraId;
                
                // Update KRA cell with kraId
                const kraCell = tr.querySelector('.kra-cell');
                kraCell.dataset.kraId = kraId;
                
                // Add event listeners
                setupRowEventListeners(tr, kraId);
                
                // Add the KRA row to the table
                body.appendChild(tr);
                
                // Increment goal count for this KRA
                goalsByKRA[kraId]++;
                
                return kraId;
            }
            
            // Setup event listeners for a row
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
                        // Your existing save logic here...
                    });
                }
                
                // Update button event
                const updateBtn = row.querySelector('.update-goal-btn');
                if (updateBtn) {
                    updateBtn.addEventListener('click', function() {
                        // Your existing update logic here...
                    });
                }
            }
            
            // Remove an entire KRA section
            function removeKRASection() {
                if (kraCount <= 1) {
                    alert('Cannot remove the last KRA section.');
                    return;
                }
                
                // Get all KRA IDs
                const kraIds = Array.from(body.querySelectorAll('tr[data-kra-id]'))
                    .map(row => row.dataset.kraId)
                    .filter((value, index, self) => self.indexOf(value) === index); // Get unique KRA IDs
                
                if (kraIds.length > 0) {
                    // Get the last KRA ID
                    const lastKraId = kraIds[kraIds.length - 1];
                    
                    // Remove all rows with this KRA ID
                    const rows = Array.from(body.querySelectorAll(`tr[data-kra-id="${lastKraId}"]`));
                    rows.forEach(row => row.remove());
                    
                    // Remove from tracking object
                    delete goalsByKRA[lastKraId];
                    kraCount--;
                }
            }
            
            // Initialize main buttons
            btnAddKRA.addEventListener('click', function() {
                addKRASection();
            });
            
            btnRemoveKRA.addEventListener('click', function() {
                removeKRASection();
            });
            
            // Add first KRA section on load
            addKRASection();
        }
    });
    // end of scorecard



    // employee auto populate
    document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_select');
    
    employeeSelect.addEventListener('change', function() {
        const employeeId = this.value;
        
        if (employeeId) {
            const formData = new FormData();
            formData.append('employee_id', employeeId);
            
            fetch('/scorecard/getEmployeeData', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Fixed: Use job_title (matches database column)
                    document.getElementById('job_title').value = data.data.job_title || '';
                    document.getElementById('department').value = data.data.department || '';
                } else {
                    console.error('Error:', data.message);
                    document.getElementById('job_title').value = '';
                    document.getElementById('department').value = '';
                }
            })
            .catch(error => {
                console.error('Error fetching employee data:', error);
                document.getElementById('job_title').value = '';
                document.getElementById('department').value = '';
            });
        } else {
            document.getElementById('job_title').value = '';
            document.getElementById('department').value = '';
        }
    });
    });
    //  employee auto populate end
    


    
    // financial save button 
    $(document).ready(function() {
        let lastSavedKraId = null;
        let goalsByKRA = {}; // Track goals per KRA
        
        // Load existing data when employee is selected
        $('#employee_select').on('change', function() {
            const employeeId = $(this).val();
            if (employeeId) {
                loadEmployeeData(employeeId);
                loadExistingGoals(employeeId);
                updateTotals();
            } else {
                clearForm();
            }
        });
        
        // Load employee basic data
        function loadEmployeeData(employeeId) {
            $.ajax({
                url: '/scorecard/getEmployeeData',
                type: 'POST',
                data: { employee_id: employeeId },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#job_title').val(res.data.job_title || '');
                        $('#department').val(res.data.department || '');
                    }
                },
                error: function() {
                    console.error('Failed to load employee data');
                }
            });
        }
        
        // Load existing goals
        function loadExistingGoals(employeeId) {
            // Clear table and reset tracking
            $('#scorecardTable tbody').empty();
            goalsByKRA = {};
            
            $.ajax({
                url: '/scorecard/loadGoals',
                type: 'POST',
                data: { 
                    employee_id: employeeId,
                    evaluation_period: $('#evaluation_period').val() || '2025'
                },
                dataType: 'json',
                success: function(res) {
                    console.log('Load Goals Response:', res);
                    
                    if (res.status === 'success' && res.data && res.data.length > 0) {
                        const groupedGoals = {};
                        res.data.forEach(goal => {
                            if (!groupedGoals[goal.kra_id]) {
                                groupedGoals[goal.kra_id] = [];
                            }
                            groupedGoals[goal.kra_id].push(goal);
                        });
                        
                        Object.keys(groupedGoals).forEach(kraId => {
                            const goals = groupedGoals[kraId];
                            
                            // Initialize tracking for this KRA
                            goalsByKRA[kraId] = goals.length;
                            
                            goals.forEach((goal, index) => {
                                let $row;
                                
                                if (index === 0) {
                                    $row = $('#kraRowTemplate').contents().clone();
                                    $row.find('.kra-select').val(goal.kra_id);
                                    // Set KRA cell rowspan and data attributes
                                    const $kraCell = $row.find('.kra-cell');
                                    $kraCell.attr('rowspan', goals.length);
                                    $kraCell.attr('data-kra-id', goal.kra_id);
                                    $row.attr('data-kra-id', goal.kra_id);
                                } else {
                                    $row = $('#goalRowTemplate').contents().clone();
                                    $row.attr('data-kra-id', goal.kra_id);
                                }
                                
                                populateRowWithData($row, goal);
                                $row.attr('data-goal-id', goal.id);
                                $('#scorecardTable tbody').append($row);
                            });
                        });
                        
                        setTimeout(function() {
                            initSavedGoals();
                            updateTotals();
                        }, 100);
                        
                    } else {
                        console.log('No existing goals found - adding empty row');
                        addInitialRow();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load goals:', xhr.responseText);
                    addInitialRow();
                }
            });
        }

        // Add goal row to existing KRA

        function addGoalToKRA(kraId) {
            console.log('Adding goal to KRA:', kraId);
            
            // Increment goal count for this KRA
            if (!goalsByKRA[kraId]) {
                goalsByKRA[kraId] = 0;
            }
            goalsByKRA[kraId]++;
            
            // Find and update KRA cell rowspan
            const $kraCell = $('#scorecardTable tbody').find(`td.kra-cell[data-kra-id="${kraId}"]`);
            if ($kraCell.length > 0) {
                $kraCell.attr('rowspan', goalsByKRA[kraId]);
            }
            
            // Create new goal row (NOT KRA row - just goal row)
            const $newRow = $('#goalRowTemplate').contents().clone();
            $newRow.attr('data-kra-id', kraId); // Set the KRA ID automatically
            
            // FIXED: Find the correct insertion point
            const $kraRows = $('#scorecardTable tbody tr[data-kra-id="' + kraId + '"]');
            
            if ($kraRows.length > 0) {
                // Find the last row of this specific KRA by checking actual DOM position
                let $insertAfter = null;
                let maxIndex = -1;
                
                $kraRows.each(function() {
                    const currentIndex = $(this).index();
                    if (currentIndex > maxIndex) {
                        maxIndex = currentIndex;
                        $insertAfter = $(this);
                    }
                });
                
                if ($insertAfter) {
                    // Insert immediately after the last row of this KRA
                    $insertAfter.after($newRow);
                } else {
                    // Fallback: append to end
                    $('#scorecardTable tbody').append($newRow);
                }
            } else {
                // If no existing rows for this KRA, this shouldn't happen but handle gracefully
                $('#scorecardTable tbody').append($newRow);
            }
            
            // Initialize the new row
            initNewRow($newRow);
            
            console.log('New goal row added to KRA:', kraId, 'Total goals for this KRA:', goalsByKRA[kraId]);
        }

        // Initialize new row
        function initNewRow($row) {
            $row.find('input, select').prop('disabled', false);
            $row.find('.save-goal-btn').show();
            $row.find('.edit-goal-btn').hide();
            $row.find('.update-goal-btn').hide();
            $row.find('.remove-goal-btn').show();
        }

        // Remove goal from KRA with proper rowspan management
        function removeGoalFromKRA(goalId, $row, kraId) {
            if (!goalId) {
                // Unsaved row - just remove it and update rowspan
                Swal.fire({
                    title: 'Remove Row?',
                    text: "Are you sure you want to remove this unsaved row?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update KRA tracking
                        if (kraId && goalsByKRA[kraId]) {
                            goalsByKRA[kraId]--;
                            
                            // Update rowspan
                            const $kraCell = $('#scorecardTable tbody').find(`td.kra-cell[data-kra-id="${kraId}"]`);
                            if ($kraCell.length > 0 && goalsByKRA[kraId] > 0) {
                                $kraCell.attr('rowspan', goalsByKRA[kraId]);
                            } else if (goalsByKRA[kraId] === 0) {
                                // If no more goals for this KRA, remove the KRA tracking
                                delete goalsByKRA[kraId];
                            }
                        }
                        
                        $row.remove();
                        updateTotals();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed!',
                            text: 'Row has been removed.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            } else {
                // Saved row - confirm deletion
                Swal.fire({
                    title: 'Delete Goal?',
                    text: "Are you sure you want to delete this saved goal? This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteGoal(goalId, $row);
                    }
                });
            }
        }
            
        // Populate row with goal data
        function populateRowWithData($row, goal) {
            $row.find('.goal-input').val(goal.goal || '');
            $row.find('.measurement-select').val(goal.measurement || 'Savings');
            $row.find('.weight-input').val(goal.weight || '');
            $row.find('.target-input').val(goal.target || '');
            $row.find('.period-select').val(goal.rating_period || 'Annual');
            $row.find('input[name="jan"]').val(goal.jan_value || '');
            $row.find('input[name="feb"]').val(goal.feb_value || '');
            $row.find('input[name="mar"]').val(goal.mar_value || '');
            $row.find('input[name="apr"]').val(goal.apr_value || '');
            $row.find('input[name="may"]').val(goal.may_value || '');
            $row.find('input[name="jun"]').val(goal.jun_value || '');
            $row.find('input[name="jul"]').val(goal.jul_value || '');
            $row.find('input[name="aug"]').val(goal.aug_value || '');
            $row.find('input[name="sep"]').val(goal.sep_value || '');
            $row.find('input[name="oct"]').val(goal.oct_value || '');
            $row.find('input[name="nov"]').val(goal.nov_value || '');
            $row.find('input[name="dec"]').val(goal.dec_value || '');
            $row.find('.rating-input').val(goal.rating || '');
            $row.find('.evidence-input').val(goal.evidence || '');
        }
            
        // Clear form
        function clearForm() {
            $('#job_title, #department, #reviewer, #reviewer_designation').val('');
            $('#scorecardTable tbody').empty();
            goalsByKRA = {};
            $('#weightTotal').text('0.0%');
            $('#scoreTotal').text('#DIV/0!');
            addInitialRow();
        }
        
        // Add initial empty row
        function addInitialRow() {
            const $row = $('#kraRowTemplate').contents().clone();
            
            // Initialize KRA cell properly
            const $kraCell = $row.find('.kra-cell');
            $kraCell.attr('rowspan', 1);
            
            $('#scorecardTable tbody').append($row);
            initSavedGoals();
        }
        
        // Check if KRA already exists for current employee - MODIFIED: Only for first goal of KRA
        function checkKraExists(kraId, currentRowGoalId = null) {
            let exists = false;
            const employeeId = $('#employee_select').val();
            
            if (!employeeId || !kraId) return false;
            
            // Only check if this is the first goal for this KRA (KRA row)
            // If there are already goals for this KRA, allow adding more
            return goalsByKRA[kraId] && goalsByKRA[kraId] > 0;
        }
        
        // Delete goal function
        function deleteGoal(goalId, $row) {
            $.ajax({
                url: '/scorecard/deleteGoal',
                type: 'POST',
                data: { goal_id: goalId },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Goal has been deleted successfully.',
                            showConfirmButton: false,
                            timer: 1500,
                            willClose: () => {
                                // Reload goals to refresh the table structure
                                const employeeId = $('#employee_select').val();
                                if (employeeId) {
                                    loadExistingGoals(employeeId);
                                }
                            }
                        });
                    } else {
                        Swal.fire('Error', res.message || 'Failed to delete goal', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete Error:', xhr.responseText);
                    Swal.fire('Error', 'There was a problem connecting to the server. Please try again.', 'error');
                }
            });
        }

        function saveGoalData($row) {
            let isKraRow = $row.hasClass('kra-row') || $row.find('.kra-select').length > 0;
            let kraId;
            
            if (isKraRow) {
                kraId = $row.find('.kra-select').val();
                
                // Set KRA cell attributes for new KRA row
                const $kraCell = $row.find('.kra-cell');
                if ($kraCell.length > 0) {
                    $kraCell.attr('data-kra-id', kraId);
                    // Initialize or update goal count
                    if (!goalsByKRA[kraId]) {
                        goalsByKRA[kraId] = 1;
                    }
                    $kraCell.attr('rowspan', goalsByKRA[kraId]);
                }
                
                // Set row's KRA ID
                $row.attr('data-kra-id', kraId);
            } else {
                kraId = $row.data('kra-id');
            }
            
            let employeeId = $('#employee_select').val();
            
            if (!employeeId) {
                Swal.fire('Error', 'Please select an employee first', 'error');
                return;
            }
            
            if (!kraId) {
                Swal.fire('Error', 'KRA is required', 'error');
                return;
            }
            
            const jobtitle = $('#job_title').val() || null;
            const department = $('#department').val() || null;
            const reviewer = $('#reviewer').val() || null;
            const reviewerDesignation = $('#reviewer_designation').val() || null;
            
            let data = {
                employee_id: employeeId,
                evaluation_period: $('#evaluation_period').val(),
                kra_id: kraId,
                job_title: jobtitle,
                department: department,
                reviewer: reviewer,
                reviewer_designation: reviewerDesignation,
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
            
            console.log('Saving goal data:', data);
            
            $.ajax({
                url: '/scorecard/saveGoal',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                    console.log('Save response:', res);
                    
                    if (res.status === 'success') {
                        lastSavedKraId = kraId;
                        
                        // Update the current row instead of reloading everything
                        $row.attr('data-goal-id', res.goal_id);
                        $row.find('input, select').prop('disabled', true);
                        $row.find('.save-goal-btn').hide();
                        $row.find('.edit-goal-btn').show();
                        $row.find('.update-goal-btn').hide();
                        $row.find('.remove-goal-btn').show();
                        
                        // Update totals after saving
                        updateTotals();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Your goal has been saved successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
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

        function updateGoalData($row) {
            let goalId = $row.attr('data-goal-id');
            
            if (!goalId) {
                Swal.fire('Error', 'Cannot update: Goal ID not found', 'error');
                return;
            }
            
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
            
            console.log('Updating goal data:', data);
            
            $.ajax({
                url: '/scorecard/updateGoal',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                    console.log('Update response:', res);
                    
                    if (res.status === 'success') {
                        // Update the current row instead of reloading everything
                        $row.find('input, select').prop('disabled', true);
                        $row.find('.save-goal-btn').hide();
                        $row.find('.edit-goal-btn').show();
                        $row.find('.update-goal-btn').hide();
                        $row.find('.remove-goal-btn').show();
                        
                        // Update totals after updating
                        updateTotals();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Your goal has been updated successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
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

        function initSavedGoals() {
            console.log('Initializing saved goals...');
            
            $('#scorecardTable tbody tr').each(function() {
                const $row = $(this);
                const goalId = $row.attr('data-goal-id');
                
                console.log('Row goal ID:', goalId);
                
                if (goalId && goalId !== '') {
                    // For saved goals, disable form inputs but keep action buttons enabled
                    $row.find('input, select').prop('disabled', true);
                    // Re-enable specific buttons
                    $row.find('.edit-goal-btn, .remove-goal-btn, .add-goal-btn').prop('disabled', false);
                    
                    $row.find('.save-goal-btn').hide();
                    $row.find('.edit-goal-btn').show();
                    $row.find('.update-goal-btn').hide();
                    $row.find('.remove-goal-btn').show();
                    console.log('Set as saved goal');
                } else {
                    // For unsaved goals, enable everything
                    $row.find('input, select').prop('disabled', false);
                    $row.find('.save-goal-btn').show();
                    $row.find('.edit-goal-btn').hide();
                    $row.find('.update-goal-btn').hide();
                    $row.find('.remove-goal-btn').show();
                    console.log('Set as unsaved goal');
                }
            });
        }

        // NEW FUNCTION: Update totals calculation
        function updateTotals() {
            const employeeId = $('#employee_select').val();
            if (!employeeId) {
                $('#weightTotal').text('0.0%');
                $('#scoreTotal').text('#DIV/0!');
                return;
            }
            
            $.ajax({
                url: '/scorecard/getTotalCalculations',
                type: 'POST',
                data: { 
                    employee_id: employeeId,
                    evaluation_period: $('#evaluation_period').val() || '2025'
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        const totalWeight = parseFloat(res.data.grand_total_weight) || 0;
                        const totalScore = parseFloat(res.data.grand_total_score) || 0;
                        
                        // Update the footer totals
                        $('#weightTotal').text(totalWeight.toFixed(1) + '%');
                        
                        if (totalWeight > 0) {
                            const percentage = ((totalScore / totalWeight) * 100);
                            $('#scoreTotal').text(percentage.toFixed(1) + '%');
                        } else {
                            $('#scoreTotal').text('#DIV/0!');
                        }
                    } else {
                        $('#scoreTotal').text('Error');
                    }
                },
                error: function() {
                    console.error('Failed to load total calculations');
                    $('#scoreTotal').text('Error');
                }
            });
        }

        function loadCalculations(perspective = 'financial') {
            const employeeId = $('#employee_select').val();
            if (!employeeId) return;
            
            $.ajax({
                url: '/scorecard/getCalculations',
                type: 'POST',
                data: { 
                    employee_id: employeeId,
                    evaluation_period: $('#evaluation_period').val() || '2025',
                    perspective: perspective
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        updateCalculationDisplay(res.data, perspective);
                    }
                },
                error: function() {
                    console.error('Failed to load calculations');
                }
            });
        }
        
        function updateCalculationDisplay(data, perspective) {
            const totalWeight = parseFloat(data.total_weight) || 0;
            const totalScore = parseFloat(data.total_score) || 0;
            
            // Display total weight - equivalent to =SUM(E69:E71)
            $(`.total-weight-${perspective}`).text(totalWeight.toFixed(1) + '%');
            
            // Display total score - equivalent to =SUM(V69:V71)
            $(`.total-score-${perspective}`).text(totalScore.toFixed(2));
            
            // Calculate percentage if weight > 0
            const percentage = totalWeight > 0 ? ((totalScore / totalWeight) * 100) : 0;
            $(`.percentage-${perspective}`).text(percentage.toFixed(1) + '%');
        }

        function calculateGoalScore(rating, period, weight) {
            if (!rating || !weight) return 0;
            
            let divisor = 1; // Annual
            switch(period) {
                case 'Semi Annual': divisor = 2; break;
                case 'Quarterly': divisor = 4; break;
                case 'Monthly': divisor = 12; break;
            }
            
            return (parseFloat(rating) / divisor) * parseFloat(weight);
        }

        function refreshCalculations() {
            const perspectives = ['financial', 'customer', 'internal', 'learning'];
            perspectives.forEach(perspective => {
                loadCalculations(perspective);
            });
        }

        // EVENT HANDLERS
        
        // Add goal button click handler - SIMPLIFIED VALIDATION
        $(document).on('click', '.add-goal-btn', function() {
            const $btn = $(this);
            const $row = $btn.closest('tr');
            let kraId = $row.attr('data-kra-id');
            
            // If this is a KRA row, get KRA ID from select
            if ($row.hasClass('kra-row') || $row.find('.kra-select').length > 0) {
                kraId = $row.find('.kra-select').val();
                
                if (!kraId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'KRA Required',
                        text: 'Please select a KRA first'
                    });
                    return;
                }
                
                // Only check if current row is saved for KRA rows
                const currentGoalId = $row.attr('data-goal-id');
                if (!currentGoalId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Save Current Row First',
                        text: 'Please save the current row before adding a new goal.'
                    });
                    return;
                }
            } else if (!kraId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select a KRA first'
                });
                return;
            }
            
            // Proceed to add goal to the same KRA
            console.log('Add goal button clicked for KRA:', kraId);
            addGoalToKRA(kraId);
        });

        // Remove goal button handler
        $(document).on('click', '.remove-goal-btn', function() {
            let $btn = $(this);
            let $row = $btn.closest('tr');
            let goalId = $row.attr('data-goal-id');
            let kraId = $row.attr('data-kra-id');
            
            removeGoalFromKRA(goalId, $row, kraId);
        });
            
        // Save goal button handler - REMOVED KRA DUPLICATE CHECK
        $(document).on('click', '.save-goal-btn', function() {
            let $btn = $(this);
            let $row = $btn.closest('tr');
            
            // Validate KRA selection
            let kraId = $row.hasClass('kra-row') ? $row.find('.kra-select').val() : $row.attr('data-kra-id');
            
            if (!kraId) {
                Swal.fire('Error', 'Please select a KRA first', 'error');
                return;
            }
            
            // REMOVED: Check if KRA already exists - now allows multiple goals per KRA
            
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
        
        // Edit goal button handler
        $(document).on('click', '.edit-goal-btn', function() {
            let $btn = $(this);
            let $row = $btn.closest('tr');
            
            $row.find('input, select').prop('disabled', false);
            $btn.hide();
            $row.find('.update-goal-btn').show();
            $row.find('.save-goal-btn').hide();
            $row.find('.remove-goal-btn').show();
        });
        
        // Update goal button handler
        $(document).on('click', '.update-goal-btn', function() {
            let $btn = $(this);
            let $row = $btn.closest('tr');
            
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

        // Event handlers for perspective changes
        $(document).on('change', '.perspective-select', function() {
            const perspective = $(this).val();
            loadCalculations(perspective);
        });

        // Auto-calculate on rating/weight changes
        $(document).on('input', '.rating-input, .weight-input, .period-select', function() {
            const $row = $(this).closest('tr');
            const rating = $row.find('.rating-input').val();
            const period = $row.find('.period-select').val();
            const weight = $row.find('.weight-input').val();
            
            if (rating && weight && period) {
                const score = calculateGoalScore(rating, period, weight);
                $row.find('.calculated-score').text(score.toFixed(2));
            }
            
            // Update totals when values change
            setTimeout(updateTotals, 300);
        });

        // Initialize on page load
        if ($('#scorecardTable tbody tr').length === 0) {
            addInitialRow();
        } else {
            initSavedGoals();
        }
    });
// end financial save button 
