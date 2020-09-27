export default {

    playing() {
        
    },

    stopped(pos) {
        var toBlur = document.getElementsByClassName("to-blur");
        var box = document.getElementsByClassName("control-box")[0];
        var stop = document.getElementById("stbutton-" + pos);
        var close = document.getElementById("modal-close-" + pos);
        var body = document.querySelector("body");

        body.style.position = "relative";
        body.style.overflowY = "scroll";

        for (let item of toBlur) {
            item.style.filter = "none";
            item.style.cursor = "pointer";
        }

        stop.style.display = "none";
        close.style.display = "none";
        box.style.display = "none";   
    }

}