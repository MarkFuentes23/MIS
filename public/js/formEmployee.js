
    $(document).ready(function() {
        // When employee is selected from dropdown
        $('#employee_select').change(function() {
            var employeeId = $(this).val();
            
            if (employeeId) {
                // Get employee data via AJAX
                $.ajax({
                    url: '/form/getEmployeeData',
                    type: 'POST',
                    data: {
                        employee_id: employeeId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var employee = response.data;
                            
                            // Populate the form fields
                            $('#job_title').val(employee.job_title.toUpperCase());
                            $('#department').val(employee.department.toUpperCase());
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error: Failed to fetch employee data');
                    }
                });
            } else {
                // Clear form fields if no employee selected
                $('#job_title').val('');
                $('#department').val('');
            }
        });
    });
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

// financial
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
            // Increment goal count for this KRA
            goalsByKRA[kraId]++;
            
            // Find KRA cell
            const kraCell = body.querySelector(`td.kra-cell[data-kra-id="${kraId}"]`);
            if (kraCell) {
                kraCell.rowSpan = goalsByKRA[kraId];
            }
            
            // Find the last row in this KRA group
            const kraRows = Array.from(body.querySelectorAll(`tr[data-kra-id="${kraId}"]`));
            const lastRow = kraRows[kraRows.length - 1];
            
            // Clone the goal row template
            const tr = goalRowTemplate.content.cloneNode(true).querySelector('tr');
            tr.dataset.kraId = kraId;
            
            // Add event listeners
            setupRowEventListeners(tr, kraId);
            
            // Insert after last row
            if (lastRow.nextSibling) {
                body.insertBefore(tr, lastRow.nextSibling);
            } else {
                body.appendChild(tr);
            }
            
            // Update weight total
            updateWeight();
        }
        
        // Remove a goal row from a specific KRA
        function removeGoalRow(kraId) {
            if (goalsByKRA[kraId] <= 1) {
                // If last goal, don't remove it
                alert('Cannot remove the last goal of a KRA section.');
                return;
            }
            
            // Find all rows for this KRA
            const rows = Array.from(body.querySelectorAll(`tr[data-kra-id="${kraId}"]`));
            
            // Get the last goal row
            const lastGoalRow = rows[rows.length - 1];
            
            if (lastGoalRow) {
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
        }
        
        // Remove an entire KRA section
        function removeKRASection() {
            if (kraCount <= 1) {
                alert('Cannot remove the last KRA section.');
                return;
            }
            
            // Get all KRA IDs
            const kraIds = Object.keys(goalsByKRA);
            
            if (kraIds.length > 0) {
                // Get the last KRA ID
                const lastKraId = kraIds[kraIds.length - 1];
                
                // Remove all rows with this KRA ID
                const rows = Array.from(body.querySelectorAll(`tr[data-kra-id="${lastKraId}"]`));
                rows.forEach(row => row.remove());
                
                // Remove from tracking object
                delete goalsByKRA[lastKraId];
                
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
// end of financial

$(document).ready(function() {
    // Load KRAs for dropdown
    $.ajax({
        url: '<?php echo BASE_URL; ?>form/getKras',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var kraDropdown = $('select[name="kra"]');
                kraDropdown.empty();
                kraDropdown.append('<option value="">Select KRA</option>');
                
                $.each(response.data, function(index, kra) {
                    kraDropdown.append('<option value="' + kra.id + '">' + kra.kra + '</option>');
                });
            } else {
                console.error('Error loading KRAs:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
        }
    });
});
