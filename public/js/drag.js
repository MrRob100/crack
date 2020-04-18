// Make the DIV element draggable:
dragElement(document.getElementById("mydiv1"));
dragElement(document.getElementById("mydiv2"));

function dragElement(elmnt) {
    var pos1 = 0,
        pos2 = 0,
        pos3 = 0,
        pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(
            elmnt.id + "header"
        ).onmousedown = dragMouseDown;
    } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos3 = e.clientX;
        // set the element's new position:

        var startx = document.getElementById("mydiv1").offsetLeft;
        var endx = document.getElementById("mydiv2").offsetLeft;

        if (endx >= startx) {
            elmnt.style.left = elmnt.offsetLeft - pos1 + "px";
        } else {
            if (elmnt.id == "mydiv2") {
                elmnt.style.left = elmnt.offsetLeft + 1 + "px";
            }
            if (elmnt.id == "mydiv1") {
                elmnt.style.left = elmnt.offsetLeft - 1 + "px";
            }
        }
    }

    function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }
}
