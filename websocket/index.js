const WebSocket = require('ws');
const fs = require('fs');
const jwt = require('jsonwebtoken');
const dotenv = require('dotenv');

dotenv.config();

const server = new WebSocket.Server({port: 8000});
const jwtkey = fs.readFileSync(process.env.WS_JWT_PUBLIC_KEY);

server.on('connection', (ws, request) => {
    console.log('connected: %s', request.connection.remoteAddress);

    ws.on('message', message => {
        const data = JSON.parse(message);

        if (data.type && data.type === 'auth') {
            try {
                const token = jwt.verify(data.token, jwtkey, {algorithms: ['RS256']});
                console.log('user_id: %s', token.sub);
                ws.user_id = token.sub;
            } catch (e) {
                console.error(e);
            }
        }
    });
});