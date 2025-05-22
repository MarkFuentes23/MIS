<link rel="stylesheet" href="/public/css/formEmployee.css?v=<?php echo time(); ?>">

<div class="container-fluid mt-3">
        <div class="table-responsive kra-table">
            <table class="table table-bordered table-sm" id="scorecardTable">
                <thead>
                    <tr class="table-yellow text-center">
                        <th colspan="22" style="text-align: center; vertical-align: middle; background-color: #FFEC19; color: #000; position: relative;">
                            FINANCIAL (10%) – goals that contribute to the company's profitability
                            <div class="action-btns" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                <button id="addKRABtn" class="btn btn-sm btn-primary" style="font-size: 9px; padding: 2px 5px;">
                                    <i class="fas fa-plus"></i> Add New KRA
                                </button>
                                <button id="removeKRABtn" class="btn btn-sm btn-danger ms-2"style="font-size: 9px; padding: 2px 5px;">
                                    <i class="fas fa-trash"></i> Remove KRA
                                </button>
                            </div>
                        </th>
                    </tr>
                    <tr class="table-light-green text-center align-middle">
                        <th class="text-center">KRA</th>
                        <th class="text-center">Goal</th>
                        <th class="text-center">Measurement</th>
                        <th class="text-center">Weight %</th>
                        <th class="text-center">Target</th>
                        <th class="text-center">Rating Period</th>
                        <th class="month-cell">Jan</th>
                        <th class="month-cell">Feb</th>
                        <th class="month-cell">Mar</th>
                        <th class="month-cell">Apr</th>
                        <th class="month-cell">May</th>
                        <th class="month-cell">Jun</th>
                        <th class="month-cell">Jul</th>
                        <th class="month-cell">Aug</th>
                        <th class="month-cell">Sep</th>
                        <th class="month-cell">Oct</th>
                        <th class="month-cell">Nov</th>
                        <th class="month-cell">Dec</th>
                        <th class="text-center">Rating 1-12</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Proof / Evidence</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="goalBody">

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Sub Total</td>
                        <td id="weightTotal">0.0%</td>
                        <td colspan="15"></td>
                        <td id="scoreTotal" class="table-yellow text-center">#DIV/0!</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


        <template id="kraRowTemplate">
            <tr data-kra-id="" class="kra-row" data-goal-id="">
                <td rowspan="1" class="align-middle kra-cell" data-kra-id="">
                    <select name="kra" class="form-select form-select-sm kra-select">
                        <option value="">Select KRA</option>
                        <?php if(isset($kras) && !empty($kras)): ?>
                            <?php foreach ($kras as $kra): ?>
                                <option value="<?php echo $kra['id']; ?>"><?php echo $kra['kra']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td><input type="text" name="goal" class="form-control form-control-sm goal-input" placeholder="Enter goal"></td>
                <td>
                    <select name="measurement" class="form-select form-select-sm measurement-select">
                        <option value="Savings">Savings</option>
                        <option value="Revenue">Revenue</option>
                        <option value="Percentage">Percentage</option>
                    </select>
                </td>
                <td><input type="number" name="weight" step="0.1" min="0" class="form-control form-control-sm weight-input" placeholder="0.0"></td>
                <td><input type="text" name="target" class="form-control form-control-sm target-input" placeholder="Enter target"></td>
                <td>
                    <select name="period" class="form-select form-select-sm period-select" style="width: 80px;">
                        <option value="Annual">Annual</option>
                        <option value="Semi Annual">Semi Annual</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                </td>
                <td class="month-cell"><input type="text" name="jan" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="feb" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="mar" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="apr" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="may" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="jun" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="jul" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="aug" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="sep" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="oct" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="nov" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="dec" class="form-control form-control-sm month-input"></td>
                <td><input type="number" name="rating" class="form-control form-control-sm rating-input" min="1" max="12"></td>
                <td class="text-center score-cell"><span class="badge bg-danger score-value">#DIV/0!</span></td>
                <td><input type="text" name="evidence" class="form-control form-control-sm evidence-input" placeholder="Proof/Evidence"></td>
                <td class="action-cell text-center">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-sm btn-success add-goal-btn" style="padding: 2px 5px; font-size:8px;">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger remove-goal-btn" style="padding: 2px 5px; font-size: 8px;">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary save-goal-btn" style="padding:2px 5px;font-size:8px;" title="Save Goal">
                            Save
                        </button>
                        <button type="button" class="btn btn-sm btn-warning edit-goal-btn" style="padding:2px 5px;font-size:8px; display:none;" title="Edit Goal">
                            Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-success update-goal-btn" style="padding:2px 5px;font-size:8px; display:none;" title="Update Goal">
                            Update
                        </button>
                    </div>
                </td>
            </tr>
        </template>

        <!-- Goal Row Template (Hidden) - For additional goals under same KRA -->
        <template id="goalRowTemplate">
            <tr data-kra-id="" class="goal-row" data-goal-id="">
                <td><input type="text" name="goal" class="form-control form-control-sm goal-input" placeholder="Enter goal"></td>
                <td>
                    <select name="measurement" class="form-select form-select-sm measurement-select">
                        <option value="Savings">Savings</option>
                        <option value="Revenue">Revenue</option>
                        <option value="Percentage">Percentage</option>
                    </select>
                </td>
                <td><input type="number" name="weight" step="0.1" min="0" class="form-control form-control-sm weight-input" placeholder="0.0"></td>
                <td><input type="text" name="target" class="form-control form-control-sm target-input" placeholder="Enter target"></td>
                <td>
                    <select name="period" class="form-select form-select-sm period-select" style="width: 80px;">
                        <option value="Annual">Annual</option>
                        <option value="Semi Annual">Semi Annual</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                </td>
                <td class="month-cell"><input type="text" name="jan" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="feb" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="mar" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="apr" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="may" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="jun" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="jul" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="aug" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="sep" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="oct" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="nov" class="form-control form-control-sm month-input"></td>
                <td class="month-cell"><input type="text" name="dec" class="form-control form-control-sm month-input"></td>
                <td><input type="number" name="rating" class="form-control form-control-sm rating-input" min="1" max="12"></td>
                <td class="text-center score-cell"><span class="badge bg-danger score-value">#DIV/0!</span></td>
                <td><input type="text" name="evidence" class="form-control form-control-sm evidence-input" placeholder="Proof/Evidence"></td>
                <td class="action-cell text-center">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-sm btn-success add-goal-btn" style="padding: 2px 5px; font-size:8px;">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger remove-goal-btn" style="padding: 2px 5px; font-size: 8px;">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary save-goal-btn" style="padding:2px 5px;font-size:8px;" title="Save Goal">
                            Save
                        </button>
                        <button type="button" class="btn btn-sm btn-warning edit-goal-btn" style="padding:2px 5px;font-size:8px; display:none;" title="Edit Goal">
                            Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-success update-goal-btn" style="padding:2px 5px;font-size:8px; display:none;" title="Update Goal">
                            Update
                        </button>
                    </div>
                </td>
            </tr>
        </template>




