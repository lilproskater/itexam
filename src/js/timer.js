var secondsLeft;
var intervalHandle;

function tick() {
    var timeDisplay = document.getElementById("time");
    var hours = secondsLeft / 3600 ^ 0;
    var min = (secondsLeft - hours * 3600) / 60 ^ 0;
    var sec = secondsLeft - hours * 3600 - min * 60;
    
    if (hours < 10)
    	hours = "0" + hours;
    hours += ":";
    if (hours === "00:")
    	hours = "";

    if (min < 10)
    	min = "0" + min;
    min += ":";
    if (min === "00:" && hours === "") 
    	min = "";

    if (sec < 10)
    	sec = "0" + sec;

    var message = hours + min + sec;
    
    if (secondsLeft === 0)
    	message = "Время вышло!";

    timeDisplay.innerHTML = message;

    if (secondsLeft > 0) secondsLeft --;
    sessionStorage.setItem("secondsLeft", secondsLeft);
}

function StartCountDown() {
	intervalHandle = setInterval(tick, 1000);
}

window.onload = function () {
	secondsLeft = sessionStorage.getItem("secondsLeft");
	if (secondsLeft === "0" || secondsLeft === null || secondsLeft === "null")
		secondsLeft =  45 * 60;
	StartCountDown();
}