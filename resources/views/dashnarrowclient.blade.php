<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10 offset-md-1 mobile-size">
            <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col" class="current-week"></th>
                <th scope="col">Stock Purchase</th>
                <th scope="col">Intake</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <th scope="row">Mon</th>
                <td contenteditable="true" id="dsp_mon_n_cell" class="cell-data dspmon">{{ $dsp_mon }}</td>
                <td contenteditable="true" id="itk_mon_n_cell" class="cell-data itkmon">{{ $itk_mon }}</td>
                </tr>
                <tr>
                <th scope="row">Tue</th>
                <td contenteditable="true" id="dsp_tue_n_cell" class="cell-data dsptue">{{ $dsp_tue }}</td>
                <td contenteditable="true" id="itk_tue_n_cell" class="cell-data itktue">{{ $itk_tue }}</td>
                </tr>
                <tr>
                <th scope="row">Wed</th>
                <td contenteditable="true" id="dsp_wed_n_cell" class="cell-data dspwed">{{ $dsp_wed }}</td>
                <td contenteditable="true" id="itk_wed_n_cell" class="cell-data itkwed">{{ $itk_wed }}</td>
                </tr>
                <tr>
                <th scope="row">Thur</th>
                <td contenteditable="true" id="dsp_thur_n_cell" class="cell-data dspthur">{{ $dsp_thur }}</td>
                <td contenteditable="true" id="itk_thur_n_cell" class="cell-data itkthur">{{ $itk_thur }}</td>
                </tr>
                <tr>
                <th scope="row">Fri</th>
                <td contenteditable="true" id="dsp_fri_n_cell" class="cell-data dspfri">{{ $dsp_fri }}</td>
                <td contenteditable="true" id="itk_fri_n_cell" class="cell-data itkfri">{{ $itk_fri }}</td>
                </tr>
                <tr>
                <th scope="row">Sat</th>
                <td contenteditable="true" id="dsp_sat_n_cell" class="cell-data dspsat">{{ $dsp_sat }}</td>
                <td contenteditable="true" id="itk_sat_n_cell" class="cell-data itksat">{{ $itk_sat }}</td>
                </tr>
                <tr>
                <th scope="row">Sun</th>
                <td contenteditable="true" id="dsp_sun_n_cell" class="cell-data dspsun">{{ $dsp_sun }}</td>
                <td contenteditable="true" id="itk_sun_n_cell" class="cell-data itksun">{{ $itk_sun }}</td>
                </tr>
                <tr>
                <th scope="row">Total</th>
                <td id="dsp_total_n" class="cell-data dsp_total"></td>
                <td id="itk_total_n" class="cell-data itk_total"></td>
                </tr>
            </tbody>
            </table>
            <div class="row">
                <form id="fields_form_narrow" action="dashboard-update" method="get" enctype="multipart/form-data">
                    @csrf
                    <input value="sunwide" type="hidden" id="input_sun_hidden_update" name="input_sun_hidden_update_n">
                    <input value="monwide" type="hidden" id="input_mon_hidden_update" name="input_mon_hidden_update_n">
                    <input type="hidden" name="dsp_mon" id="dsp_mon_n" class="dsp_mon">
                    <input type="hidden" name="dsp_tue" id="dsp_tue_n" class="dsp_tue">
                    <input type="hidden" name="dsp_wed" id="dsp_wed_n" class="dsp_wed">
                    <input type="hidden" name="dsp_thur" id="dsp_thur_n" class="dsp_thur">
                    <input type="hidden" name="dsp_fri" id="dsp_fri_n" class="dsp_fri">
                    <input type="hidden" name="dsp_sat" id="dsp_sat_n" class="dsp_sat">
                    <input type="hidden" name="dsp_sun" id="dsp_sun_n" class="dsp_sun">
                    <input type="hidden" name="itk_mon" id="itk_mon_n" class="itk_mon">
                    <input type="hidden" name="itk_tue" id="itk_tue_n" class="itk_tue">
                    <input type="hidden" name="itk_wed" id="itk_wed_n" class="itk_wed">
                    <input type="hidden" name="itk_thur" id="itk_thur_n" class="itk_thur">
                    <input type="hidden" name="itk_fri" id="itk_fri_n" class="itk_fri">
                    <input type="hidden" name="itk_sat" id="itk_sat_n" class="itk_sat">
                    <input type="hidden" name="itk_sun" id="itk_sun_n" class="itk_sun">
                    <br>
                    <button class="btn btn-primary btn-sm mx-auto" type="subnit">Save</button>
                </form>
            </div>
            <br>
        </div>
    </div>
</div>

<script>
//pasted from narrow
//populate hidden inputw with table data


function updateNarrow() {

    //dsp
    $("#dsp_mon_n").val($("#dsp_mon_n_cell").text());
    $("#dsp_tue_n").val($("#dsp_tue_n_cell").text());
    $("#dsp_wed_n").val($("#dsp_wed_n_cell").text());
    $("#dsp_thur_n").val($("#dsp_thur_n_cell").text());
    $("#dsp_fri_n").val($("#dsp_fri_n_cell").text());
    $("#dsp_sat_n").val($("#dsp_sat_n_cell").text());
    $("#dsp_sun_n").val($("#dsp_sun_n_cell").text());
    //itk
    $("#itk_mon_n").val($("#itk_mon_n_cell").text());
    $("#itk_tue_n").val($("#itk_tue_n_cell").text());
    $("#itk_wed_n").val($("#itk_wed_n_cell").text());
    $("#itk_thur_n").val($("#itk_thur_n_cell").text());
    $("#itk_fri_n").val($("#itk_fri_n_cell").text());
    $("#itk_sat_n").val($("#itk_sat_n_cell").text());
    $("#itk_sun_n").val($("#itk_sun_n_cell").text());

    //populating week
    var mon_update = $(".week-mon-hidden").text();
    var sun_update = $(".week-sun-hidden").text();
    $("#input_mon_hidden_update_n").val(mon_update);
    $("#input_sun_hidden_update_n").val(sun_update);
    //alert($("#input_sun_hidden_update").val());

};


$(document).ready(function () {
    updateNarrow();
});

$(document).click(function () {
    updateNarrow();
});

</script>