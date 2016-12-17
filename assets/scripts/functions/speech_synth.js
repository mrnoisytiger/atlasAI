// This script handles the interactive speech synthesis component. The function takes text to be synthesized, as well as a callback function to execute when the text has completed.

function speech_synth(text, callback) {

    var synth_message = new SpeechSynthesisUtterance();
    var chosen_voice = "Daniel";

    synth_message.voice = speechSynthesis.getVoices().filter(function(voice) { return voice.name == chosen_voice; })[0]
    synth_message.volume = 1; // From 0 to 1
    synth_message.rate = 1.1; // From 0.1 to 10
    synth_message.pitch = 1.1; // From 0 to 2
    synth_message.lang = 'en-GB';
    synth_message.text = text;

    window.speechSynthesis.speak(synth_message);

    if (callback !== undefined) {
        synth_message.onend = function() { // When the message is complete, fire the callback function
            callback(); // Fire callback function whatever it may be
        }
    }

}
