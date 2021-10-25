/**
 * 
 * @param {*} now 
 * @returns 
 */
function displayTimeNow(now = new Date())
{
    // Format Hours
    let hours = now.getHours().toString();
    hours = hours.length == 1 ? 0+hours : hours;
    // Format Minutes
    let minutes = now.getMinutes().toString();
    minutes = minutes.length == 1 ? 0+minutes : minutes;
    // Format Seconds
    let seconds = now.getSeconds().toString();
    seconds = seconds.length == 1 ? 0+seconds : seconds;

    // Format Date/Day
    let day = now.getDate().toString();
    day = day.length == 1 ? 0+day : day;
    // Format Date/Month
    let month = now.getMonth().toString();
    month = month.length == 1 ? 0+month : month;

    let time = `${hours}:${minutes}:${seconds}`;
    let date = `${day}/${month}/${now.getFullYear()}`;

    return {
        'time': time,
        'date': date
    };
}

/**
 * 
 * @param {*} data 
 * @param {*} task 
 * @param {*} type 
 */
function whatsappFormat(data, task)
{
    let template = ``;
    let formatedType = 'Check-In';
    if(data.type == 'check-out'){
        formatedType = 'Check-Out';
    }

    template += `*${formatedType}*`;
    template += `%0A${data.name} | ${data.date} | ${data.time}`;
    template += `%0AActivities`;
    task.forEach((data, row) => {
        template += `%0A- ${data.progress > 0 ? '['+data.progress+'%]' : data.progress} ${data.name}`;
    });
    template += `%0ALocation: `;
    return template;
}