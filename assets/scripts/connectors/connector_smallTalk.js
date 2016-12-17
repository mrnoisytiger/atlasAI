function connector_smallTalk() {

    var smallTalk_text = intentObject.result.fulfillment.speech;

    speech_synth(smallTalk_text, startRecog);

}
