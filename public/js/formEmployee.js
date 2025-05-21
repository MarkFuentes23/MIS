
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
            
            // Update add buttons state based on total weight and database weight
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
                    // Check if any row has a weight of 10% or more in the database
                    body.querySelectorAll('tr').forEach(row => {
                        const weightInput = row.querySelector('.weight-input');
                        const savedWeightAttr = row.getAttribute('data-saved-weight');
                        
                        if (savedWeightAttr && parseFloat(savedWeightAttr) >= 10) {
                            // Disable add buttons for this KRA
                            const kraId = row.dataset.kraId;
                            if (kraId) {
                                body.querySelectorAll(`tr[data-kra-id="${kraId}"] .add-goal-btn`).forEach(btn => {
                                    btn.disabled = true;
                                    btn.title = "Cannot add goal - weight limit of 10% reached in database";
                                });
                            }
                        } else {
                            // Enable if not disabled by total weight check
                            const kraId = row.dataset.kraId;
                            if (kraId && totalWeight < maxWeight) {
                                body.querySelectorAll(`tr[data-kra-id="${kraId}"] .add-goal-btn`).forEach(btn => {
                                    if (!isKraWeightLimitReached(kraId)) {
                                        btn.disabled = false;
                                        btn.title = "Add goal";
                                    }
                                });
                            }
                        }
                    });
                }
            }
            
            // Check if KRA has reached the 10% weight limit in database
            function isKraWeightLimitReached(kraId) {
                let isLimitReached = false;
                body.querySelectorAll(`tr[data-kra-id="${kraId}"]`).forEach(row => {
                    const savedWeightAttr = row.getAttribute('data-saved-weight');
                    if (savedWeightAttr && parseFloat(savedWeightAttr) >= 10) {
                        isLimitReached = true;
                    }
                });
                return isLimitReached;
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

                // Check if KRA has reached 10% weight limit in database
                if (isKraWeightLimitReached(kraId)) {
                alert('Cannot add new goal. This KRA has reached the 10% weight limit in the database.');
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
            
            // Remove a goal row from a specific KRA
            function removeGoalRow(kraId) {
                // Find all rows for this KRA
                const rows = Array.from(body.querySelectorAll(`tr[data-kra-id="${kraId}"]`));
                
                if (rows.length <= 1) {
                    // If last goal, don't remove it
                    return;
                }
                
                // Get the last goal row (fixed)
                const lastGoalRow = rows[rows.length - 1];
                
                if (lastGoalRow) {
                    // Check if this is a KRA row
                    const isKraRow = lastGoalRow.querySelector('.kra-cell') !== null;
                    
                    // If it's a KRA row and not the only row, don't remove it
                    if (isKraRow && rows.length > 1) {
                        alert('Cannot remove KRA row. Remove goal rows first.');
                        return;
                    }
                    
                    // Remove the row
                    lastGoalRow.remove();
                    
                    // Decrement goal count
                    goalsByKRA[kraId]--;
                    
                    // Update KRA cell rowspan
                    const kraCell = body.querySelector(`td.kra-cell[data-kra-id="${kraId}"]`);
                    if (kraCell) {
                        kraCell.rowSpan = goalsByKRA[kraId];
                    }
                    
                    // Update weight total
                    updateWeight();
                    
                    // Update total score
                    updateTotalScore();
                }
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
                        // When saving to database, store the weight value as data attribute
                        const weightInput = row.querySelector('.weight-input');
                        if (weightInput) {
                            const weight = parseFloat(weightInput.value) || 0;
                            row.setAttribute('data-saved-weight', weight);
                            
                            // Check if weight is 10% or more
                            if (weight >= 10) {
                                // Disable add buttons for this KRA
                                body.querySelectorAll(`tr[data-kra-id="${kraId}"] .add-goal-btn`).forEach(btn => {
                                    btn.disabled = true;
                                    btn.title = "Cannot add goal - weight limit of 10% reached in database";
                                });
                            }
                        }
                        
                        // Your existing save logic here...
                    });
                }
                
                // Update button event
                const updateBtn = row.querySelector('.update-goal-btn');
                if (updateBtn) {
                    updateBtn.addEventListener('click', function() {
                        // When updating in database, update the stored weight value
                        const weightInput = row.querySelector('.weight-input');
                        if (weightInput) {
                            const weight = parseFloat(weightInput.value) || 0;
                            row.setAttribute('data-saved-weight', weight);
                            
                            // Check if weight is 10% or more
                            if (weight >= 10) {
                                // Disable add buttons for this KRA
                                body.querySelectorAll(`tr[data-kra-id="${kraId}"] .add-goal-btn`).forEach(btn => {
                                    btn.disabled = true;
                                    btn.title = "Cannot add goal - weight limit of 10% reached in database";
                                });
                            } else {
                                // Re-check if any row in this KRA still has 10%+ weight
                                if (!isKraWeightLimitReached(kraId)) {
                                    // Enable add buttons if no other row has 10%+ weight
                                    body.querySelectorAll(`tr[data-kra-id="${kraId}"] .add-goal-btn`).forEach(btn => {
                                        btn.disabled = false;
                                        btn.title = "Add goal";
                                    });
                                }
                            }
                        }
                        
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
            
            // Function to load data from database and set data-saved-weight attributes
            function loadDatabaseWeights() {
                // This function should be called after loading data from your database
                // It will set the data-saved-weight attribute on rows based on database values
                
                // Example of how this might work with your actual data loading code:
                /*
                fetchGoalsFromDatabase().then(goals => {
                    goals.forEach(goal => {
                        const row = document.querySelector(`tr[data-goal-id="${goal.id}"]`);
                        if (row) {
                            row.setAttribute('data-saved-weight', goal.weight);
                            
                            // Check if any weight is 10% or more
                            if (parseFloat(goal.weight) >= 10) {
                                const kraId = row.dataset.kraId;
                                if (kraId) {
                                    // Disable add buttons for this KRA
                                    body.querySelectorAll(`tr[data-kra-id="${kraId}"] .add-goal-btn`).forEach(btn => {
                                        btn.disabled = true;
                                        btn.title = "Cannot add goal - weight limit of 10% reached in database";
                                    });
                                }
                            }
                        }
                    });
                });
                */
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
            
            // Load data from database if available
            loadDatabaseWeights();
        }
    });
    // end of scorecard


    // employee auto populate
    document.addEventListener('DOMContentLoaded', function() {
        // Get the employee select dropdown
        const employeeSelect = document.getElementById('employee_select');
        
        // Add change event listener
        employeeSelect.addEventListener('change', function() {
            const employeeId = this.value;
            
            if (employeeId) {
                // Create form data for the AJAX request
                const formData = new FormData();
                formData.append('employee_id', employeeId);
                
                // Send AJAX request to get employee data
                fetch('/scorecard/getEmployeeData', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Fill in the job title and department fields
                        document.getElementById('job_title').value = data.data.job_title || '';
                        document.getElementById('department').value = data.data.department || '';
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching employee data:', error);
                });
            } else {
                // Clear the fields if no employee is selected
                document.getElementById('job_title').value = '';
                document.getElementById('department').value = '';
            }
        });
    });
    //  employee auto populate end





    // financial save button 
    $(document).ready(function() {
        let lastSavedKraId = null; // Store the last saved KRA ID
        
        // Add new remove button functionality
        $(document).on('click', '.remove-goal-btn', function() {
            let $btn = $(this);
            let $row = $btn.closest('tr');
            
            // Only allow removal if row has no goal_id (unsaved)
            if (!$row.attr('data-goal-id')) {
                // Show confirmation dialog
                Swal.fire({
                    title: 'Remove Row?',
                    text: "Are you sure you want to remove this unsaved row?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Remove the row
                        $row.remove();
                        
                        // Show success message
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
                // Row is already saved, show error
                Swal.fire('Cannot Remove', 'This row has already been saved and cannot be removed.', 'error');
            }
        });
        
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
        
        // Function to save goal data (for new goals only)
        function saveGoalData($row) {
            // Determine if it's a KRA row or goal row
            let isKraRow = $row.hasClass('kra-row');
            
            // Get KRA ID properly based on row type
            let kraId;
            if (isKraRow) {
                kraId = $row.find('.kra-select').val();
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
            const positionTitle = $('#job_title').val() || null;
            const department = $('#department').val() || null;
            const reviewer = $('#reviewer').val() || null;
            const reviewerDesignation = $('#reviewer_designation').val() || null;
            
            // Collect form data
            let data = {
                employee_id: employeeId,
                evaluation_period: $('#evaluation_period').val(),
                kra_id: kraId,
                position_title: positionTitle,
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
            
            // Send Ajax request
            $.ajax({
                url: '/scorecard/saveGoal',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        // Store the KRA ID of the saved row
                        lastSavedKraId = kraId;
                        
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
                        
                        // Hide remove button once saved
                        $row.find('.remove-goal-btn').hide();
                        
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
                    $row.find('.remove-goal-btn').hide(); // Hide remove button for saved rows
                    $row.find('input, select').prop('disabled', true);
                } else {
                    // This is a new goal
                    $row.find('.save-goal-btn').show();
                    $row.find('.edit-goal-btn').hide();
                    $row.find('.update-goal-btn').hide();
                    $row.find('.remove-goal-btn').show(); // Show remove button for unsaved rows
                }
            });
        }
        
        // Call init function when page loads
        initSavedGoals();
        
        // When adding new rows, make sure to set the right button visibility
        // and apply the last saved KRA ID
        $(document).on('click', '.add-goal-btn', function() {
            // Add the new row (existing code would handle this)
            // Then we need to modify it to inherit the KRA
            setTimeout(function() {
                $('.kra-row, .goal-row').each(function() {
                    const $row = $(this);
                    if (!$row.attr('data-goal-id')) {
                        // Set button visibility for new row
                        $row.find('.save-goal-btn').show();
                        $row.find('.edit-goal-btn').hide();
                        $row.find('.update-goal-btn').hide();
                        $row.find('.remove-goal-btn').show(); // Show remove button for new rows
                        
                        // Apply the last saved KRA ID if available
                        if (lastSavedKraId) {
                            if ($row.hasClass('kra-row')) {
                                // If it's a KRA row, set the select value
                                $row.find('.kra-select').val(lastSavedKraId);
                            } else {
                                // If it's a goal row, set the data attribute
                                $row.attr('data-kra-id', lastSavedKraId);
                            }
                        }
                    }
                });
            }, 100);
        });
    });
    // end financial save button 