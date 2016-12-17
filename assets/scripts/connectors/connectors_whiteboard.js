function connector_whiteboard(action) {

    if ( action == "open" ) {

        $("#whiteboard").fadeIn(300);
        startRecog();

    } else {

        $("#whiteboard").fadeOut(300);
        startRecog();

    }

}
