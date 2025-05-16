<!-- operational -->
<div class="container-fluid">
    <div class="table-responsive kra-table">
        <table class="table table-bordered table-sm">
            <thead>
                <tr class="table-yellow text-center">
                    <th colspan="22" style="text-align: center; vertical-align: middle; background-color: #FFEC19; color: #000; position: relative;">
                       OPERATIONAL (70%) - goals that bring the organization to the higher level of performance or state
                       <div class="action-btns" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                            <button id="addKRABtnOperational" class="btn btn-sm btn-primary" style="font-size: 9px; padding: 2px 5px;">
                                <i class="fas fa-plus"></i> Add New KRA
                            </button>
                            <button id="removeKRABtnOperational" class="btn btn-sm btn-danger ms-2"style="font-size: 9px; padding: 2px 5px;">
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
            <tbody id="goalBodyOperational">
                <!-- Template for KRA row - This will be used by JavaScript to create new rows -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Sub Total</td>
                    <td id="weightTotalOperational">0.0%</td>
                    <td colspan="15"></td>
                    <td id="scoreTotalOperational" class="table-yellow text-center">#DIV/0!</td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- KRA Row Template (Hidden) -->
<template id="kraRowTemplateOperational">
     <tr data-kra-id="" class="kra-row">
            <td rowspan="1" class="align-middle kra-cell" data-kra-id="">
             <select name="kra" class="form-select form-select-sm">
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
            <select name="measurement" class="form-select form-select-sm">
                <option value="Savings">Savings</option>
                <option value="Revenue">Revenue</option>
                <option value="Percentage">Percentage</option>
            </select>
        </td>
        <td><input type="number" name="weight" step="0.1" min="0" class="form-control form-control-sm weight-input" placeholder="0.0"></td>
        <td><input type="text" name="target" class="form-control form-control-sm" placeholder="Enter target"></td>
        <td>
             <select name="period" class="form-select form-select-sm" style="width: 80px;">
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
        <td><input type="text" name="evidence" class="form-control form-control-sm" placeholder="Proof/Evidence"></td>
        <td class="action-cell text-center">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-success add-goal-btn" style="padding: 2px 5px; font-size:8px;">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger remove-goal-btn" style="padding: 2px 5px; font-size: 8px;">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </td>
    </tr>
</template>

<!-- Goal Row Template (Hidden) - For additional goals under same KRA -->
<template id="goalRowTemplateOperational">
    <tr data-kra-id="" class="goal-row">
        <td><input type="text" name="goal" class="form-control form-control-sm goal-input" placeholder="Enter goal"></td>
        <td>
            <select name="measurement" class="form-select form-select-sm">
                <option value="Savings">Savings</option>
                <option value="Revenue">Revenue</option>
                <option value="Percentage">Percentage</option>
            </select>
        </td>
        <td><input type="number" name="weight" step="0.1" min="0" class="form-control form-control-sm weight-input" placeholder="0.0"></td>
        <td><input type="text" name="target" class="form-control form-control-sm" placeholder="Enter target"></td>
        <td>
             <select name="period" class="form-select form-select-sm" style="width: 80px;">
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
        <td><input type="text" name="evidence" class="form-control form-control-sm" placeholder="Proof/Evidence"></td>
        <td class="action-cell text-center">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-success add-goal-btn" style="padding: 2px 5px; font-size:8px;">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger remove-goal-btn" style="padding: 2px 5px; font-size: 8px;">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </td>
    </tr>
</template>
<!-- end operational -->



