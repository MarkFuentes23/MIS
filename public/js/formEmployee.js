
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
            
            // Update total weight calculation
            function updateWeight() {
                let sum = 0;
                body.querySelectorAll('.weight-input').forEach(i => sum += parseFloat(i.value) || 0);
                wtCell.textContent = sum.toFixed(1) + '%';
                
                // Disable add buttons if weight >= 100%
                updateAddButtonsState(sum);
            }
            
            // Update add buttons state based on total weight
            function updateAddButtonsState(totalWeight) {
                const maxWeight = 100; // Maximum allowed weight is 100%
                const addButtons = body.querySelectorAll('.add-goal-btn');
                
                // Disable/enable add buttons based on weight
                if (totalWeight >= maxWeight) {
                    addButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.title = "Maximum weight (100%) reached";
                    });
                } else {
                    addButtons.forEach(btn => {
                        btn.disabled = false;
                        btn.title = "Add goal";
                    });
                }
            }
            
            // Calculate score based on formula
            function calculateScore(row) {
                const periodEl = row.querySelector('select[name="period"]');
                const weightEl = row.querySelector('input[name="weight"]');
                const ratingEl = row.querySelector('input[name="rating"]');
                const scoreEl = row.querySelector('.score-value');
                
                if (!periodEl || !weightEl || !ratingEl || !scoreEl) return;
                
                const period = periodEl.value;
                const weight = parseFloat(weightEl.value) || 0;
                const rating = parseFloat(ratingEl.value) || 0;
                
                // Calculate divisor based on period
                let divisor = 1; // Default for Annual
                if (period === "Semi Annual") divisor = 2;
                else if (period === "Quarterly") divisor = 4;
                else if (period === "Monthly") divisor = 12;
                
                // Calculate score using formula: (Rating/Divisor) * Weight
                if (rating > 0 && weight > 0) {
                    const score = (rating/divisor) * weight;
                    scoreEl.textContent = score.toFixed(2);
                    scoreEl.classList.remove('bg-danger');
                    scoreEl.classList.add('bg-success');
                } else {
                    scoreEl.textContent = "#DIV/0!";
                    scoreEl.classList.remove('bg-success');
                    scoreEl.classList.add('bg-danger');
                }
                
                // Update total score
                updateTotalScore();
            }
            
            // Update total score
            function updateTotalScore() {
                let totalWeight = 0;
                let totalScore = 0;
                
                body.querySelectorAll('.score-value').forEach(function(scoreEl) {
                    const row = scoreEl.closest('tr');
                    const weightEl = row.querySelector('input[name="weight"]');
                    
                    if (weightEl) {
                        const weight = parseFloat(weightEl.value) || 0;
                        totalWeight += weight;
                        
                        if (scoreEl.textContent !== "#DIV/0!") {
                            const score = parseFloat(scoreEl.textContent) || 0;
                            totalScore += score;
                        }
                    }
                });
                
                // Set total score
                if (totalWeight > 0) {
                    scoreTotal.textContent = totalScore.toFixed(2);
                    scoreTotal.classList.remove('bg-danger');
                } else {
                    scoreTotal.textContent = "#DIV/0!";
                    scoreTotal.classList.add('bg-danger');
                }
            }
            
            // Create a new KRA section
            function addKRASection() {
                // Check current weight first
                let currentWeight = parseFloat(wtCell.textContent) || 0;
                if (currentWeight >= 100) {
                    alert('Cannot add new KRA. Maximum weight (100%) has been reached.');
                    return;
                }
                
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
            
            // Add a goal row to a specific KRA
            function addGoalRow(kraId) {
                // Check current weight first
                let currentWeight = parseFloat(wtCell.textContent) || 0;
                if (currentWeight >= 100) {
                    alert('Cannot add new goal. Maximum weight (100%) has been reached.');
                    return;
                }

                // Increment goal count for this KRA
                goalsByKRA[kraId]++;

                // Find KRA cell
                const kraCell = body.querySelector(`td.kra-cell[data-kra-id="${kraId}"]`);
                if (kraCell) {
                    kraCell.rowSpan = goalsByKRA[kraId];
                }

                // Clone the goal row template
                const tr = goalRowTemplate.content.cloneNode(true).querySelector('tr');
                tr.dataset.kraId = kraId;

                // Add event listeners
                setupRowEventListeners(tr, kraId);

                // FIXED: Always append to the end of the table
                body.appendChild(tr);

                // Update weight total
                updateWeight();
            }
            
            // Setup event listeners for a row
            function setupRowEventListeners(row, kraId) {
                // Weight input event
                const weightInput = row.querySelector('.weight-input');
                if (weightInput) {
                    weightInput.addEventListener('input', function() {
                        updateWeight();
                        calculateScore(row);
                    });
                }
                
                // Rating input event
                const ratingInput = row.querySelector('.rating-input');
                if (ratingInput) {
                    ratingInput.addEventListener('input', function() {
                        calculateScore(row);
                    });
                }
                
                // Period select event
                const periodSelect = row.querySelector('select[name="period"]');
                if (periodSelect) {
                    periodSelect.addEventListener('change', function() {
                        calculateScore(row);
                    });
                }
                
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
                
                // Month input events for score calculation
                const monthInputs = row.querySelectorAll('.month-input');
                monthInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        calculateScore(row);
                    });
                });
                
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
                    
                    // Update totals
                    updateWeight();
                    updateTotalScore();
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
    
    // Load existing data when employee is selected
    $('#employee_select').on('change', function() {
        const employeeId = $(this).val();
        if (employeeId) {
            loadEmployeeData(employeeId);
            loadExistingGoals(employeeId);
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
        // Clear table first
        $('#scorecardTable tbody').empty();
        
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
                        
                        goals.forEach((goal, index) => {
                            let $row;
                            
                            if (index === 0) {
                                $row = $('#kraRowTemplate').contents().clone();
                                $row.find('.kra-select').val(goal.kra_id);
                                // Set KRA cell rowspan based on number of goals
                                $row.find('.kra-cell').attr('rowspan', goals.length);
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
        
        updateScore($row);
    }
    
    // Clear form
    function clearForm() {
        $('#job_title, #department, #reviewer, #reviewer_designation').val('');
        $('#scorecardTable tbody').empty();
        addInitialRow();
    }
    
    // Add initial empty row
    function addInitialRow() {
        const $row = $('#kraRowTemplate').contents().clone();
        $('#scorecardTable tbody').append($row);
        initSavedGoals();
    }
    
    // Check if KRA already exists for current employee
    function checkKraExists(kraId, currentRowGoalId = null) {
        let exists = false;
        const employeeId = $('#employee_select').val();
        
        if (!employeeId || !kraId) return false;
        
        // Check in existing rows
        $('#scorecardTable tbody tr').each(function() {
            const $row = $(this);
            const rowGoalId = $row.attr('data-goal-id');
            const rowKraId = $row.hasClass('kra-row') ? $row.find('.kra-select').val() : $row.attr('data-kra-id');
            
            // Skip checking the current row being edited
            if (currentRowGoalId && rowGoalId === currentRowGoalId) {
                return true; // continue
            }
            
            if (rowKraId === kraId && rowGoalId) {
                exists = true;
                return false; // break
            }
        });
        
        return exists;
    }
    
    // Remove goal button handler
    $(document).on('click', '.remove-goal-btn', function() {
        let $btn = $(this);
        let $row = $btn.closest('tr');
        let goalId = $row.attr('data-goal-id');
        
        if (!goalId) {
            // Unsaved row - just remove it
            Swal.fire({
                title: 'Remove Row?',
                text: "Are you sure you want to remove this unsaved row?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $row.remove();
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
    });
    
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
    
    // Save goal button handler
    $(document).on('click', '.save-goal-btn', function() {
        let $btn = $(this);
        let $row = $btn.closest('tr');
        
        // Validate KRA selection
        let kraId = $row.hasClass('kra-row') ? $row.find('.kra-select').val() : $row.attr('data-kra-id');
        
        if (!kraId) {
            Swal.fire('Error', 'Please select a KRA first', 'error');
            return;
        }
        
        // Check if KRA already exists
        if (checkKraExists(kraId)) {
            Swal.fire({
                title: 'KRA Already Exists',
                text: 'This KRA already exists for the selected employee. Please add goals to the existing KRA or select a different KRA.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
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
    
    function saveGoalData($row) {
        let isKraRow = $row.hasClass('kra-row');
        let kraId;
        
        if (isKraRow) {
            kraId = $row.find('.kra-select').val();
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
                    
                    // Update the score display
                    updateScore($row);
                    
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
    
    function updateScore($row) {
        const weight = parseFloat($row.find('.weight-input').val()) || 0;
        const rating = parseFloat($row.find('.rating-input').val()) || 0;
        const period = $row.find('.period-select').val();
        
        let divisor = 1;
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

    function initSavedGoals() {
        console.log('Initializing saved goals...');
        
        $('#scorecardTable tbody tr').each(function() {
            const $row = $(this);
            const goalId = $row.attr('data-goal-id');
            
            console.log('Row goal ID:', goalId);
            
            if (goalId && goalId !== '') {
                $row.find('input, select').prop('disabled', true);
                $row.find('.save-goal-btn').hide();
                $row.find('.edit-goal-btn').show();
                $row.find('.update-goal-btn').hide();
                $row.find('.remove-goal-btn').show(); // Show delete button for saved goals
                console.log('Set as saved goal');
            } else {
                $row.find('input, select').prop('disabled', false);
                $row.find('.save-goal-btn').show();
                $row.find('.edit-goal-btn').hide();
                $row.find('.update-goal-btn').hide();
                $row.find('.remove-goal-btn').show();
                console.log('Set as unsaved goal');
            }
            
            updateScore($row);
        });
    }
    
    // Add goal button handler
    $(document).on('click', '.add-goal-btn', function() {
        const $currentRow = $(this).closest('tr');
        const isKraRow = $currentRow.hasClass('kra-row');
        
        if (isKraRow) {
            // Adding a new KRA row
            const $newRow = $('#kraRowTemplate').contents().clone();
            $newRow.find('.save-goal-btn').show();
            $newRow.find('.edit-goal-btn').hide();
            $newRow.find('.update-goal-btn').hide();
            $newRow.find('.remove-goal-btn').show();
            
            // Insert after the current row
            $currentRow.after($newRow);
        } else {
            // Adding a goal to existing KRA
            const kraId = $currentRow.attr('data-kra-id') || $currentRow.find('.kra-select').val();
            
            if (!kraId) {
                Swal.fire('Error', 'Please select a KRA first', 'error');
                return;
            }
            
            const $newRow = $('#goalRowTemplate').contents().clone();
            $newRow.attr('data-kra-id', kraId);
            $newRow.find('.save-goal-btn').show();
            $newRow.find('.edit-goal-btn').hide();
            $newRow.find('.update-goal-btn').hide();
            $newRow.find('.remove-goal-btn').show();
            
            // Insert after the current row
            $currentRow.after($newRow);
        }
    });
    
    // Score calculation on input change
    $(document).on('input', '.weight-input, .rating-input', function() {
        const $row = $(this).closest('tr');
        updateScore($row);
    });
    
    $(document).on('change', '.period-select', function() {
        const $row = $(this).closest('tr');
        updateScore($row);
    });
    
    // Initialize on page load
    if ($('#scorecardTable tbody tr').length === 0) {
        addInitialRow();
    } else {
        initSavedGoals();
    }
    });
    // end financial save button 

