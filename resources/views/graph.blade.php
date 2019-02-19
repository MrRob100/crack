<div class="row">
    <div class="col-12 mx-auto text-center">
        <svg class="graph-canvas" width="200" height="200" version="1.1" xmlns="http://www.w3.org/2000/svg">
        
          <rect class="inner-rect"
  style="stroke-width:1;fill:aquamarine" />
        
        </svg>
    </div>
</div>

<script>

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

$('.graph-canvas').attr('width', width);
$('.graph-canvas').attr('height', height);


$('.inner-rect').attr('x', areaOffsetX);
$('.inner-rect').attr('y', areaOffsetY);
$('.inner-rect').attr('width', areaWidth);
$('.inner-rect').attr('height', areaHeight);



</script>