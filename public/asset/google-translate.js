// const googleTranslateConfig = {lang: "ru",};
//
// console.log("connect lang")
//
// function TranslateInit() {
//
//     if (googleTranslateConfig.langFirstVisit && !localStorage.getItem('googtrans')) {
//         TranslateCookieHandler("/auto/" + googleTranslateConfig.langFirstVisit);
//     }
//     let code = TranslateGetCode();
//     if (document.querySelector('[data-google-lang="' + code + '"]') !== null) {
//         document.querySelector('[data-google-lang="' + code + '"]').classList.add('language__img_active');
//     }
//     if (code == googleTranslateConfig.lang) {
//         TranslateCookieHandler(null, googleTranslateConfig.domain);
//     }
//
//     console.log("code code code")
//     console.log(code)
//     console.log(" googleTranslateConfig.lang googleTranslateConfig.lang googleTranslateConfig.lang")
//     console.log(googleTranslateConfig.lang)
//
//     new google.translate.TranslateElement({pageLanguage: code});
//     TranslateEventHandler('click', '[data-google-lang]', function (e) {
//         TranslateCookieHandler("/" + code + "/" + e.getAttribute("data-google-lang"), googleTranslateConfig.domain);
//         window.location.reload();
//     });
// }
//
// function TranslateGetCode() {
//
//     console.log("googtrans - 33333 - googtrans")
//     console.log(localStorage.getItem('googtrans'))
//
//     let lang = (localStorage.getItem('googtrans') != undefined && localStorage.getItem('googtrans') != "null") ? localStorage.getItem('googtrans') : googleTranslateConfig.lang;
//     return lang.match(/(?!^\/)[^\/]*$/gm)[0];
// }
//
// function TranslateCookieHandler(val, domain) {
//
//     console.log("val val val val")
//     console.log(val)
//
//     localStorage.setItem('googtrans', val)
//     // Cookies.set("googtrans", val, {domain: "." + document.domain,});
//
//
//     console.log("googtrans - googtrans - googtrans")
//     console.log(localStorage.getItem('googtrans'))
//
//
//     if (domain == "undefined") return;
//
//     localStorage.setItem("googtrans", val, {domain: domain,});
//     localStorage.setItem("googtrans", val, {domain: "." + domain,});
// }
//
// function TranslateEventHandler(event, selector, handler) {
//     document.addEventListener(event, function (e) {
//         let el = e.target.closest(selector);
//         if (el) handler(el);
//     });
// }
//


const googleTranslateConfig = {lang: "nl",};

function TranslateInit() {

    // console.log(" googleTranslateConfig googleTranslateConfig")
    // console.log(googleTranslateConfig)

    if (googleTranslateConfig.langFirstVisit && !getCookie('googtrans')) {
        TranslateCookieHandler("/auto/" + googleTranslateConfig.langFirstVisit);
    }

    let code = TranslateGetCode();
    if (document.querySelector('[data-google-lang="' + code + '"]') !== null) {
        document.querySelector('[data-google-lang="' + code + '"]').classList.add('language__img_active');
    }

    // console.log(" googleTranslateConfig 222222 googleTranslateConfig")
    // console.log(googleTranslateConfig)
    // console.log("code code code")
    // console.log(code)


    if (code == googleTranslateConfig.lang) {
        TranslateCookieHandler(null, googleTranslateConfig.domain);
    }

    new google.translate.TranslateElement({pageLanguage: googleTranslateConfig.lang,});

    TranslateEventHandler('click', '[data-google-lang]', function (e) {
        TranslateCookieHandler("/" + googleTranslateConfig.lang + "/" + e.getAttribute("data-google-lang"), googleTranslateConfig.domain);
        // setTimeout(this.reloadPage, Math.random() * 60500)
        window.location.reload();
    });
}

// function reloadPage()
// {
//     window.location.reload();
// }

function TranslateGetCode() {
    let lang = (getCookie('googtrans') != undefined && getCookie('googtrans') != "null") ? getCookie('googtrans') : googleTranslateConfig.lang;
    return lang.match(/(?!^\/)[^\/]*$/gm)[0];
}

function TranslateCookieHandler(val, domain) {
    //
    // console.log(" val val val")
    // console.log(val)
    // console.log("domain domain")
    // console.log(domain)

    setCookie('googtrans', val);
    // setCookie("googtrans", val, {domain: "." + document.domain,});

    // console.log(" ----  888 999 888 ---- ")
    // console.log(getCookie('googtrans'))

    if (domain == "undefined") return;
    setCookie("googtrans", val, {domain: domain,});
    setCookie("googtrans", val, {domain: "." + domain,});
}

function TranslateEventHandler(event, selector, handler) {
    document.addEventListener(event, function (e) {
        let el = e.target.closest(selector);
        if (el) handler(el);
    });
}

// Set Cookie
function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

// setCookie('name', 'value', {'path':'/', 'expires':3604324334});

// Get cookie
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

// getCookie('name')

// Del cookie
function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    })
}