function connector_onscreenDisplay() {

    var displayAction = intentObject.result.parameters.displayAction;
    var displayObject = intentObject.result.parameters.displayObject;

    switch(displayObject) {

        case "whiteboard":
            connector_whiteboard(displayAction);
            break;

        default:
            speech_synth("No such object", startRecog);
            break;

    }

}
