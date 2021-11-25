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
    let month = (now.getMonth() + 1).toString();
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
    let location = '-';
    if(data.location != ''){
        location = data.location;
    }

    template += `*${formatedType}*`;
    template += `%0A${data.name} | ${data.date} | ${data.time}`;
    template += `%0AActivities`;
    task.forEach((data, row) => {
        template += `%0A- ${data.progress > 0 ? '['+data.progress+'%] ' : ''}${data.name}`;
    });
    template += `%0ALocation: ${data.location}`;
    return template;
}

/**
 * 
 * @param {*} date 
 * @param {*} type 
 */
function convertMomentJsToIndonesia(rawDate, type = 'days'){
    let date = moment(rawDate);
    let result = '';

    switch(type){
        case 'days':
            result = moment(date).format('dddd');

            switch(result){
                case 'Monday':
                    result = "Senin";
                    break;
                case 'Tuesday':
                    result = "Selasa";
                    break;
                case 'Wednesday':
                    result = "Rabu";
                    break;
                case 'Thursday':
                    result = "Kamis";
                    break;
                case 'Friday':
                    result = "Jum'at";
                    break;
                case 'Saturday':
                    result = "Sabtu";
                    break;
                case 'Sunday':
                    result = "Minggu";
                    break;
            }
            break;
        case 'months':
            result = moment(date).format('MMMM');

            switch(result){
                case 'January':
                    result = 'Januari';
                    break;
                case 'February':
                    result = 'Februari';
                    break;
                case 'March':
                    result = 'Maret';
                    break;
                case 'April':
                    result = 'April';
                    break;
                case 'May':
                    result = 'Mei';
                    break;
                case 'June':
                    result = 'Juni';
                    break;
                case 'July':
                    result = 'July';
                    break;
                case 'August':
                    result = 'Agustus';
                    break;
                case 'September':
                    result = 'September';
                    break;
                case 'October':
                    result = 'Oktober';
                    break;
                case 'November':
                    result = 'November';
                    break;
                case 'December':
                    result = 'December';
                    break;
            }
            break;
    }

    return result;
}