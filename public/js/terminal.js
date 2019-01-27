class Terminal {

	constructor(container_id) {
		this.container = document.getElementById(container_id);
		this.container.addEventListener('click', this);
		this.handleEvent();
	}


	write(message, type) {
		let element = document.createElement("p");
		element.innerHTML = message;
		if (type !== undefined) element.classList.add(type);

		this.container.appendChild(element);
		window.scrollTo(0, document.body.scrollHeight);
	}


	paramsToArray(params) {
		let arr = {};
		let pairs = params.substring(params.indexOf('?') + 1).split('&');
		for (let i = 0; i < pairs.length; i++) {
			if(!pairs[i]) continue;
			let pair = pairs[i].split('=');
			arr[pair[0]] = pair[1];
		}
		return arr;
	}


	handleEvent(event = undefined) {
		if (event === undefined) return;
		if (event.target.tagName === "A") {
			event.preventDefault();
			let arr = this.paramsToArray(event.target.search);
			client.send(JSON.stringify(arr));
		}
	}


}