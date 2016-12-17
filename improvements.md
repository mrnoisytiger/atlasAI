# List of Improvements to Do

## Intent Classifier
### High Priority
    -
### Medium Priority
    - Move the known items list to an external database to improve performance and to clean up code on large data sets
    - Make functional wrapper to reduce repetitive code
        - May not be necessary
### Low Priority
    - Reduce one-by-one array traversals
    - Skip classifying objects individually and moe to one larger function
    - Remove debug commands

---

## Command Listener
### High Priority
    - 
### Medium Priority
    - Move the setup and trigger into one function since it doesn't REALLY need to be two seperate ones
        - May need it to play the startup sounds
### Low Priority
    - Replace sounds with custom sounds
    - Remove debug aspects

---

## Initial Recognition
### High Priority
    - Consider what to do about the command killer and restarting it if/when necessary
        - Use keystroke sequence ?
        - May not be necessary in production
### Medium Priority
    - Change sounds
### Low Priority
    - Optimize code for efficiency
    - Improve speech recognition with custom parameters
