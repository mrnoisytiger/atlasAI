<?php

    function language_weather_conditions( $condition ) {

        switch( $condition ) {

            // Case 200's
            case "200":
                return "Looks like a thunderstorm with light rain";
                break;

            case "201":
                return "Looks like a thunderstorm with rain";
                break;

            case "202":
                return "Looks like a thunderstorm with heavy rain";
                break;

            case "210":
                return "There appears to be a light thunderstorm";
                break;

            case "211":
                return "There appears to be a thunderstorm";
                break;

            case "212":
                return "There appears to be a heavy thunderstorm";
                break;

            case "221":
                return "There appears to be a ragged thunderstorm";
                break;

            case "230":
                return "There appears to be a thunderstorm with a light drizzle";
                break;

            case "231":
                return "There appears to be a thunderstorm with some drizzle";
                break;

            case "232":
                return "There appears to be a thunderstorm with heavy drizzle";
                break;

            // Case 300's
            case "300":
                return "There is a light drizzle";
                break;

            case "301":
                return "There is a small drizzle";
                break;

            case "302":
                return "There is a heavy drizzle";
                break;

            case "310":
                return "There is a light drizzle rain";
                break;

            case "311":
                return "There is a some drizzle rain";
                break;

            case "312":
                return "There is a heavy drizzle rain";
                break;

            case "313":
                return "There is a shower with drizzle";
                break;

            case "314":
                return "There is a heavy shower with drizzle";
                break;

            case "321":
                return "There is a shower drizzle";
                break;

            // Case 500's
            case "500":
                return "There is some light rain";
                break;

            case "501":
                return "There is some moderate rain";
                break;

            case "502":
                return "There is some heavy rain";
                break;

            case "503":
                return "There is some very heavy rain";
                break;

            case "504":
                return "There is some extreme rain";
                break;

            case "511":
                return "There is some freezing rain";
                break;

            case "520":
                return "There is some light shower rain";
                break;

            case "521":
                return "There is some shower rain";
                break;

            case "522":
                return "There is some heavy shower rain";
                break;

            case "531":
                return "There is some ragged shower rain";
                break;

            // Case 600's
            case "600":
                return "There appears to be some light snow";
                break;

            case "601":
                return "There appears to be some snow";
                break;

            case "602":
                return "There appears to be some heavy snow";
                break;

            case "611":
                return "There appears to be some sleet";
                break;

            case "612":
                return "There appears to be some shower sleet";
                break;

            case "615":
                return "There appears to be some light rain and snow";
                break;

            case "616":
                return "There appears to be some rain and snow";
                break;

            case "620":
                return "There appears to be some light shower snow";
                break;

            case "621":
                return "There appears to be some shower snow";
                break;

            case "622":
                return "There appears to be some heavy shower snow";
                break;

            // Case 700's
            case "701":
                return "The atmosphere is misty";
                break;

            case "711":
                return "There is a lot of smoke in the air";
                break;

            case "721":
                return "The air is quite hazy";
                break;

            case "731":
                return "There are is some sand and dust in the air";
                break;

            case "741":
                return "The air is quite foggy";
                break;

            case "761":
                return "There is dust in the air";
                break;

            // Case 800's
            case "800":
                return "The sky is very clear";
                break;

            case "801":
                return "There are a few clouds in the sky";
                break;

            case "802":
                return "There are some scattered clouds";
                break;

            case "803":
                return "There are some broken clouds in the sky";
                break;

            case "804":
                return "The sky is quite overcast";
                break;


            default:
                return "Unidentified condition";
                break;

        }

    }

?>
