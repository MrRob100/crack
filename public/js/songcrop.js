
function getCrop(event) {
    jQuery('.tunes').css('backgroundColor', '');
    event.style.backgroundColor = 'aqua';
    var whichCd = event.id.replace('tune-', '');
    var fetchCd = document.getElementById('cd-'+whichCd).innerHTML;
    document.getElementById('mydiv1').style.left = fetchCd == "" ? 0 : fetchCd + 'px';
}
