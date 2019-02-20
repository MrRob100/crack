function setChart() {

    var allValues = [dsp_mon, dsp_tue, dsp_wed, dsp_thur, dsp_fri, dsp_sat, dsp_sun, itk_mon, itk_tue, itk_wed, itk_thur, itk_fri, itk_sat, itk_sun];

    arrayAllValues = Object.values(allValues);
    maxVal = Math.max(...arrayAllValues);

    limitVal = (Math.ceil(maxVal/100)) * 100;

    var windowWidth = $(window).width();

    if (windowWidth < 585) {
        width = windowWidth * 0.8;
        height = 200;
    } else {
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

    //gridlines
    nGridlines = limitVal / 100;
    gridlineSpace = areaHeight / nGridlines;
    n = 0;
    for (n = 0; n < nGridlines; n++) {
        y = areaOffsetY + (n * gridlineSpace);
        $('.graph-canvas').append('<svg><line class="gridline" x1="' + areaOffsetX + '" x2="' + (areaOffsetX + (dayWidth * 7)) + '" y1="' + y + '" y2="' + y + '" /></svg>')
        //console.log('n = ', n);
    }

    // $('.graph-canvas').append('<svg><line x1="50" y1="50" x2="200" y2="200" stroke="red" stroke-width="2" /></svg>');

    $('.axis').attr('stroke', 'black'); //set color of axes
    $('.axis').attr('stroke-width', 1.5); //set stroke width of axes
    $('.point').attr('r', 3); //set radius of point
    $('.dsp-point').attr('fill', 'blue'); //set color of dsp points
    $('.itk-point').attr('fill', '#57B474'); //set color of itk points

    //set connecting line style
    $('.connecting-line-dsp').attr('stroke', 'blue');
    $('.connecting-line-dsp').attr('stroke-width', 1.5);

    $('.connecting-line-itk').attr('stroke', '#51B474');
    $('.connecting-line-itk').attr('stroke-width', 1.5);

    $('.gridline').attr('stroke', '#777');
    $('.gridline').attr('stroke-width', 1);

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

    scaleFactor = (interceptOffsetY - areaOffsetY) / limitVal;

    dspMonAfloat = (-(dsp_mon * scaleFactor)) + interceptOffsetY;
    dspTueAfloat = (-(dsp_tue * scaleFactor)) + interceptOffsetY;
    dspWedAfloat = (-(dsp_wed * scaleFactor)) + interceptOffsetY;
    dspThurAfloat = (-(dsp_thur * scaleFactor)) + interceptOffsetY;
    dspFriAfloat = (-(dsp_fri * scaleFactor)) + interceptOffsetY;
    dspSatAfloat = (-(dsp_sat * scaleFactor)) + interceptOffsetY;
    dspSunAfloat = (-(dsp_sun * scaleFactor)) + interceptOffsetY;

    itkMonAfloat = (-(itk_mon * scaleFactor)) + interceptOffsetY;
    itkTueAfloat = (-(itk_tue * scaleFactor)) + interceptOffsetY;
    itkWedAfloat = (-(itk_wed * scaleFactor)) + interceptOffsetY;
    itkThurAfloat = (-(itk_thur * scaleFactor)) + interceptOffsetY;
    itkFriAfloat = (-(itk_fri * scaleFactor)) + interceptOffsetY;
    itkSatAfloat = (-(itk_sat * scaleFactor)) + interceptOffsetY;
    itkSunAfloat = (-(itk_sun * scaleFactor)) + interceptOffsetY;

    //dsp points
    $('.dsp-mon-point').attr('cx', monPointX);
    $('.dsp-mon-point').attr('cy', dspMonAfloat);

    $('.dsp-tue-point').attr('cx', tuePointX);
    $('.dsp-tue-point').attr('cy', dspTueAfloat);

    $('.dsp-wed-point').attr('cx', wedPointX);
    $('.dsp-wed-point').attr('cy', dspWedAfloat);

    $('.dsp-thur-point').attr('cx', thurPointX);
    $('.dsp-thur-point').attr('cy', dspThurAfloat);

    $('.dsp-fri-point').attr('cx', friPointX);
    $('.dsp-fri-point').attr('cy', dspFriAfloat);

    $('.dsp-sat-point').attr('cx', satPointX);
    $('.dsp-sat-point').attr('cy', dspSatAfloat);

    $('.dsp-sun-point').attr('cx', sunPointX);
    $('.dsp-sun-point').attr('cy', dspSunAfloat);

    //dsp connecting lines
    $('.dsp-mon-tue').attr('x1', monPointX);
    $('.dsp-mon-tue').attr('x2', tuePointX);
    $('.dsp-mon-tue').attr('y1', dspMonAfloat);
    $('.dsp-mon-tue').attr('y2', dspTueAfloat);

    $('.dsp-tue-wed').attr('x1', tuePointX);
    $('.dsp-tue-wed').attr('x2', wedPointX);
    $('.dsp-tue-wed').attr('y1', dspTueAfloat);
    $('.dsp-tue-wed').attr('y2', dspWedAfloat);

    $('.dsp-wed-thur').attr('x1', wedPointX);
    $('.dsp-wed-thur').attr('x2', thurPointX);
    $('.dsp-wed-thur').attr('y1', dspWedAfloat);
    $('.dsp-wed-thur').attr('y2', dspThurAfloat);

    $('.dsp-thur-fri').attr('x1', thurPointX);
    $('.dsp-thur-fri').attr('x2', friPointX);
    $('.dsp-thur-fri').attr('y1', dspThurAfloat);
    $('.dsp-thur-fri').attr('y2', dspFriAfloat);

    $('.dsp-fri-sat').attr('x1', friPointX);
    $('.dsp-fri-sat').attr('x2', satPointX);
    $('.dsp-fri-sat').attr('y1', dspFriAfloat);
    $('.dsp-fri-sat').attr('y2', dspSatAfloat);

    $('.dsp-sat-sun').attr('x1', satPointX);
    $('.dsp-sat-sun').attr('x2', sunPointX);
    $('.dsp-sat-sun').attr('y1', dspSatAfloat);
    $('.dsp-sat-sun').attr('y2', dspSunAfloat);

    //itk points
    $('.itk-mon-point').attr('cx', monPointX);
    $('.itk-mon-point').attr('cy', itkMonAfloat);

    $('.itk-tue-point').attr('cx', tuePointX);
    $('.itk-tue-point').attr('cy', itkTueAfloat);

    $('.itk-wed-point').attr('cx', wedPointX);
    $('.itk-wed-point').attr('cy', itkWedAfloat);

    $('.itk-thur-point').attr('cx', thurPointX);
    $('.itk-thur-point').attr('cy', itkThurAfloat);

    $('.itk-fri-point').attr('cx', friPointX);
    $('.itk-fri-point').attr('cy', itkFriAfloat);

    $('.itk-sat-point').attr('cx', satPointX);
    $('.itk-sat-point').attr('cy', itkSatAfloat);

    $('.itk-sun-point').attr('cx', sunPointX);
    $('.itk-sun-point').attr('cy', itkSunAfloat);

    //itk connecting lines
    $('.itk-mon-tue').attr('x1', monPointX);
    $('.itk-mon-tue').attr('x2', tuePointX);
    $('.itk-mon-tue').attr('y1', itkMonAfloat);
    $('.itk-mon-tue').attr('y2', itkTueAfloat);

    $('.itk-tue-wed').attr('x1', tuePointX);
    $('.itk-tue-wed').attr('x2', wedPointX);
    $('.itk-tue-wed').attr('y1', itkTueAfloat);
    $('.itk-tue-wed').attr('y2', itkWedAfloat);

    $('.itk-wed-thur').attr('x1', wedPointX);
    $('.itk-wed-thur').attr('x2', thurPointX);
    $('.itk-wed-thur').attr('y1', itkWedAfloat);
    $('.itk-wed-thur').attr('y2', itkThurAfloat);

    $('.itk-thur-fri').attr('x1', thurPointX);
    $('.itk-thur-fri').attr('x2', friPointX);
    $('.itk-thur-fri').attr('y1', itkThurAfloat);
    $('.itk-thur-fri').attr('y2', itkFriAfloat);

    $('.itk-fri-sat').attr('x1', friPointX);
    $('.itk-fri-sat').attr('x2', satPointX);
    $('.itk-fri-sat').attr('y1', itkFriAfloat);
    $('.itk-fri-sat').attr('y2', itkSatAfloat);

    $('.itk-sat-sun').attr('x1', satPointX);
    $('.itk-sat-sun').attr('x2', sunPointX);
    $('.itk-sat-sun').attr('y1', itkSatAfloat);
    $('.itk-sat-sun').attr('y2', itkSunAfloat);

}