function connector_getWeather() {

    $.ajax({

        type: "POST",
        url: "https://dev.homedrop.tk/atlas/api/pick_api.php",
        data: {
            intentObject: intentObject,
        },
        success: function(data) {
            speech_synth(data, startRecog);
        }

    });

}
