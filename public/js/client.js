class Client {

	constructor() {

		terminal.write('Connecting to server...', 'gray');

		this.socket = new SimpleWebsocket('wss://rumorsmatrix.com:8080');

		this.socket.on('connect', this.onConnect );

		this.socket.on('data', function(data) {
			data = data.toString();

			if (data.charAt(0) === "{") {
				data = JSON.parse(data);
				client.onJSONData(data);

			} else {
				client.onStringData(data);
			}
		});

		this.socket.on('close', function() {
			clearInterval(this.ticker);
			terminal.write("Connection closed.", 'red');
		});

		this.socket.on('error', function(err) {
			console.log( err.message.toString() );
			if (err.message.includes('connection error to')) terminal.write("Error connecting to server.", "red");
		});

	}



	onConnect() {
		terminal.write('Connected.', 'green');
		this.ticker = setInterval( function() { client.tick();  } , 5000);
	}


	onJSONData(data) {
		console.log(data);

		if (data.say) {
			let output = '';
			if (data.say.admin === 1) output += '<span class="green tag">admin</span> ';
			output += '<span class="yellow">' + data.say.name + '</span> ';
			output += 'says, &quot;' + data.say.message + '&quot;';
			terminal.write(output);
		}

	}


	onStringData(data) {

		if (data === 'PING' || data === 'PONG') {
			console.log(data);
			return;
		}

		terminal.write(data.toString());
	}


	send(message) {
		if (this.socket.connected === true) {
			this.socket.send(message);
		} else {
			return false;
		}
	}


	tick() {
		this.send('PING');
	}

}
