<template>
  <div class="contr slider-house">
    <div class="marker marker-start" :id='"div-start-"+setting' :style="'left: '+ canvasLeft + 'px'">
      <div class="markerheader" :id='"div-start-"+setting+"-header"'>S</div>
    </div>
    <div class="marker marker-end" :id='"div-end-"+setting'>
      <div class="markerheader" :id='"div-end-"+setting+"-header"'>E</div>
    </div>
  </div>
</template>
<script>

export default {

  props: ['setting', 'canvasWidth', 'canvasLeft', 'name'],

  data: function() {
    return {
      end: 0,
    }
  },

  //when component has mounted
  mounted() {

    var isso = this;

    var nonHeadStart = document.getElementById("div-start-" + isso.setting); 
    var nonHeadEnd = document.getElementById("div-end-" + isso.setting); 

    nonHeadEnd.style.left = "calc(100% - 20px)";

    // nonHeadEnd.style.left = this.canvasWidth; 

    // Make the DIV element draggable:
    dragElement(nonHeadStart);
    dragElement(nonHeadEnd);

    function dragElement(elmnt) {
      var pos1 = 0,
        pos2 = 0,
        pos3 = 0,
        pos4 = 0;
      if (document.getElementById(elmnt.id + "-header")) {
        // if present, the header is where you move the DIV from:
        var mHead = document.getElementById(elmnt.id + "-header");
        mHead.onmousedown = dragMouseDown;
        mHead.ontouchstart = dragMouseDown;
      } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
      }

      //first touch
      function dragMouseDown(e) {
        
        e = e || window.event;
        if (e.touches) {
          pos3 = e.touches[0].clientX;
          document.ontouchend = closeDragElement;
          document.ontouchmove = elementDrag;
        } else {
          e.preventDefault();
          pos3 = e.clientX;
          document.onmouseup = closeDragElement;
          document.onmousemove = elementDrag;
          document.ontouchend = closeDragElement;
          document.ontouchmove = elementDrag;
        }
      }

      function elementDrag(e) {
        e = e || window.event;

        if (e.touches) {
          pos1 = pos3 - e.touches[0].clientX;
          pos3 = e.touches[0].clientX;
        } else {
          e.preventDefault();
          pos1 = pos3 - e.clientX;
          pos3 = e.clientX;
        }
        var startx = nonHeadStart.offsetLeft;
        var endx = nonHeadEnd.offsetLeft;

        if (startx < 0 && elmnt.id == "div-start-" + isso.setting) {
            elmnt.style.left = "0px";
        }

        if (endx > (window.innerWidth - 20) && elmnt.id == "div-end-" + isso.setting) {
            elmnt.style.left = "calc(100% - 20px)";
        }

        if (endx >= startx) {
          var posCalced = ((elmnt.offsetLeft - pos1) / window.innerWidth) * 100; 
          elmnt.style.left = elmnt.offsetLeft - pos1
        } else {
          if (elmnt.id == "div-end-" + isso.setting) {
            //end
            elmnt.style.left = elmnt.offsetLeft + 1 + "px"; 
          }
          if (elmnt.id == "div-start-" + isso.setting) {
            //start
            elmnt.style.left = elmnt.offsetLeft - 1 + "px"; 
          }
        }
      }

      function closeDragElement(e) {

        //telling parent that drag is starting
        isso.$emit('clicked');

        elmnt.style.left = ((elmnt.offsetLeft - pos1) / window.innerWidth) * 100 + "%";

        if (e.toElement) {
          if (e.toElement.id == "div-start-" + isso.setting + "-header") {
            isso.setMarkers('startScale', e.clientX);
          }

          if (e.toElement.id == "div-end-" + isso.setting + "-header") {
            isso.setMarkers('endScale', e.clientX);
          }
        } else {
          if (e.target.id == "div-start-" + isso.setting + "-header") {
            isso.setMarkers('startScale', e.pageX);
          }

          if (e.target.id == "div-end-" + isso.setting + "-header") {
            isso.setMarkers('endScale', e.pageX);
          }
        }



        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
        document.ontouchend = null;
        document.ontouchmove = null;
      }
    }
  },
  methods: {
    setMarkers: function(which, ol) {
      var isso = this;
      var request = new XMLHttpRequest();

      var value = ol / window.innerWidth;

      if (window.location.hostname == 'localhost') {
        request.open('GET', '/crack/public/set?which=' + which + "&position=" + isso.name + "&value=" + value);
      
      } else {
        request.open('GET', '/set?which=' + which + "&position=" + isso.name + "&value=" + value);
      }
      
      request.send();
      // request.onload = function () {

      //   }
      }
  }
};
</script>
<style>
.slider-house {
  background-color: aqua;
  padding-left: 0;
}

.marker {
  width:20px;
  height: 40px;
  position: absolute;
  z-index: 9;
  /* background-color: #f1f1f1; */
  border: 2px solid #d3d3d3;
  text-align: center;
}


.marker-start {
  border-right: none;
}

.marker-end {
  border-left: none;
}

.markerheader {
  color: #d3d3d3;
  height: 40px;
  cursor: move;
  z-index: 10;
}

</style>