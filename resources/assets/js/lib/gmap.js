const API_KEY = typeof GOOGLE_API_KEY !== 'undefined' ? GOOGLE_API_KEY : 'AIzaSyDYuKJ4N-IxhsZHSaiBFwzTE4KTJrE17Js';

function loadGMap(apiKey = API_KEY) {
    return new Promise((resolve) => {
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
            resolve();
            return;
        }

        const callbackName = `gmapLoaded_${(new Date()).getTime()}`;
        window[callbackName] = () => {
            window[callbackName] = null;
            resolve();
        };

        const url = `https://maps.googleapis.com/maps/api/js?key=${apiKey || API_KEY}&callback=${callbackName}`;
        const script = document.createElement('script');
        script.src = url;
        script.type = 'text/javascript';
        document.getElementsByTagName('head')[0].appendChild(script);
    });
}

function geocode(address) {
    return new Promise((resolve, reject) => {
        const geocoder = new google.maps.Geocoder();

        geocoder.geocode({
            address,
        }, (results, status) => {
            if (status === google.maps.GeocoderStatus.OK) {
                resolve(results[0].geometry.location);
            } else {
                reject(status);
            }
        });
    });
}

module.exports = {
    API_KEY,
    loadGMap,
    geocode,
};
