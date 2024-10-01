let countDate = new Date('june 1, 2024 00:00:00').getTime();

function CountDown(){
    let now = new Date().getTime();
    gap = countDate - now;

    let Sec = 1000;
    let Minute = Sec * 60;
    let Hour = Minute * 60;
    let day = Hour * 24;
    
     // Calculating the remaining days, hours, minutes, and seconds
    let d = Math.floor(gap / (day));
    let h = Math.floor(gap % (day) / (Hour));
    let m = Math.floor(gap % (Hour) / (Minute));
    let s = Math.floor(gap % (Hour) / (Sec));
     
     // Updating the HTML elements with the remaining time
    document.getElementById('day').innerText = d;
    document.getElementById('Hour').innerText = h;
    document.getElementById('Minute').innerText = m;
    document.getElementById('Sec').innerText = s;

   
}
// Setting up a timer to call the CountDown function every second
setInterval(function(){
    CountDown();
},1000)