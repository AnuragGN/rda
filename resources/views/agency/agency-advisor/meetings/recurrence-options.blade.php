<div class="form-group row">
    <div class="col-sm-9 offset-sm-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="repeat" name="repeat">
            <label class="form-check-label" for="repeat">Recurrence</label>
        </div>
    </div>
</div>

<div class="form-group row" id="reccurence_input" style="display: none;">
    <label for="recurring" class="col-sm-3 col-form-label">Frequency:</label>
    <div class="col-sm-9">
        <select class="form-control" name="recurring" id="recurring" onchange="toggleRecurrenceOptions()">
            <option value="DAILY">Daily</option>
            <option value="WEEKLY">Weekly</option>
            <option value="MONTHLY">Monthly</option>
            <option value="YEARLY">Yearly</option>
        </select>
    </div>
</div>

<div id="recurrenceOptions" style="display: none;">
    <!-- Weekly Options -->
    <div class="form-group row" id="weeklyOptions" style="display: none;">
        <label class="col-sm-3 col-form-label">Repeat on:</label>
        <div class="col-sm-9">
            <div class="form-group row">
                <div class="col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="weekdayMO" name="weekday[]" value="MO">
                        <label class="form-check-label" for="weekdayMO">Monday</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="weekdayTU" name="weekday[]" value="TU">
                        <label class="form-check-label" for="weekdayTU">Tuesday</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="weekdayWE" name="weekday[]" value="WE">
                        <label class="form-check-label" for="weekdayWE">Wednesday</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="weekdayTH" name="weekday[]" value="TH">
                        <label class="form-check-label" for="weekdayTH">Thursday</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="weekdayFR" name="weekday[]" value="FR">
                        <label class="form-check-label" for="weekdayFR">Friday</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="weekdaySA" name="weekday[]" value="SA">
                        <label class="form-check-label" for="weekdaySA">Saturday</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="weekdaySU" name="weekday[]" value="SU">
                        <label class="form-check-label" for="weekdaySU">Sunday</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Options -->
    <div class="form-group row" id="monthlyOptions" style="display: none;">
        <label class="col-sm-3 col-form-label">Repeat on:</label>
        <div class="col-sm-3">
            <input type="number" class="form-control" name="dateOfMonth" id="dateOfMonth" min="1" max="31" placeholder="01 to 31">
        </div>
    </div>
</div>

<script>
    function toggleRecurrenceOptions() {
        var recurring = document.getElementById('recurring').value;
        var recurrenceOptions = document.getElementById('recurrenceOptions');
        var weeklyOptions = document.getElementById('weeklyOptions');
        var monthlyOptions = document.getElementById('monthlyOptions');
        
        recurrenceOptions.style.display = recurring ? 'block' : 'none';
        weeklyOptions.style.display = recurring === 'WEEKLY' ? 'flex' : 'none';
        monthlyOptions.style.display = recurring === 'MONTHLY' ? 'flex' : 'none';
    }

    $(document).ready(function() {
        $('#repeat').change(function() {
            if ($(this).is(':checked')) {
                $('#reccurence_input').show();
            } else {
                $('#reccurence_input').hide();
                $('#recurring').val('');
                $('#recurrenceOptions').hide();
                $('#weeklyOptions').hide();
                $('#monthlyOptions').hide();
            }
        });
    });
</script>
