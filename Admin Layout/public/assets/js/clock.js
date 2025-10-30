const HOURHAND = document.querySelector("#hour");
const MINUTEHAND = document.querySelector("#minute");
const SECONDHAND = document.querySelector("#second");
const GETREALTIME = document.querySelector("#dateAndTime");
const DATEDOTAY = document.getElementById("date")
var HOURHAND2 = document.querySelector("#Hours");
var MINUTEHAND2 = document.querySelector("#Minutes");
var SECONDHAND2 = document.querySelector("#Seconds");
var date = new Date();
console.log(date);
let hr = date.getHours();
let min = date.getMinutes();
let sec = date.getSeconds();
console.log("Hour: " + hr + " Minute: " + min + " Second: " + sec);

let hrPosition = (hr*360/12)+(min*(360/60)/12);
let minPosition = (min*360/60)+(sec*(360/60)/60);
let secPosition = sec*360/60;

function runTheClock() {
    var date = new Date();
    console.log(date);
    let hr = date.getHours();
    let min = date.getMinutes();
    let sec = date.getSeconds();
    let currentHour = function () {
        let realHour = 0;
        let realHourInPM = 0;
        if (hr >= 13) {
            realHour = hr - 12;
            realHourInPM = `${realHour}`;
        }
        else {
            realHour = hr;
            realHourInPM = `${realHour}`;
        }

        return realHourInPM;
    }
    hrPosition = hrPosition+(3/360);
    minPosition = minPosition+(6/60);
    secPosition = secPosition+6;

    HOURHAND.style.transform = "rotate(" + hrPosition + "deg)";
    MINUTEHAND.style.transform = "rotate(" + minPosition + "deg)";
    SECONDHAND.style.transform = "rotate(" + secPosition + "deg)";
    HOURHAND2.innerHTML = currentHour();
    MINUTEHAND2.innerHTML = min;
    SECONDHAND2.innerHTML = sec;

}

var interval = setInterval(runTheClock, 1000);