function setChart() {

    var windowWidth = $(window).width();

    if (windowWidth < 585) {
        width = windowWidth * 0.95;
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


    $('.axis').attr('stroke', 'black'); //set color of axes
    $('.axis').attr('stroke-width', 1.5); //set stroke width of axes
    $('.point').attr('r', 3); //set radius of point
    $('.dsp-point').attr('fill', 'blue'); //set color of dsp points
    $('.itk-point').attr('fill', '#57B474'); //set color of itk points
    $('.itk-point-ave').attr('fill', '#303B37'); //set color of itk-ave points

    //set axes labels
    $('.x-axis-label').attr('x', width / 100);
    $('.x-axis-label').attr('y', height / 2);

    $('.label-line-itk').attr('x', width * 0.25);
    $('.label-line-itk').attr('y', height * 0.05);
    $('.label-line-itk').attr('fill', '#57B474');

    $('.label-line-dsp').attr('x', width * 0.75);
    $('.label-line-dsp').attr('y', height * 0.05);
    $('.label-line-dsp').attr('fill', 'blue');

    $('.label-line-itk-ave').attr('x', width * 0.5);
    $('.label-line-itk-ave').attr('y', height * 0.05);
    $('.label-line-itk-ave').attr('fill', '#303B37');


    //set connecting line style
    $('.connecting-line-dsp').attr('stroke', 'blue');
    $('.connecting-line-dsp').attr('stroke-width', 1.5);

    $('.connecting-line-itk').attr('stroke', '#51B474');
    $('.connecting-line-itk').attr('stroke-width', 1.5);

    $('.connecting-line-itk-ave').attr('stroke', '#303B37');
    $('.connecting-line-itk-ave').attr('stroke-width', 1.5);

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

    $('.label-mon').attr('x', monPointX);
    $('.label-mon').attr('y', height * 0.95);

    $('.label-tue').attr('x', tuePointX);
    $('.label-tue').attr('y', height * 0.95);

    $('.label-wed').attr('x', wedPointX);
    $('.label-wed').attr('y', height * 0.95);

    $('.label-thur').attr('x', thurPointX);
    $('.label-thur').attr('y', height * 0.95);

    $('.label-fri').attr('x', friPointX);
    $('.label-fri').attr('y', height * 0.95);

    $('.label-sat').attr('x', satPointX);
    $('.label-sat').attr('y', height * 0.95);

    $('.label-sun').attr('x', sunPointX);
    $('.label-sun').attr('y', height * 0.95);

    //set max here?
    var allValues = [
        dsp_mon, 
        dsp_tue, 
        dsp_wed, 
        dsp_thur, 
        dsp_fri, 
        dsp_sat, 
        dsp_sun, 
        itk_mon, 
        itk_tue, 
        itk_wed, 
        itk_thur, 
        itk_fri, 
        itk_sat, 
        itk_sun,
        itk_mon_ave,
        itk_tue_ave,
        itk_wed_ave,
        itk_thur_ave,
        itk_fri_ave,
        itk_sat_ave,
        itk_sun_ave,
    ];

    arrayAllValues = Object.values(allValues);
    maxVal = Math.max(...arrayAllValues);

    limitVal = (Math.ceil(maxVal / 100)) * 100;

    //gridlines
    $('.gridline').hide();
    $('.gridLabel').hide();
    nGridlines = limitVal / 100;
    gridlineSpace = areaHeight / nGridlines;
    n = 0;
    for (n = 0; n < nGridlines; n++) {
        y = areaOffsetY + (n * gridlineSpace);
        $('.graph-canvas').append('<svg><line class="gridline" x1="' + areaOffsetX + '" x2="' + (areaOffsetX + (dayWidth * 7)) + '" y1="' + y + '" y2="' + y + '" /></svg>')
        negScale = -n * 100;
        scale = negScale + limitVal;
        fs1 = width / 90;
        fs2 = fs1 + 6;
        labelOffsetY = height / 50;
        labelPositionY = labelOffsetY + y;
        $('.graph-canvas').append('<svg><text class="gridLabel" fill="#777" font-size="' + fs2 + '" x="' + areaOffsetX * 0.4 + '" y="' + labelPositionY + '">' + scale + '</text></svg>');
    }

    $('.label-line').attr('font-size', fs2);

    $('.label-day').attr('font-size', fs2);

    $('.gridline').attr('stroke', '#777');
    $('.gridline').attr('stroke-width', 0.7);


    //set dayline
    if (thisWeek)
    {
        dateToday = new Date().getDay();
        dayLineX = 0;
        switch (dateToday){
            case 1:
            dayLineX = monPointX;

            // itkRestPred = eval(itk_mon_ave) + eval(itk_tue_ave) + eval(itk_wed_ave) + eval(itk_thur_ave) + eval(itk_fri_ave) + eval(itk_sat_ave) + eval(itk_sun_ave);
            break;
            case 2:
            dayLineX = tuePointX;
                itkRestPred = eval(itk_mon) + eval(itk_tue_ave) + eval(itk_wed_ave) + eval(itk_thur_ave) + eval(itk_fri_ave) + eval(itk_sat_ave) + eval(itk_sun_ave);
                break;
            case 3:
            dayLineX = wedPointX;
                itkRestPred = eval(itk_mon) + eval(itk_tue) + eval(itk_wed_ave) + eval(itk_thur_ave) + eval(itk_fri_ave) + eval(itk_sat_ave) + eval(itk_sun_ave);
            break;
            case 4:
            dayLineX = thurPointX;
                itkRestPred = eval(itk_mon) + eval(itk_tue) + eval(itk_wed) + eval(itk_thur_ave) + eval(itk_fri_ave) + eval(itk_sat_ave) + eval(itk_sun_ave);
            break;
            case 5:
            dayLineX = friPointX;
                itkRestPred = eval(itk_mon) + eval(itk_tue) + eval(itk_wed) + eval(itk_thur) + eval(itk_fri_ave) + eval(itk_sat_ave) + eval(itk_sun_ave);
            break;
            case 6:
            dayLineX = satPointX;
                itkRestPred = eval(itk_mon) + eval(itk_tue) + eval(itk_wed) + eval(itk_thur) + eval(itk_fri) + eval(itk_sat_ave) + eval(itk_sun_ave);
            break;
            case 7:
            dayLineX = sunPointX;
                itkRestPred = eval(itk_mon) + eval(itk_tue) + eval(itk_wed) + eval(itk_thur) + eval(itk_fri) + eval(itk_sat) +eval(itk_sun_ave);
            break;
        }

        $('.graph-canvas').append('<svg><line class="today" stroke="red" stroke-width="1" x1="' + dayLineX + '" y1="'+ areaOffsetY +'" x2="' + dayLineX + '" y2="'+ interceptOffsetY +'" /></svg>')
        budgetRestOfWeek = (0.7 * itkRestPred) - dsp_total;
        $('#remaining_budget').text('£' + (Math.floor(budgetRestOfWeek * 100)) / 100);

    }

    



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

    itkMonAveAfloat = (-(itk_mon_ave * scaleFactor)) + interceptOffsetY;
    itkTueAveAfloat = (-(itk_tue_ave * scaleFactor)) + interceptOffsetY;
    itkWedAveAfloat = (-(itk_wed_ave * scaleFactor)) + interceptOffsetY;
    itkThurAveAfloat = (-(itk_thur_ave * scaleFactor)) + interceptOffsetY;
    itkFriAveAfloat = (-(itk_fri_ave * scaleFactor)) + interceptOffsetY;
    itkSatAveAfloat = (-(itk_sat_ave * scaleFactor)) + interceptOffsetY;
    itkSunAveAfloat = (-(itk_sun_ave * scaleFactor)) + interceptOffsetY;

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

    //itk ave points
    $('.itk-mon-ave-point').attr('cx', monPointX);
    $('.itk-mon-ave-point').attr('cy', itkMonAveAfloat);

    $('.itk-tue-ave-point').attr('cx', tuePointX);
    $('.itk-tue-ave-point').attr('cy', itkTueAveAfloat);

    $('.itk-wed-ave-point').attr('cx', wedPointX);
    $('.itk-wed-ave-point').attr('cy', itkWedAveAfloat);

    $('.itk-thur-ave-point').attr('cx', thurPointX);
    $('.itk-thur-ave-point').attr('cy', itkThurAveAfloat);

    $('.itk-fri-ave-point').attr('cx', friPointX);
    $('.itk-fri-ave-point').attr('cy', itkFriAveAfloat);

    $('.itk-sat-ave-point').attr('cx', satPointX);
    $('.itk-sat-ave-point').attr('cy', itkSatAveAfloat);

    $('.itk-sun-ave-point').attr('cx', sunPointX);
    $('.itk-sun-ave-point').attr('cy', itkSunAveAfloat);

    //itk connecting lines
    $('.itk-mon-tue-ave').attr('x1', monPointX);
    $('.itk-mon-tue-ave').attr('x2', tuePointX);
    $('.itk-mon-tue-ave').attr('y1', itkMonAveAfloat);
    $('.itk-mon-tue-ave').attr('y2', itkTueAveAfloat);

    $('.itk-tue-wed-ave').attr('x1', tuePointX);
    $('.itk-tue-wed-ave').attr('x2', wedPointX);
    $('.itk-tue-wed-ave').attr('y1', itkTueAveAfloat);
    $('.itk-tue-wed-ave').attr('y2', itkWedAveAfloat);

    $('.itk-wed-thur-ave').attr('x1', wedPointX);
    $('.itk-wed-thur-ave').attr('x2', thurPointX);
    $('.itk-wed-thur-ave').attr('y1', itkWedAveAfloat);
    $('.itk-wed-thur-ave').attr('y2', itkThurAveAfloat);

    $('.itk-thur-fri-ave').attr('x1', thurPointX);
    $('.itk-thur-fri-ave').attr('x2', friPointX);
    $('.itk-thur-fri-ave').attr('y1', itkThurAveAfloat);
    $('.itk-thur-fri-ave').attr('y2', itkFriAveAfloat);

    $('.itk-fri-sat-ave').attr('x1', friPointX);
    $('.itk-fri-sat-ave').attr('x2', satPointX);
    $('.itk-fri-sat-ave').attr('y1', itkFriAveAfloat);
    $('.itk-fri-sat-ave').attr('y2', itkSatAveAfloat);

    $('.itk-sat-sun-ave').attr('x1', satPointX);
    $('.itk-sat-sun-ave').attr('x2', sunPointX);
    $('.itk-sat-sun-ave').attr('y1', itkSatAveAfloat);
    $('.itk-sat-sun-ave').attr('y2', itkSunAveAfloat);

}