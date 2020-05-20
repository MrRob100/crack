

<button id="playpause">Play</button>
<input class="phasecontrol" type="range" min="0" max="1" step="0.1" value="0">

<script>

const context = new (window.AudioContext || window.webkitAudioContext)()
const loopUrl = '/crack/public/storage/data/bunny.mp3'
// const loopUrl = 'https://cdn.glitch.com/180b93e4-25d3-48a6-80e0-f3490ea23253%2Fdrum%20loop.wav?1534877297684'

class Phaser {
  constructor(options = {}) {
    const { stages, context } = options
    this.stages = stages || 12
    this.context = context

    this.buildChain()
      
    const min = 100
    const max = 1000
    let centerFrequency = min
    setInterval(() => {
      this.filters.forEach((filter, i) => {  
        if (centerFrequency > max) {
          centerFrequency = min
        }

        filter.frequency.value = centerFrequency + i * ((max - min) / this.filters.length)
        centerFrequency += 1
      })
    }, 20)
    
    return {
      input: this.input,
      output: this.output
    }
  }

  /*
   * Our chain looks like so:
   * 
   *  +-------+                                      +--------+
   *  | input |-----------------------------------+--| output |
   *  +-------+                                   |  +--------+
   *      |                                       |            
   *      |  +-----------------+   +-----------+  |            
   *      +--| all-pass filter |-+-| depth VCA |--+            
   *      |  +-----------------+ | +-----------+               
   *      |                      |                             
   *      |  +--------------+    |                             
   *      +--| feedback VCA |----+                             
   *         +--------------+                                  
   */
  buildChain() {

    const { filters, input: filterInput, output: filterOutput } = this.createFilterChain(this.stages)
    this.filters = filters

    this.input = context.createGain()
    this.input.connect(filterInput)


    this.feedbackVca = context.createGain()
    this.feedbackVca.gain.value = 0.7
    filterOutput.connect(this.feedbackVca)
    this.feedbackVca.connect(filterInput)

    this.depthVca = context.createGain()
    this.depthVca.gain.value = 0;

    var slider = document.getElementsByClassName('phasecontrol')[0];

    var that = this;

    slider.oninput = function() {
        that.depthVca.gain.value = slider.value;
    }

    // this.depthVca.gain.value = 0.6
    filterOutput.connect(this.depthVca)

    this.output = context.createGain()
    this.depthVca.connect(this.output)

    this.input.connect(this.output)
  }

  createFilterChain(stages) {
    const filters = []
    
    for (let i = 0; i < stages; i++) {
      const filter = context.createBiquadFilter()
      filter.type = 'allpass'
      filters.push(filter)
      
      if (i > 0) {
        filter.connect(filters[i-1])
      }
    }
    
    return {
      filters,
      input: filters[filters.length - 1],
      output: filters[0]
    }
  }
}

const volume = context.createGain()
volume.gain.value = 0

const source = context.createBufferSource()
source.loop = true
source.start(0)

let playing = false
document.getElementById('playpause').addEventListener('click', async (event) => {
  // Hopefully bypass Chrome's absurd autoplay policy 😭
  // https://developers.google.com/web/updates/2017/09/autoplay-policy-changes#webaudio
  if (context.state !== 'running') {
    await context.resume()
  }

  if (playing) {
    event.currentTarget.innerText = 'Play'
    // source.stop()
    volume.gain.value = 0
    playing = false
  } else {
    event.currentTarget.innerText = 'Pause'
    // source.start(0)
    volume.gain.value = 1
    playing = true
  }
})

// fetch an external audio file to loop and decode it into a buffer!
async function getLoop(url) {
  const response = await fetch(url)
  const data = await response.arrayBuffer()
  return context.decodeAudioData(data)
  
}

async function main() {
  const buffer = await getLoop(loopUrl)
  source.buffer = buffer
  
  const { input, output } = new Phaser()

  const p = new Phaser();
  console.log('p', p);

  console.log('in', input);
  console.log('v', volume);

  source.connect(input)
  output.connect(volume)
  volume.connect(context.destination)
}

main()

</script>