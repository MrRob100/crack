@extends('layouts.app')

@section('content')

<script type="text/javascript" src="js/data.js"></script>

<form id="week_select" action="dashboard" method="get" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="input_mon" name="input_mon">
    <input type="hidden" id="input_sun" name="input_sun">
    <input type="hidden" id="input_sun_hidden" name="input_sun_hidden">
    <input type="hidden" id="input_mon_hidden" name="input_mon_hidden">
</form>
<div class="row">
    <div class="col"></div>
    <div class="col text-center">Week:</div>
    <div class="col"></div>
</div>
<div class="row text-center row-week-select col-md-8 mx-auto">
    <div class="col-5">
        <h4 class="week-mon week">{{ $the_mon }}<h4>
    </div>
    <div class="col-2">
        <h4 class="week">-</h4>
    </div>
    <div class="col-5">
        <h4 class="week-sun week">{{ $the_sun }}<h4>
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
<!-- Wide -->
@include('dashwideclient')

<!-- Mobile -->
@include('dashnarrowclient')

<div class="row">
    <div class="col-12">
        <div class="panel panel-default info-card">
            <div class="panel-body panel-data">
                <div class="col-6 mx-auto data-div">
                    <h4>GP - Week</h4>
                    <h4 id="gp_currency"></h4>
                    <h4 id="gp_percent"></h4>
                    <hr>
                    <h4>Budget - Rest of Week:</h4>
                    <h4 id="remaining_budget" class="th-vert"></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
@include('graph')

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

    dsp_mon = parseFloat($('#dsp_mon_w_cell').text());
    itk_mon = parseFloat($('#itk_mon_w_cell').text());
    dsp_tue = parseFloat($('#dsp_tue_w_cell').text());
    itk_tue = parseFloat($('#itk_tue_w_cell').text());
    dsp_wed = parseFloat($('#dsp_wed_w_cell').text());
    itk_wed = parseFloat($('#itk_wed_w_cell').text());
    dsp_thur = parseFloat($('#dsp_thur_w_cell').text());
    itk_thur = parseFloat($('#itk_thur_w_cell').text());
    dsp_fri = parseFloat($('#dsp_fri_w_cell').text());
    itk_fri = parseFloat($('#itk_fri_w_cell').text());
    dsp_sat = parseFloat($('#dsp_sat_w_cell').text());
    itk_sat = parseFloat($('#itk_sat_w_cell').text());
    dsp_sun = parseFloat($('#dsp_sun_w_cell').text());
    itk_sun = parseFloat($('#itk_sun_w_cell').text());

    dsp_total = dsp_mon + dsp_tue + dsp_wed + dsp_thur + dsp_fri + dsp_sat + dsp_sun;
    itk_total = itk_mon + itk_tue + itk_wed + itk_thur + itk_fri + itk_sat + itk_sun;

    gp_currency = itk_total - dsp_total;
    gp_percent = (gp_currency / itk_total) * 100;

    $('.dsp_total').text(dsp_total);
    $('.itk_total').text(itk_total);

    $('#gp_currency').text("£" + gp_currency);
    $('#gp_percent').text(gp_percent + "%");

    //CALL GRAPH FUNCTION AND PASS IN VALUES
    setChart();
}

function calcNarrow() {

    dsp_mon = parseFloat($('#dsp_mon_n_cell').text());
    itk_mon = parseFloat($('#itk_mon_n_cell').text());
    dsp_tue = parseFloat($('#dsp_tue_n_cell').text());
    itk_tue = parseFloat($('#itk_tue_n_cell').text());
    dsp_wed = parseFloat($('#dsp_wed_n_cell').text());
    itk_wed = parseFloat($('#itk_wed_n_cell').text());
    dsp_thur = parseFloat($('#dsp_thur_n_cell').text());
    itk_thur = parseFloat($('#itk_thur_n_cell').text());
    dsp_fri = parseFloat($('#dsp_fri_n_cell').text());
    itk_fri = parseFloat($('#itk_fri_n_cell').text());
    dsp_sat = parseFloat($('#dsp_sat_n_cell').text());
    itk_sat = parseFloat($('#itk_sat_n_cell').text());
    dsp_sun = parseFloat($('#dsp_sun_n_cell').text());
    itk_sun = parseFloat($('#itk_sun_n_cell').text());

    dsp_total = dsp_mon + dsp_tue + dsp_wed + dsp_thur + dsp_fri + dsp_sat + dsp_sun;
    itk_total = itk_mon + itk_tue + itk_wed + itk_thur + itk_fri + itk_sat + itk_sun;

    gp_currency = itk_total - dsp_total;
    gp_percent = (gp_currency / itk_total) * 100;

    $('.dsp_total').text(dsp_total);
    $('.itk_total').text(itk_total);

    $('#gp_currency').text("£" + gp_currency);
    $('#gp_percent').text(gp_percent + "%");

    //CALL GRAPH FUNCTION AND PASS IN VALUES
    setChart(dsp_mon);
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

//////////////////////////////////////////////////
/////////////// GRAPH ////////////////////////////
//////////////////////////////////////////////////

//append lines
// $('.graph-canvas').append('<svg height="210" width="500"><line x1="0" y1="0" x2="200" y2="200" style="stroke:rgb(255,0,0);stroke-width:2" /></svg>');

function setChart() {

    var windowWidth = $(window).width(); 

    if (windowWidth < 585) 
    {
        width = windowWidth * 0.8;
        height = 200;
    }
    else
    {
        width = windowWidth * 0.8;
        height = 300;
    }

    areaOffsetX = width / 10;
    areaOffsetY = height / 10;

    areaWidth = width * 0.8;
    areaHeight = height * 0.8;
    interceptOffsetY = height * 0.9;
    endOffsetX = width * 0.9;

    dayWidth = areaWidth / 7;
    halfDayWidth = dayWidth / 2;

    mondayStartOffset = areaOffsetX;
    tuesdayStartOffset = areaOffsetX + dayWidth;
    wednesdayStartOffset = areaOffsetX + (dayWidth * 2);
    thursdayStartOffset = areaOffsetX + (dayWidth * 3);
    fridayStartOffset = areaOffsetX + (dayWidth * 4);
    saturdayStartOffset = areaOffsetX + (dayWidth * 5);
    sundayStartOffset = areaOffsetX + (dayWidth * 6);

    monPointX = mondayStartOffset + halfDayWidth;
    tuePointX = tuesdayStartOffset + halfDayWidth;
    wedPointX = wednesdayStartOffset + halfDayWidth;
    thurPointX = thursdayStartOffset + halfDayWidth;
    friPointX = fridayStartOffset + halfDayWidth;
    satPointX = saturdayStartOffset + halfDayWidth;
    sunPointX = sundayStartOffset + halfDayWidth;

    $('.axis').attr('stroke', 'black'); //set color of axes
    $('.axis').attr('stroke-width', 1.5); //set color of axes
    $('.point').attr('r', 4); //set radius of point
    $('.dsp-point').attr('fill', 'blue'); //set color of dsp points

    $('.graph-canvas').attr('width', width);
    $('.graph-canvas').attr('height', height);

    $('.inner-rect').attr('fill', '#C6D3DB'); //set color of inner rectangle
    $('.inner-rect').attr('x', areaOffsetX);
    $('.inner-rect').attr('y', areaOffsetY);
    $('.inner-rect').attr('width', areaWidth);
    $('.inner-rect').attr('height', areaHeight);

    $('.y-axis').attr('x1', areaOffsetX);
    $('.y-axis').attr('x2', areaOffsetX);
    $('.y-axis').attr('y1', areaOffsetY);
    $('.y-axis').attr('y2', interceptOffsetY);

    $('.x-axis').attr('x1', areaOffsetX);
    $('.x-axis').attr('x2', endOffsetX);
    $('.x-axis').attr('y1', interceptOffsetY);
    $('.x-axis').attr('y2', interceptOffsetY);

    $('.dsp-mon-point').attr('cx', monPointX);
    $('.dsp-mon-point').attr('cy', interceptOffsetY);

    $('.dsp-tue-point').attr('cx', tuePointX);
    $('.dsp-tue-point').attr('cy', interceptOffsetY);

    $('.dsp-wed-point').attr('cx', wedPointX);
    $('.dsp-wed-point').attr('cy', interceptOffsetY);

    $('.dsp-thur-point').attr('cx', thurPointX);
    $('.dsp-thur-point').attr('cy', interceptOffsetY);

    $('.dsp-fri-point').attr('cx', friPointX);
    $('.dsp-fri-point').attr('cy', interceptOffsetY);

    $('.dsp-sat-point').attr('cx', satPointX);
    $('.dsp-sat-point').attr('cy', interceptOffsetY);

    $('.dsp-sun-point').attr('cx', sunPointX);
    $('.dsp-sun-point').attr('cy', interceptOffsetY);

}

</script>

@endsection

