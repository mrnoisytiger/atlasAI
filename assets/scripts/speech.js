// This is the central file for speech recognition. This file will handle the start, stop, and trigger of the command listener.

// Set Global Variables
// Set the initial speech recognition
var speech;
var command_listener_active=false; // Set the command_listener active state to false
var recog_killed=false; // Set the state of the initial recognition

// Function for the triggering of the command listener
function command_listener_trigger() {

    var trigger_phrase_array = ["hey atlas", "hey alice", "hey ashley", "hey angeles", "hey apollo"]; // Set the trigger phrase possibilities to account for listening inaccuracy
    var recog_results = document.getElementById("recog_results").innerHTML.trim().toLowerCase(); // Look at the voice recognition results without white space and capitalization
    var trigger_response = ["Yes?", "Hello Felix.", "What's up?", "What do you need?"]; // Set the response when trigger is met

    if (trigger_phrase_array.indexOf(recog_results) > -1) {     // Check if the results of the voice recognition match the trigger phrase
        // Trigger the command listener
        document.getElementById("intro_end").innerHTML = "Listener Triggered";
        command_listener_active=true; // Set the command listener status to active to end initial speech recog loop
        speech.stop(); // Stop the initial speech recog
        speech_synth(trigger_response[gen_rand_num(trigger_response.length)], startListener); // Trigger the speech respond using the speech synth connector and fire the command listener
    }
}

// Function to trigger the killing of the initial recognition for debug
function kill_phrase_trigger() {

    var kill_phrase_array = ["go dormant", "stop listening", "sleep now"];
    var recog_results = document.getElementById("recog_results").innerHTML.trim().toLowerCase();

    if (kill_phrase_array.indexOf(recog_results) > -1) {
        // Kill the initial recognition if necessary
        document.getElementById("intro_end").innerHTML = "Atlas dormant";
        recog_killed=true;
        speech.stop();
        play_audio("killed.mp3");
    }
}

// Function to set parameters for the initial recognition
function setRecog() {

    if (!('webkitSpeechRecognition' in window)) {
        alert("Browser Not Supported"); // Alert that the browser is not right
    } else {
        speech = new webkitSpeechRecognition();
        speech.continuous = true; // Allow continuous recognition for passive listening
        speech.interimResults = false; // Don't need interim results

        speech.onstart = function() {
            // For code to run when recognition starts
        }

        speech.onresult = function(event) { // To run as results are received

            for (var i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    document.getElementById("recog_results").innerHTML = event.results[i][0].transcript; // Set the fesults to an invisible div on the page once recognition is complete
                    kill_phrase_trigger();
                    command_listener_trigger(); // Call the command listener trigger check
                }

            }

        }

        speech.onend = function() {

            if (!command_listener_active && !recog_killed) { // If the command listener is not active or if the initial recognition has not been killed, keep listening
                speech.start();
            }

        }
    }
}

// Function to start the speech recognition with the bootstrapped settings
function startRecog() {
    setRecog();
    speech.start();
    $("#intro, #results_area, #command_area").fadeOut(200);
}
