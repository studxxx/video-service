const WebSocket = require('ws');

const server = new WebSocket.Server({port: 8000});

server.on('connection', (ws, request) => {
    console.log('connected: %s', request.connection.remoteAddress);

    ws.on('message', message => console.log('received: %s', message));
});