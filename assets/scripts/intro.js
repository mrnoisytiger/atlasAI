$(document).ready(function() {

    // Hide all the intro first
    $("#intro h1").hide();

    $("#intro h1:first").delay(400).fadeIn(800).delay(1000).fadeOut(800); // First sentence

    $("#intro h1:nth-of-type(2)").delay(3000).fadeIn(800).delay(1000).fadeOut(800); // Second Sentence

    $("#intro h1:nth-of-type(3)").delay(5600).fadeIn(800).delay(200).queue(function() { // Third Sentence
        startRecog(); // Start the initial recognition after the intro sequence
    });

});
