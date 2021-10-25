// Picture Preview
function generateCustomPreview(input)
{
    console.log("Generate Preview is running...");

    let preview_container = $(input).closest('.preview-container');
    let image_container = $(preview_container).find('.img-preview img');
    let reset_button = $(preview_container).find('.btn-preview_remove');
    let fileName;
    let fileLabel = $(preview_container).find('.custom-file-label');

    if (input[0].files[0] && input[0].files[0]) {
        let reader = new FileReader();
        fileName = input[0].files[0].name;
        reader.onload = function(e) {
            // Append Image on Image Container
            image_container.attr('src', e.target.result);
        }

        reader.readAsDataURL(input[0].files[0]);
        // Remove Disabled from Reset Button
        reset_button.prop('disabled', false);
    } else {
        fileName = 'Choose File';
        // Reset Preview
        $(reset_button).click();
    }

    // Set Label
    $(fileLabel).text(fileName);
}
function removeCustomPreview(input, old_value = ''){
    console.log("Remove Preview is running...");

    let preview_container = $(input).closest('.preview-container');
    let image_container = $(preview_container).find('.img-preview img');
    let reset_button = $(preview_container).find('.btn-preview_remove');
    let image_input = $(preview_container).find('.custom-file-input');
    let fileLabel = $(preview_container).find('.custom-file-label');

    // Add Disabled from Reset Button
    $(reset_button).attr('disabled', true);

    // Check if Old Value Exists
    if(old_value != ''){
        // Append Old Value to Image Container
        image_container.attr('src', old_value);
    } else {
        // Remove Preview from Image Container
        image_container.removeAttr('src');
    }

    // Set Input and Input Label
    image_input.val('');
    fileLabel.text('Choose file');
}

// Convert First Character of Words to Uppercase
function ucwords(str){
    str = str.toLowerCase().replace(/\b[a-z]/g, (letter) => {
        return letter.toUpperCase();
    });

    return str;
}

// Select2 Function to add style for matches key
function markMatch(text, term){
    // Find where the match is
    var match = text.toUpperCase().indexOf(term.toUpperCase());
    var $result = $('<span></span>');

    // If there is no match, move on
    if (match < 0) {
        return $result.text(text);
    }

    // Put in whatever text is before the match
    $result.text(text.substring(0, match));
    // Mark the match
    var $match = $('<span class="select2-rendered__match" style="text-decoration: underline;"></span>');
    $match.text(text.substring(match, match + term.length));
    // Append the matching text
    $result.append($match);
    // Put in whatever is after the match
    $result.append(text.substring(match + term.length));

    return $result;
}

// Generate Slug (source element_id, target element_id)
function makeSlug(string){
    let slug = string
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-');
    if(slug.slice(-1) == "-"){
        slug = slug.slice(0, -1);
    }

    return slug;
}
function generateSlug(source, target){
    let title = $(`#${source}`).val();
    let slug = makeSlug(title);
    $(`#${target}`).val(slug).trigger('change');
}
// Indonesian Format
function formatRupiah(angka, prefix = 'Rp'){
    var	reverse = angka.toString().split('').reverse().join(''),
	rupiah 	= reverse.match(/\d{1,3}/g);
    rupiah	= rupiah.join('.').split('').reverse().join('');
    
    return prefix == undefined ? rupiah : prefix+" "+rupiah;
}

// Indonesian Format
function formatRupiah(angka, prefix = 'Rp'){
    var	reverse = angka.toString().split('').reverse().join(''),
	rupiah 	= reverse.match(/\d{1,3}/g);
    rupiah	= rupiah.join('.').split('').reverse().join('');
    
    if(angka < 0){
        return prefix == undefined ? `-(${rupiah})` : `-(${prefix} ${rupiah})`;
    }
    return prefix == undefined ? rupiah : prefix+" "+rupiah;
}

// Indonesian Format (Month)
function formatBulan(angka){
    let name = '';
    switch(angka){
        case 1:
            name = 'Januari';
            break;
        case 2:
            name = 'Februari';
            break;
        case 3:
            name = 'Maret';
            break;
        case 4:
            name = 'April';
            break;
        case 5:
            name = 'Mei';
            break;
        case 6:
            name = 'Juni';
            break;
        case 7:
            name = 'Juli';
            break;
        case 8:
            name = 'Agustus';
            break;
        case 9:
            name = 'September';
            break;
        case 10:
            name = 'Oktober';
            break;
        case 11:
            name = 'November';
            break;
        case 12:
            name = 'Desember';
            break;
    }

    return name;
}

// Generate Random RGB Value
function randomRgb()
{
    var o = Math.round, r = Math.random, s = 255;
    
    return [
    	o(r()*s),
        o(r()*s), 
        o(r()*s)
    ];
}