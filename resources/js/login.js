let texts = window.promoTexts;

let _elem = document.getElementById('promo-344');
let startedAt = Date.now();
let duration = 7000;

let last = Math.floor(Math.random() * texts.length);

async function animation(showOrHide) {

    if (_elem) {
        if (showOrHide) { // show
            (function fadeIn(el_ = _elem) {
                (el_.style.opacity = String(Number(el_.style.opacity) + .05)) >= 1 ? el_.style.opacity = "1" : setTimeout(fadeIn, 25) // ~1s
            })();

            while (_elem.style.opacity !== "1")
                await new Promise(resolve => setTimeout(resolve, 1000));
        } else { // hide and change
            (function fadeOut(el_ = _elem) {
                (el_.style.opacity = String(Number(el_.style.opacity) - .05)) <= 0 ? el_.style.opacity = "0" : setTimeout(fadeOut, 25) // ~1s
            })();

            while (_elem.style.opacity !== "0")
                await new Promise(resolve => setTimeout(resolve, 1000));

            let rand = last;

            if (texts.length > 1) {
                while (rand === last)
                    rand = await Math.floor(Math.random() * texts.length);
            }

            last = rand;
            _elem.innerText = texts[rand];
        }
    }
}

function start() {
    startedAt = Date.now();
    animation(0).then(() => {
        requestAnimationFrame(step);
    });
}

function step() {
    const elapsed = Date.now() - startedAt;
    const playback = elapsed / duration;

    animation(1).then(() => {
        if (playback > 0 && playback < 1)
            requestAnimationFrame(step);
        else
            start();
    });
}

start();
