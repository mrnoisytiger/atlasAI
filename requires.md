# Functional Dependencies for each Script

---

## Core Scripts

Provides the core functionality of Atlas with regards to logic and events

### Intro.js
    - Requires setRecog();
        - Found in speech.js
        - Allows the introduction to start the background speech recognition
### Speech.js
    - Requires speech_synth();
        - Found in functions/speech_synth.js
        - Allows the voice synthesis
        - Required after the command trigger
    - Requires startListener();
        - Found in cmd_listener.js
        - Used to start listening for actual commands
### cmd_listener.js
    - Contains setListener();
        - Requires play_audio.js to play sound byte when listener begins
        - Starts parameters for the command listener and initializes the variable for use
    - Contains startListener();
        - Requires setListener() set the parameters of the listen
        - Begins the speech recognition for the command phase

---

## Functions

Provides extended functionality that is self-contained to itself or to other functions only. Includes things that provide behavior or user interaction

### Speech_synth.js
    - No Dependencies
    - Provides the voice synthesis for Atlas
        - Executes a named function as a callback after the speech synthesis is complete to allow continuous interactions
    - Usage
        - speech_synth("Your message here", callback);  
### play_audio.js
    - No Dependencies
    - Used to play a simple audio byte after taking the filename and concatenating to the full file path
    - Usage
        - play_audio("filename");
        
---

## Connectors

Provides extended functionality which calls upon other third-party services. Used to execute various commands given the context

---

The order of script inclusion should always start from the least dependent to the most dependent. In this situation, functions should be included first, followed by connectors, and lastly core scripts. This is because connectors will likely have to act using functions in order to properly respond to user input. 

For example, when asked to audibly give the weather, the weather connector must call upon the Speech Synthesis function to enumerate the text. If asked to visually provide the temperature, the same connector must use the Display function to place the information on the display. 

As such, functions should be built as generally as possible, allowing the connector to determine what best should be done in the given scenario. The Display function should let the connector set not only the contents to be displayed, but also the location and depth of the information. Some parameters can be set by the function to tighten down customizability, such as in Speech Synthesis voice speed, pitch, and volume to allow for simpler development and a more tight user experience. 