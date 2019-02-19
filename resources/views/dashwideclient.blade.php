<div class="col-md-10 tablet-desktop-size mx-auto">
    <table class="table table-striped">
        <thead>
            <th class="th-vert current-week"></th>
            <th class="th-top">Monday</th>
            <th class="th-top">Tuesday</th>
            <th class="th-top">Wednesday</th>
            <th class="th-top">Thursday</th>
            <th class="th-top">Friday</th>
            <th class="th-top">Saturday</th>
            <th class="th-top">Sunday</th>
            <th class="th-top">Total</th>
        </thead>
        <tbody>
            <tr>
                <th class="th-vert">(suppliers list)</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th class="th-vert">Stock Purchase (£)</th>
                <td contenteditable="true" id="dsp_mon_w_cell" class="cell-data cell-wide dspmon">{{ $dsp_mon }}</td>
                <td contenteditable="true" id="dsp_tue_w_cell" class="cell-data cell-wide dsptue">{{ $dsp_tue }}</td>
                <td contenteditable="true" id="dsp_wed_w_cell" class="cell-data cell-wide dspwed">{{ $dsp_wed }}</td>
                <td contenteditable="true" id="dsp_thur_w_cell" class="cell-data cell-wide dspthur">{{ $dsp_thur }}</td>
                <td contenteditable="true" id="dsp_fri_w_cell" class="cell-data cell-wide dspfri">{{ $dsp_fri }}</td>
                <td contenteditable="true" id="dsp_sat_w_cell" class="cell-data cell-wide dspsat">{{ $dsp_sat }}</td>
                <td contenteditable="true" id="dsp_sun_w_cell" class="cell-data cell-wide dspsun">{{ $dsp_sun }}</td>
                <td>
                    <h4 id="dsp_total_w" class="cell-wide dsp_total"></h4>
                </td>
            </tr>
            <tr>
                <th class="th-vert">Intake (£)</th>
                <td contenteditable="true" id="itk_mon_w_cell" class="cell-data cell-wide itkmon">{{ $itk_mon }}</td>
                <td contenteditable="true" id="itk_tue_w_cell" class="cell-data cell-wide itktue">{{ $itk_tue }}</td>
                <td contenteditable="true" id="itk_wed_w_cell" class="cell-data cell-wide itkwed">{{ $itk_wed }}</td>
                <td contenteditable="true" id="itk_thur_w_cell" class="cell-data cell-wide itkthur">{{ $itk_thur }}</td>
                <td contenteditable="true" id="itk_fri_w_cell" class="cell-data cell-wide itkfri">{{ $itk_fri }}</td>
                <td contenteditable="true" id="itk_sat_w_cell" class="cell-data cell-wide itksat">{{ $itk_sat }}</td>
                <td contenteditable="true" id="itk_sun_w_cell" class="cell-data cell-wide itksun">{{ $itk_sun }}</td>
                <td>
                    <h4 id="itk_total_w" class="cell-wide itk_total"></h4>
                </td>
            </tr>
            <tr>
                <th class="th-vert">Total Credits</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th class="th-vert">Net Food Sales</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th class="th-vert">Daily Food GP</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th class="th-vert">Weekly Results</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th class="th-vert">Food GP %</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="row">
        <form id="fields_form_narrow" action="dashboard-update" method="get" enctype="multipart/form-data">
            @csrf
            <input value="sunwide" type="hidden" id="input_sun_hidden_update_w" name="input_sun_hidden_update">
            <input value="monwide" type="hidden" id="input_mon_hidden_update_w" name="input_mon_hidden_update">
            <input type="hidden" name="dsp_mon" id="dsp_mon_w">
            <input type="hidden" name="dsp_tue" id="dsp_tue_w">
            <input type="hidden" name="dsp_wed" id="dsp_wed_w">
            <input type="hidden" name="dsp_thur" id="dsp_thur_w">
            <input type="hidden" name="dsp_fri" id="dsp_fri_w">
            <input type="hidden" name="dsp_sat" id="dsp_sat_w">
            <input type="hidden" name="dsp_sun" id="dsp_sun_w">
            <input type="hidden" name="itk_mon" id="itk_mon_w">
            <input type="hidden" name="itk_tue" id="itk_tue_w">
            <input type="hidden" name="itk_wed" id="itk_wed_w">
            <input type="hidden" name="itk_thur" id="itk_thur_w">
            <input type="hidden" name="itk_fri" id="itk_fri_w">
            <input type="hidden" name="itk_sat" id="itk_sat_w">
            <input type="hidden" name="itk_sun" id="itk_sun_w">
            <br>
            <button class="btn btn-primary btn-sm mx-auto" type="submit">Save</button>
        </form>
    </div>
    <br>

</div>
<script>
//pasted from narrow
//populate hidden inputw with table data

function updateWide() {
    //dsp
    $("#dsp_mon_w").val($("#dsp_mon_w_cell").text());
    $("#dsp_tue_w").val($("#dsp_tue_w_cell").text());
    $("#dsp_wed_w").val($("#dsp_wed_w_cell").text());
    $("#dsp_thur_w").val($("#dsp_thur_w_cell").text());
    $("#dsp_fri_w").val($("#dsp_fri_w_cell").text());
    $("#dsp_sat_w").val($("#dsp_sat_w_cell").text());
    $("#dsp_sun_w").val($("#dsp_sun_w_cell").text());
    //itk
    $("#itk_mon_w").val($("#itk_mon_w_cell").text());
    $("#itk_tue_w").val($("#itk_tue_w_cell").text());
    $("#itk_wed_w").val($("#itk_wed_w_cell").text());
    $("#itk_thur_w").val($("#itk_thur_w_cell").text());
    $("#itk_fri_w").val($("#itk_fri_w_cell").text());
    $("#itk_sat_w").val($("#itk_sat_w_cell").text());
    $("#itk_sun_w").val($("#itk_sun_w_cell").text());

    //populating week
    var mon_update = $(".week-mon-hidden").text();
    var sun_update = $(".week-sun-hidden").text();
    $("#input_mon_hidden_update_w").val(mon_update);
    $("#input_sun_hidden_update_w").val(sun_update);
    //alert($("#input_sun_hidden_update").val());
};

$(document).ready(function () {
    updateWide();
});

$(document).click(function () {
    updateWide();
});

</script>