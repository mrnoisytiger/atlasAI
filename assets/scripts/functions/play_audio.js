// Used to play audio bytes when events happen. Takes a filename and concatenates it to the path where audio bytes should be stored

function play_audio(filename) {
    var path = "assets/audio/" + filename;
    var audio = new Audio(path);
    audio.play();
}
