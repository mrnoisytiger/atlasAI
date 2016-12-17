function pickConnector() {

    var action = intentObject.result.action;

    // Standardize some actions
    if ( action.match("smalltalk*") ) {
        action = "smalltalk";
    }

    switch(action) {

        case "smalltalk":
            connector_smallTalk();
            break;

        case "getWeather":
            connector_getWeather();
            break;

        case "onscreenDisplay":
            connector_onscreenDisplay();
            break;

        default:
            speech_synth("No connector for this action yet!", startRecog);
            break;
    }

}
