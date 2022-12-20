#!/bin/bash

# Install the javascript dependencies for the project.
# This script is intended to be run from the root of the project.
echo "Starting install of javascript project dependencies..."
JSDIR="src/public/js/lib"
sudo mkdir -p "$JSDIR"

echo "Installing jquery..."
sudo curl -sS -o "$JSDIR/jquery.min.js" https://code.jquery.com/jquery-3.6.0.min.js

echo "Installing chartjs..."
sudo curl -sS -o "$JSDIR/chart.min.js" https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js

echo "Installing randomColor..."
sudo curl -sS -o "$JSDIR/randomColor.js" https://raw.githubusercontent.com/davidmerfield/randomColor/master/randomColor.js


echo "Done installing javascript project dependencies."
exit 0