function connector_sendToServer() {

    $.ajax({

        type: "POST",
        url: "https://demo.ide.homedrop.org/atlas/api/pick_api.php", // SWITCH THIS TO YOUR CORRECT URL
        data: {
            intentObject: intentObject,
        },
        success: function(data) {
            speech_synth(data, startRecog);
        }

    });

}
