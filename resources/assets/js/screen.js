/* eslint-disable no-undef */
const $ = require('jquery');
const Manivelle = require('manivelle');

$(() => {
    const manivelleProps = (typeof MANIVELLE_PROPS !== 'undefined') ? MANIVELLE_PROPS : {};
    const openBrowserOnReady = (typeof START_VIEW !== 'undefined' && START_VIEW) ? START_VIEW : null;

    const options = {
        manivelleProps,
        openBrowserOnReady,
        touch: Modernizr.touchevents,
        screen: SCREEN,
        loaderUrl: LOADER_URLS,
        loaderBubblesPerPage: LOADER_BUBBLES_PER_PAGE,
        socketPubnub: {
            subscribe_key: PUBNUB_SUBSCRIBE_KEY,
            namespace: PUBNUB_NAMESPACE,
        },
        trackingId: TRACKING_ID,
        initialTime: INITIAL_TIME,
        timezone: TIMEZONE,
        locale: LOCALE,
        theme: THEME || null,
    };

    if (typeof PHRASES !== 'undefined') {
        options.phrases = PHRASES;
    }

    const manivelle = new Manivelle(options);

    manivelle.start();
    manivelle.render($('#app')[0]);

    const isElectron = window && window.process && window.process.type;

    // If we are in an Electron app, we notify the the process
    if (isElectron) {
        const ipcRenderer = window.require('electron').ipcRenderer;
        ipcRenderer.send('manivelle:started');
    }
});
