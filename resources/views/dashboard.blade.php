@extends('layouts.app')

@section('content')

<script type="text/javascript" src="js/data.js"></script>
<script type="text/javascript" src="js/chart.js"></script>

<?php

//print_r($itk_mon_ave);

?>
<form id="week_select" action="dashboard" method="get" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="input_mon" name="input_mon">
    <input type="hidden" id="input_sun" name="input_sun">
    <input type="hidden" id="input_sun_hidden" name="input_sun_hidden">
    <input type="hidden" id="input_mon_hidden" name="input_mon_hidden">
</form>
<div class="row current-week current-week-top">
    <div class="col"></div>
    <div class="col text-center">Week:</div>
    <div class="col"></div>
</div>
<div class="row text-center row-week-select col-md-8 mx-auto">
    <div class="col-5">
        <h4 class="week-mon week float-right">{{ $the_mon }}<h4>
    </div>
    <div class="col-2">
        <h4 class="week">-</h4>
    </div>
    <div class="col-5">
        <h4 class="week-sun week float-left">{{ $the_sun }}<h4>
    </div>

</div>
<div class="row text-center mx-auto">
    <div class="col-6">
        <button type="submit" class="md-12 float-right week-previous week-btn">
            <p class="week-labels">Previous Week</p>
        </button>
    </div>
    <div class="col-6">
        <button type="submit" class="md-12 text-center float-left week-next week-btn">
            <p class="week-labels">Next Week</p>
        </button>
    </div>
</div>
<br>
<!-- Graph -->
@include('graph')
<br>

<div class="row">
    <div class="col-12">
        <div class="panel panel-default info-card">
            <div class="panel-body panel-data">
                <div class="col-6 mx-auto data-div">
                    <h4>GP Week</h4>
                    <h4 id="gp_currency"></h4>
                    <h4 id="gp_percent"></h4>
                    <hr>
                    <h4>Budget Rest of Week:</h4>
                    <h4 id="remaining_budget" class="th-vert"></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

<!-- Wide -->
@include('dashwideclient')

<!-- Mobile -->
@include('dashnarrowclient')

<h4 class="week-hidden week-mon-hidden">{{ $mon_unix }}</h4>
<h4 class="week-hidden week-sun-hidden">{{ $sun_unix }}</h4>

<script>
//select on click


$('.cell-data').on('focus', function() {
  var cell = this;
  var range, selection;
  if (document.body.createTextRange) {
    range = document.body.createTextRange();
    range.moveToElementText(cell);
    range.select();
  } else if (window.getSelection) {
    selection = window.getSelection();
    range = document.createRange();
    range.selectNodeContents(cell);
    selection.removeAllRanges();
    selection.addRange(range);
  }
});

    var monDisplay = "<?php echo $mon_unix; ?>";
    var monCurrent = "<?php echo $current_mon; ?>";

if (monDisplay == monCurrent)
{
    $(".current-week").text('current week');
    $(".current-week").css('background-color', '#28B473');
    $(".current-week").css('color', '#fff');
}

//Week Previous
    $(".week-previous").click(function () {
        //Monday
        var nextMonUnix = parseInt(($(".week-mon-hidden").text())) - 604800;
        var nextMon = new Date(nextMonUnix * 1000);

        //$(".week-mon").text(nextMon);
        $("#input_mon").val(nextMon);

        $(".week-mon-hidden").text(nextMonUnix);
        $("#input_mon_hidden").val(nextMonUnix);

        //Sunday
        var nextSunUnix = parseInt(($(".week-sun-hidden").text())) - 604800;
        var nextSun = new Date(nextSunUnix * 1000);

        //$(".week-sun").text(nextSun);
        $("#input_sun").val(nextSun);

        $(".week-sun-hidden").text(nextSunUnix);
        $("#input_sun_hidden").val(nextSunUnix);
        //

        $("#week_select").submit();
    });

    //Week Next
    $(".week-next").click(function () {
        //Monday
        var nextMonUnix = parseInt(($(".week-mon-hidden").text())) + 604800;
        var nextMon = new Date(nextMonUnix * 1000);

        //$(".week-mon").text(nextMon);
        $("#input_mon").val(nextMon);

        $(".week-mon-hidden").text(nextMonUnix);
        $("#input_mon_hidden").val(nextMonUnix);

        //Sunday
        var nextSunUnix = parseInt(($(".week-sun-hidden").text())) + 604800;
        var nextSun = new Date(nextSunUnix * 1000);

        //$(".week-sun").text(nextSun);
        $("#input_sun").val(nextSun);

        $(".week-sun-hidden").text(nextSunUnix);
        $("#input_sun_hidden").val(nextSunUnix);

        $("#week_select").submit();
    });

//THE ABOVE COULD GO IN SEPARATE FILE????


function calcWide() {

    itk_mon_ave = '<?php echo $itk_mon_ave; ?>';
    itk_tue_ave = '<?php echo $itk_tue_ave; ?>';
    itk_wed_ave = '<?php echo $itk_wed_ave; ?>';
    itk_thur_ave = '<?php echo $itk_thur_ave; ?>';
    itk_fri_ave = '<?php echo $itk_fri_ave; ?>';
    itk_sat_ave = '<?php echo $itk_sat_ave; ?>';
    itk_sun_ave = '<?php echo $itk_sun_ave; ?>';

    dsp_mon = parseFloat($('#dsp_mon_w_cell').text()) + 0.00001;
    itk_mon = parseFloat($('#itk_mon_w_cell').text()) + 0.00001;
    dsp_tue = parseFloat($('#dsp_tue_w_cell').text()) + 0.00001;
    itk_tue = parseFloat($('#itk_tue_w_cell').text()) + 0.00001;
    dsp_wed = parseFloat($('#dsp_wed_w_cell').text()) + 0.00001;
    itk_wed = parseFloat($('#itk_wed_w_cell').text()) + 0.00001;
    dsp_thur = parseFloat($('#dsp_thur_w_cell').text()) + 0.00001;
    itk_thur = parseFloat($('#itk_thur_w_cell').text()) + 0.00001;
    dsp_fri = parseFloat($('#dsp_fri_w_cell').text()) + 0.00001;
    itk_fri = parseFloat($('#itk_fri_w_cell').text()) + 0.00001;
    dsp_sat = parseFloat($('#dsp_sat_w_cell').text()) + 0.00001;
    itk_sat = parseFloat($('#itk_sat_w_cell').text()) + 0.00001;
    dsp_sun = parseFloat($('#dsp_sun_w_cell').text()) + 0.00001;
    itk_sun = parseFloat($('#itk_sun_w_cell').text()) + 0.00001;

    dsp_total = dsp_mon + dsp_tue + dsp_wed + dsp_thur + dsp_fri + dsp_sat + dsp_sun;
    itk_total = itk_mon + itk_tue + itk_wed + itk_thur + itk_fri + itk_sat + itk_sun;

    gp_currency = itk_total - dsp_total;
    gp_percent = (gp_currency / itk_total) * 100;

    $('.dsp_total').text((Math.floor(dsp_total * 100)) / 100);
    $('.itk_total').text((Math.floor(itk_total * 100)) / 100);

    $('#gp_currency').text("£" + (Math.floor(gp_currency * 100)) / 100);
    $('#gp_percent').text((Math.floor(gp_percent * 10)) / 10 + "%");

    setChart();
}

function calcNarrow() {

    itk_mon_ave = '<?php echo $itk_mon_ave; ?>';
    itk_tue_ave = '<?php echo $itk_tue_ave; ?>';
    itk_wed_ave = '<?php echo $itk_wed_ave; ?>';
    itk_thur_ave = '<?php echo $itk_thur_ave; ?>';
    itk_fri_ave = '<?php echo $itk_fri_ave; ?>';
    itk_sat_ave = '<?php echo $itk_sat_ave; ?>';
    itk_sun_ave = '<?php echo $itk_sun_ave; ?>';

    dsp_mon = parseFloat($('#dsp_mon_n_cell').text()) + 0.00001;
    itk_mon = parseFloat($('#itk_mon_n_cell').text()) + 0.00001;
    dsp_tue = parseFloat($('#dsp_tue_n_cell').text()) + 0.00001;
    itk_tue = parseFloat($('#itk_tue_n_cell').text()) + 0.00001;
    dsp_wed = parseFloat($('#dsp_wed_n_cell').text()) + 0.00001;
    itk_wed = parseFloat($('#itk_wed_n_cell').text()) + 0.00001;
    dsp_thur = parseFloat($('#dsp_thur_n_cell').text()) + 0.00001;
    itk_thur = parseFloat($('#itk_thur_n_cell').text()) + 0.00001;
    dsp_fri = parseFloat($('#dsp_fri_n_cell').text()) + 0.00001;
    itk_fri = parseFloat($('#itk_fri_n_cell').text()) + 0.00001;
    dsp_sat = parseFloat($('#dsp_sat_n_cell').text()) + 0.00001;
    itk_sat = parseFloat($('#itk_sat_n_cell').text()) + 0.00001;
    dsp_sun = parseFloat($('#dsp_sun_n_cell').text()) + 0.00001;
    itk_sun = parseFloat($('#itk_sun_n_cell').text()) + 0.00001;

    dsp_total = dsp_mon + dsp_tue + dsp_wed + dsp_thur + dsp_fri + dsp_sat + dsp_sun;
    itk_total = itk_mon + itk_tue + itk_wed + itk_thur + itk_fri + itk_sat + itk_sun;

    gp_currency = itk_total - dsp_total;
    gp_percent = (gp_currency / itk_total) * 100;

    $('.dsp_total').text((Math.floor(dsp_total * 100)) / 100);
    $('.itk_total').text((Math.floor(itk_total * 100)) / 100);

    $('#gp_currency').text("£" + (Math.floor(gp_currency * 100)) / 100);
    $('#gp_percent').text((Math.floor(gp_percent * 10)) / 10 + "%");

    setChart();
}

function widthCondition() {

    var windowWidth = $(window).width(); 

    if (windowWidth < 585) 
    {
        //Narrow
        calcNarrow();

        days.forEach(element => {
            $("#dsp_"+ element +"_w_cell").text("");
            $("#dsp_"+ element +"_w_cell").text($(".dsp" + element).text());
        });

        days.forEach(element => {
            $("#itk_"+ element +"_w_cell").text("");
            $("#itk_"+ element +"_w_cell").text($(".itk" + element).text());
        });

    }
    else 
    {
        //Wide
        calcWide();

        days.forEach(element => {
            $("#dsp_"+ element +"_n_cell").text("");
            $("#dsp_"+ element +"_n_cell").text($(".dsp" + element).text());
        });

        days.forEach(element => {
            $("#itk_"+ element +"_n_cell").text("");
            $("#itk_"+ element +"_n_cell").text($(".itk" + element).text());
        });
    }
}

$(document).ready(function () {
    widthCondition();
});

$(document).click(function () {
    widthCondition();
});

</script>

@endsection

