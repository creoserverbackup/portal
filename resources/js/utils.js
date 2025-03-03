export const getMessageError = (error) => {
    let messageArray = []


    if (error?.response?.data?.message) {
        messageArray.push(error.response.data.message)
    }

    if (error?.response?.message) {
        messageArray.push(error.response.message)
    }

    // if (error?.message) {
    //     messageArray.push(error.message)
    // }

    if (error?.response?.data?.errors) {
        const errors = error.response.data.errors
        for (const itemKey in errors) {
            console.log("errors[itemKey]")
            console.log(errors[itemKey])
            // messageArray.push(`${itemKey}: ${errors[itemKey].join(' ')};`)
            messageArray.push(`${errors[itemKey].join(' ')}`)
        }
    }


    // if (error?.response?.data?.error) {
    //     messageArray.push(error.response.data.error)
    // }

    if (error?.errors) {
        const errors = error.errors
        for (const itemKey in errors) {
            messageArray.push(`${itemKey}: ${errors[itemKey].join('</br>')};`)
        }
    }

    if (messageArray.length === 0) {
        messageArray.push(`Error Message: ${error.message}`)
    }

    return messageArray.join(' ')
}

export const loadJsFile = (url, id, onLoadedCallback, defer, async) => {
    if (process.browser) {
        let test = document.getElementById(id);
        if (test) return onLoadedCallback();
        else {
            const script = document.createElement("script")
            script.src = url
            script.id = id
            script.onload = onLoadedCallback
            script.type = "text/javascript"
            script.defer = defer === true || typeof defer == "undefined"
            script.async = async === true || typeof async == "undefined"
            document.head.appendChild(script)
        }
    }
}