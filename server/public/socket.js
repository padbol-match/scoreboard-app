var socket  = new WebSocket('ws://scoreboard.padbol.org:3001');

// Define the 
var message = "Browser Esto es un gran mensaje para mandar";

function transmitMessage(message) {
    socket.send( message );
}

socket.onmessage = function(e) {
    console.log( e.data );
}