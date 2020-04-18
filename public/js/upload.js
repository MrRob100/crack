//song upload
//caution may contain jquery
// $('#song-file-input').change(function (e) {
//     console.log('upload changed');
//     $('#song-upload').submit();
// });

console.log('b', document.getElementById('song-upload-input'));


document.getElementById('song-upload-input').onchange = function() {
    console.log('c');
    document.getElementById('song-upload').submit();
}