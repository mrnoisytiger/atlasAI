// This script handles the processing of the command listener once triggered by the initial speech recognition. It takes the command and places it in a Shadow DOM (to do) for process later by the natural language processor which fires on the recognition end.

var command; // Initialize the command variable as a global so we can set it in one function and use it in another. This is simply for easier book-keeping and could be combined into one function if needed.

function setCommandListener() {

    command = new webkitSpeechRecognition();
    command.continuous = false; // Do not use continuous listening as we only need one single phrase
    command.interimResults = false; // Don't need interim results

    command.onstart = function() {
        // For code to run when recognition starts. For beeps if needed
    }

    command.onresult = function(event) { // To run as results are received

        for (var i = event.resultIndex; i < event.results.length; ++i) {
            if (event.results[i].isFinal) {
                document.getElementById("command_results").innerHTML = event.results[i][0].transcript; // Append results to a shadow DOM for debug purposes
            }
        }

    }

    command.onend = function() {
        play_audio('stop.mp3')
        command_listener_active=false;
        document.getElementById("intro_end").innerHTML = "Command Received"; // Acknowledge the command has been received
        // Trigger Natural Language Processor here
        //classifyObject();
        //classifyAction();
        sendToProcess();
    }

}

function startListener() {
    setCommandListener();
    play_audio('start.mp3')
    command.start();
}
