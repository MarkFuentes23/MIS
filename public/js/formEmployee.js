
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


