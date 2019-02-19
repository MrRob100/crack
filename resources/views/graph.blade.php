<div class="row">
    <div class="col-12 mx-auto text-center graph-div">
        <svg class="graph-canvas" width="200" height="200" version="1.1" xmlns="http://www.w3.org/2000/svg">
        
            <rect class="inner-rect"/>
            <line class="x-axis axis" />
            <line class="y-axis axis" />
            <circle class="dsp-mon-point dsp-point point" cx="50" cy="50" r="5" stroke="black" stroke-width="0" />
            <circle class="dsp-tue-point dsp-point point" cx="50" cy="50" r="5" stroke="black" stroke-width="0" />
            <circle class="dsp-wed-point dsp-point point" cx="50" cy="50" r="5" stroke="black" stroke-width="0" />
            <circle class="dsp-thur-point dsp-point point" cx="50" cy="50" r="5" stroke="black" stroke-width="0" />
            <circle class="dsp-fri-point dsp-point point" cx="50" cy="50" r="5" stroke="black" stroke-width="0" />
            <circle class="dsp-sat-point dsp-point point" cx="50" cy="50" r="5" stroke="black" stroke-width="0" />
            <circle class="dsp-sun-point dsp-point point" cx="50" cy="50" r="5" stroke="black" stroke-width="0" />
        </svg>
    </div>
</div>

<script>

//append lines
// $('.graph-canvas').append('<svg height="210" width="500"><line x1="0" y1="0" x2="200" y2="200" style="stroke:rgb(255,0,0);stroke-width:2" /></svg>');


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

</script>