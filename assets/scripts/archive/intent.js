// This script is used to create the intent processor to process the command received in earlier functions. It shall create an Intent object with object type properties which include metadata like type, objects, and time

var intent;
var rawObject;
var rawAction;
var rawMoment;

// Begin known words definitions

var knownObjects = {
    weather:["weather", "temperature", "rain", "cloud", "cloudy", "warm", "cold", "snow", "blizzard", "hail", "overcast", "wet", "humidity", "pressure", "pollution", "clear", "wind"],
    email:["email", "send"],
    units:["fahrenheit", "celcius", "liters", "litres"],
};

var knownActions = {
    visual:["give me", "give", "show me", "show", "display", "on screen", "put", "pull up", "pull"],
    audio:["tell me", "tell", "read me", "read", "let's hear"],
    superaction:["what is", "is it", "how is", "when is", "how's", "what's", "when's", "what will", "what'll", "what was"],
};

var knownMoments = {
    month:["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"],
    relative:["day after tomorrow", "tomorrow", "next week", "next day", "next week", "next month", "next year", "in one day", "in two days", "in three days", "in four days"],
    vague:["soon", "close", "near future"],
};

// Begin Object Definitions

function Object(superObject, discreteObject) {
    this.superObject = superObject;
    this.discreteObject = discreteObject;
}

function Action(superAction, discreteAction) {
    this.superAction = superAction;
    this.discreteAction = discreteAction;
}

function Moment(superMoment, discreteMoment) {
    this.superMoment = superMoment;
    this.discreteMoment = discreteMoment;
}

// Begin Classification Declarations

function classifyObject() {

    var fullCommand = document.getElementById("command_results").innerHTML.trim().toLowerCase();
    var objectFound = false;

    for (var categories in knownObjects) {
        for (var i = 0; i < knownObjects[categories].length && !objectFound; i++) {
            if ( fullCommand.includes(knownObjects[categories][i]) ) {
                objectFound = true;
                rawObject = new Object();
                rawObject.superObject = categories;
                rawObject.discreteObject = knownObjects[categories][i];

                // --- DEBUG COMMANDS ---
                // alert(categories);
                alert(rawObject.discreteObject);
                alert(rawObject.superObject);
            }
        }
    }

}

function classifyAction() {

    var fullCommand = document.getElementById("command_results").innerHTML.trim().toLowerCase();
    var actionFound = false;

    for (var categories in knownActions) {
        for (var i = 0; i < knownActions[categories].length && !actionFound; i++) {
            if ( fullCommand.includes(knownActions[categories][i]) ) {
                actionFound = true;
                rawAction = new Action();
                rawAction.superAction = categories;
                rawAction.discreteAction = knownActions[categories][i];

                // --- DEBUG COMMANDS ---
                // alert(categories);
                alert(rawAction.discreteAction);
                alert(rawAction.superAction);
            }
        }
    }

}

function classifyMoment()  {

    var fullCommand = document.getElementById("command_results").innerHTML.trim().toLowerCase();
    var momentFound = false;

    for (var categories in knownMoments) {
        for (var i = 0; i < knownMoments[categories].length && !momentFound; i++) {
            if ( fullCommand.includes(knownMoments[categories][i]) ) {
                momentFoundFound = true;
                rawMoment = new Object();
                rawMoment.superMoment = categories;
                rawMoment.discreteMoment = knownMoment[categories][i];

                // --- DEBUG COMMANDS ---
                // alert(categories);
                alert(rawMoment.discreteObject);
                alert(rawMoment.superObject);
            }
        }
    }

}

function classifyIntent() {

}
