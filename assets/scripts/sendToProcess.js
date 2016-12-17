var intentObject;

function sendToProcess() {

    // Set Environmental Variables
    var apiKey = "986265fa25434f399645dd9b273c9c1a";
    var query = document.getElementById("command_results").innerHTML;
    var baseURL = "https://api.api.ai/v1/";
    var queryVersion = "query?v=20161117";
    var sessionId = genRandString();

    // Set Configuration Variables
    var timezone = "America/Los_Angeles";
    var lang = "en";

    $.ajax({

        type: "POST",
        url: baseURL + queryVersion,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
            "Authorization": "Bearer " + apiKey
        },
        data: JSON.stringify({ query: query, lang: lang, timezone: timezone, sessionId: sessionId}),

        success: function(data) {
            intentObject = data;
            pickConnector();
        },

        error: function(data) {
            speech_synth("Sorry, we are having some issues right now!");
        }

    });

}
/*
function pickConnector() {

    var connectorType = intentObject.result.action;

    switch(connectorType) {

        case "smalltalk.person":
            connector_smallTalk();
            break;

        case "getWeather":
            connector_getWeather();
            break;

        default:
            speech_synth("No connector for this action yet!");
            break;
    }

}
*/
