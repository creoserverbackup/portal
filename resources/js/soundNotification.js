import BufferLoader from "./BufferLoader";

export default class {
    constructor(soundPath) {
        this.soundPath = soundPath
        this.bufferData = null
    }

    init() {
        // Fix up prefixing
        window.AudioContext = window.AudioContext || window.webkitAudioContext
        this.context = new AudioContext()
        this.bufferLoader = new BufferLoader(
            this.context,
            [
                this.soundPath
            ],
            (bufferList) => {
                this.bufferData = bufferList[0]
            }
        )
        this.gainNode = null
        this.volume = 100

        this.bufferLoader.load()
    }

    play() {
        if (!this.bufferData) {
            return
        }
        if (!this.context.createGain)
            this.context.createGain = this.context.createGainNode
        this.gainNode = this.context.createGain()
        var source = this.context.createBufferSource()
        source.buffer = this.bufferData

        // Connect source to a gain node
        source.connect(this.gainNode)
        // Connect gain node to destination
        this.gainNode.connect(this.context.destination)
        // Start playback in a loop
        source.loop = false
        if (!source.start) {
            source.start = source.noteOn
        }
        this.changeVolume()
        source.start(0)
        this.source = source
    }

    stop(){
        if (!this.source.stop)
            this.source.stop = source.noteOff;
        this.source.stop(0);
    }

    /**
     * @param {Number} int - value: 0-100
     */
    setVolume(int) {
        this.volume = int
    }

    changeVolume() {
        var fraction = this.volume / 100;
        // Let's use an x*x curve (x-squared) since simple linear (x) does not
        // sound as good.

        this.gainNode.gain.value = fraction * fraction;
    }
}
